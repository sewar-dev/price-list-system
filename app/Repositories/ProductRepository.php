<?php

namespace App\Repositories;

use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

class  ProductRepository extends Repository
{
    function getModel()
    {
        return new  Product();
    }

    public function getProductsWithPrices(array $params): LengthAwarePaginator
    {
        $cacheKey = $this->generateCacheKey($params);

        return Cache::remember($cacheKey, 3600, function () use ($params) {
            return $this->getModel()->query()
                ->withBestPrice($params)
                ->paginate();
        });
    }


    public function getProductWithPrice(array $params, int $productId): Product
    {
        $cacheKey = $this->generateCacheKey($params);

        return Cache::remember($cacheKey, 3600, function () use ($params, $productId) {
            return $this->getModel()->query()
                ->find($productId)
                ->withBestPrice($params)
                ->first();
        });
    }

    protected function generateCacheKey(array $params, $productId = 0): string
    {
        return md5(implode('|', [
            'products',
            $params['country_code'] ?? '',
            $params['currency_code'] ?? '',
            $params['date'] ?? '',
            $params['order'] ?? '',
            $productId
        ]));
    }
}
