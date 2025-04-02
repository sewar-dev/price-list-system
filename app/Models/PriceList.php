<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

class PriceList extends Model
{

    protected $fillable = ['product_id', 'country_code', 'currency_code', 'price', 'start_date', 'end_date', 'priority'];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
        'price'      => 'decimal:2',
        'priority'   => 'integer',
    ];


    public function product()
    {
        return $this->belongsTo(Product::class);
    }


    public function scopeCountry(Builder $query, ?string $countryCode)
    {
        return $query->where(function ($q) use ($countryCode) {
            $q->whereNull('country_code')
                ->when($countryCode, fn($q) => $q->orWhere('country_code', $countryCode));
        });
    }


    public function scopeCurrency(Builder $query, ?string $currencyCode)
    {
        return $query->where(function ($q) use ($currencyCode) {
            $q->whereNull('currency_code')
                ->when($currencyCode, fn($q) => $q->orWhere('currency_code', $currencyCode));
        });
    }


    /**
     * Scope to filter price lists active at a specific date
     *
     * @param Builder $query
     * @param string|null $date Date in YYYY-MM-DD format
     * @return Builder
     */
    public function scopeActiveAt(Builder $query, ?string $date = null): Builder
    {
        $targetDate = $date
            ? Carbon::parse($date)->toDateString()
            : Carbon::now()->toDateString();

        return $query->where(function (Builder $q) use ($targetDate) {
            $q->whereNull('start_date')
                ->orWhere('start_date', '<=', $targetDate);
        })->where(function (Builder $q) use ($targetDate) {
            $q->whereNull('end_date')
                ->orWhere('end_date', '>=', $targetDate);
        });
    }


    public function scopeOrderByPriority(Builder $query, ?string $order)
    {
        $query->orderBy('priority',  $order === 'lowest-to-highest' ? 'asc' : 'desc');
    }
}
