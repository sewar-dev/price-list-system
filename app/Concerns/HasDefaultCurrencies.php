<?php

declare(strict_types=1);

namespace App\Concerns;

trait HasDefaultCurrencies
{
    protected function currencies(): array
    {
        return [
            ['code' => 'USD', 'name' => 'United States Dollar'],
            ['code' => 'EUR', 'name' => 'Euro'],
            ['code' => 'CAD', 'name' => 'Canadian Dollar'],
        ];
    }
}
