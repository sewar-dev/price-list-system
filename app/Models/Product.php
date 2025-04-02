<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    
    protected $casts = [
        'base_price' => 'decimal:2',
        'applicable_price' => 'decimal:2',
    ];

    protected $fillable = ['name', 'base_price', 'description'];

    public function scopeWithBestPrice($query, array $params)
    {
        return $query->with(['bestPrice' => function($q) use ($params) {
            $q->forCountry($params['country_code'])
              ->forCurrency($params['currency_code'])
              ->activeAt($params['date'])
              ->orderByPriority();
        }]);
    }

    public function priceLists()
    {
        return $this->hasMany(PriceList::class);
    }
}
