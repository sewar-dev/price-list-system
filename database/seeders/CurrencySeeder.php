<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Concerns\HasDefaultCurrencies;
use App\Models\Currency;
use Illuminate\Database\Seeder;

final class CurrencySeeder extends Seeder
{
    use HasDefaultCurrencies;
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Currency::query();

        foreach ($this->currencies() as $key => $value) {
            Currency::query()->create($value);
        }
    }
}
