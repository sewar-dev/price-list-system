<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;


    protected $fillable = ['name', 'base_price', 'description'];

    public function priceLists()
    {
        return $this->hasMany(PriceList::class);
    }
}
