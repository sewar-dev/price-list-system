<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\PriceList;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(TestCase::class, RefreshDatabase::class);


beforeEach(function () {
    $this->product = Product::create([
        'name' => 'Test Product',
        'base_price' => 100.00
    ]);
});

// Database Schema Tests
test('has required database tables', function () {
    $this->assertDatabaseHas('migrations', [
        'migration' => '2025_03_28_221320_create_countries_table'
    ]);
    $this->assertDatabaseHas('migrations', [
        'migration' => '2025_03_28_221447_create_price_lists_table'
    ]);
});


test('price list model relationships work', function () {
    $priceList = PriceList::create([
        'price' => 90.00,
        'priority' => 1,
        'product_id' => $this->product->id
    ]);

    expect($priceList->product)
        ->toBeInstanceOf(Product::class)
        ->id->toBe($this->product->id);
});

// API Endpoint Tests
test('GET /api/v1/products returns correct structure', function () {
    $response = $this->getJson('/api/v1/products');

    $response->assertOk()
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'base_price',
                    'final_price',
                    'currency'
                ]
            ]
        ]);
});

// test('GET /api/v1/products/{id} returns single product', function () {
//     $response = $this->getJson("/api/v1/products/{$this->product->id}");

//     $response->assertOk()
//         ->assertJsonPath('data.id', $this->product->id)
//         ->assertJsonStructure([
//             'data' => [
//                 'id',
//                 'name',
//                 'base_price',
//                 'final_price',
//                 'currency'
//             ]
//         ]);
// });

// // Price Calculation Logic Tests
// test('selects correct price based on priority', function () {
//     PriceList::insert([
//         ['price' => 90.00, 'priority' => 2, 'product_id' => $this->product->id],
//         ['price' => 85.00, 'priority' => 1, 'product_id' => $this->product->id]
//     ]);

//     $response = $this->getJson("/api/v1/products?order=lowest-to-highest");

//     expect($response->json('data.final_price'))->toBe(85.00);
// });

// test('prefers country-specific prices', function () {
//     PriceList::insert([
//         ['price' => 90.00, 'country_code' => 'US', 'product_id' => $this->product->id],
//         ['price' => 95.00, 'country_code' => null ,'product_id' => $this->product->id]
//     ]);

//     $response = $this->getJson("/api/v1/products/{$this->product->id}?country_code=US");

//     expect($response->json('data.final_price'))->toBe(90.00);
// });

// test('falls back to base price when no matches', function () {
//     $response = $this->getJson("/api/v1/products/{$this->product->id}");

//     expect($response->json('data.final_price'))->toBe(100.00);
// });

// // Date Range Tests
// test('respects active date ranges', function () {
//     PriceList::create([
//         'price' => 80.00,
//         'start_date' => '2024-01-01',
//         'end_date' => '2024-01-31',
//         'product_id' => $this->product->id
//     ]);

//     $validResponse = $this->getJson("/api/v1/products/{$this->product->id}?date=2024-01-15");
//     $invalidResponse = $this->getJson("/api/v1/products/{$this->product->id}?date=2024-02-01");

//     expect($validResponse->json('data.final_price'))->toBe(80.00);
//     expect($invalidResponse->json('data.final_price'))->toBe(100.00);
// });

// // Sorting Tests
// test('orders products by price ascending', function () {
//     Product::factory(3)->create(['base_price' => 100]);

//     $response = $this->getJson('/api/v1/products?order=lowest-to-highest');

//     $prices = collect($response->json('data'))->pluck('final_price')->values();
//     expect($prices->toArray())->toBeSorted();
// });

// Validation Tests
// test('rejects invalid country codes', function () {
//     $response = $this->getJson('/api/v1/products?country_code=XYZ');

//     $response->assertStatus(422)
//         ->assertJsonValidationErrors(['country_code']);
// });

// test('normalizes currency codes to uppercase', function () {
//     PriceList::create([
//         'price' => 90.00,
//         'currency_code' => 'USD',
//         'product_id' => $this->product->id
//     ]);

//     $response = $this->getJson("/api/v1/products/{$this->product->id}?currency_code=usd");

//     expect($response->json('data.currency'))->toBe('USD');
// });

// Cache Tests
test('caches product listings', function () {
    Cache::shouldReceive('remember')
        ->once()
        ->andReturnUsing(fn($key, $ttl, $callback) => $callback());

    $this->getJson('/api/v1/products');
});

// test('invalidates cache on product update', function () {
//     Cache::spy();

//     $this->getJson('/api/v1/products');
//     $this->product->update(['name' => 'New Name']);

//     Cache::shouldHaveReceived('forget')->withSomeOfArgs('products|');
// });

// Edge Case Tests
// test('handles overlapping date ranges', function () {
//     PriceList::insert([
//         [
//             'price' => 70.00,
//             'start_date' => '2024-01-01',
//             'end_date' => '2024-01-15',
//             'priority' => 2,
//             'product_id' => $this->product->id
//         ],
//         [
//             'price' => 75.00,
//             'start_date' => '2024-01-10',
//             'end_date' => '2024-01-20',
//             'priority' => 1,
//             'product_id' => $this->product->id
//         ]
//     ]);

//     $response = $this->getJson("/api/v1/products/{$this->product->id}?date=2024-01-12");

//     expect($response->json('data.final_price'))->toBe(75.00);
// });
