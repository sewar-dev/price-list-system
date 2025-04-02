# Price List System - Laravel

[![PHP Version](https://img.shields.io/badge/PHP-8.1.10%2B-brightgreen)](https://php.net/)
[![Laravel Version](https://img.shields.io/badge/Laravel-10.3.3-brightgreen)](https://laravel.com)

A dynamic pricing system for e-commerce platforms that supports country-specific pricing, currency conversion, and time-based promotions.

## Features

* **Product Price Management:** Allows administrators to define price lists for products based on country, currency, and time period.
* **Dynamic Price Application:** Applies the correct price to a product based on the user's country, currency, and the current date.
* **Flexible Pricing Rules:**
    * Price lists can be specific to a country and currency.
    * Price lists can be applicable to all countries or currencies.
    * Prioritized price lists: if multiple applicable price lists exist, the one with the lowest priority number is used.
    * Fallback to base price: if no applicable price list is found, the product's base price is used.
* **API Endpoints:**
    * `GET /api/products`: Lists all products with their applicable prices.
    * `GET /api/products/{id}`: Retrieves a single product with its applicable price based on query parameters (`country_code`, `currency_code`, `date`).
    * `GET /api/products?order=[lowest-to-highest, highest-to-lowest]`: Lists all products with their applicable price, ordered by the applicable price.
* **Caching:** Implements caching to improve API performance.
* **Request Validation:** Uses Laravel request classes to validate API requests.
* **Unit Testing:** Includes unit tests to ensure code quality and reliability.
* **SOLID Principles:** Implements the SOLID principles for maintainable and scalable code.

## Requirements

* PHP >= 8.1
* Composer
* MySQL or other supported database

## Setup

1.  **Clone the repository:**

    ```bash
    git clone https://github.com/sewar-dev/price-list-system.git
    cd  price-list-system
    ```

2.  **Install Composer dependencies:**

    ```bash
    composer install
    ```

3.  **Copy `.env.example` to `.env` and configure your database settings:**

    ```bash
    cp .env.example .env
    ```

4.  **Generate an application key:**

    ```bash
    php artisan key:generate
    ```
5.  **Configure database**

    ```bash
        Edit .env file:
        env 
            DB_CONNECTION=mysql
            DB_HOST=127.0.0.1
            DB_PORT=3306
            DB_DATABASE=your_db_name
            DB_USERNAME=your_db_user
            DB_PASSWORD=your_db_password
    ```
5.  **Run database migrations:**

    ```bash
    php artisan migrate
    ```

6.  **Start the development server:**

    ```bash
    php artisan serve
    ```

7.  **Run Unit tests:**

    ```bash
    php artisan test
    ```

## API Endpoints

* **List all products:**

    ```
    GET /api/products
    ```

    Optional query parameter: `order` (values: `lowest-to-highest`, `highest-to-lowest`).

* **Retrieve a single product:**

    ```
    GET /api/products/{id}
    ```

    Optional query parameters: `country_code`, `currency_code`, `date`.

## Database Schema

* `products`:
    * `id` (INT, primary key)
    * `name` (VARCHAR)
    * `base_price` (DECIMAL)
    * `description` (TEXT, nullable)
* `price_lists`:
    * `id` (INT, primary key)
    * `product_id` (INT, foreign key to `products.id`)
    * `country_code` (VARCHAR, nullable, foreign key to `countries.code`)
    * `currency_code` (VARCHAR, nullable, foreign key to `currencies.code`)
    * `price` (DECIMAL)
    * `start_date` (DATE, nullable)
    * `end_date` (DATE, nullable)
    * `priority` (INT)
* `countries`:
    * `id` (INT, primary key)
    * `code` (VARCHAR, unique)
    * `name` (VARCHAR)
* `currencies`:
    * `id` (INT, primary key)
    * `code` (VARCHAR, unique)
    * `name` (VARCHAR)

## Assumptions

* Country codes are 2 characters.
* Currency codes are 3 characters.
* Priority numbers are integers (lower numbers indicate higher priority).
* Dates are in the format YYYY-MM-DD.

## Contributing

Feel free to contribute by submitting pull requests.

## License

This project is licensed under the MIT License - see the LICENSE file for details.
