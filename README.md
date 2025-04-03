# Dynamic Pricing System - Laravel

[![PHP Version](https://img.shields.io/badge/PHP-8.1%2B-blue)](https://php.net/)
[![Laravel Version](https://img.shields.io/badge/Laravel-10.x-orange)](https://laravel.com)
[![License: MIT](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)

A sophisticated pricing engine for e-commerce platforms supporting multi-dimensional pricing rules, temporal promotions, and global commerce capabilities.

## 🌟 Features

### Core Capabilities
- **Multi-dimensional Pricing Rules**
  - Country-specific pricing with ISO 3166-1 alpha-2 codes
  - Currency-specific rates with ISO 4217 codes
  - Time-bound promotional pricing
  - Priority-based rule resolution

- **Intelligent Price Resolution**
  - Parameter-based price resolution (country/currency/date)
  - Automatic fallback mechanisms:
    - Country → Global
    - Currency → Default (USD)
    - Specific Date → Nearest active period
  - Priority-based conflict resolution

- **RESTful API Endpoints**
  - `GET /api/v1/products`: Paginated product listing
  - `GET /api/v1/products/{id}`: Product detail endpoint
  - Query parameters support:
    - `country_code` (2-letter ISO)
    - `currency_code` (3-letter ISO)
    - `date` (YYYY-MM-DD)
    - `order` (price sorting)

- **Performance Optimizations**
  - Redis-based response caching
  - Query optimization with database indexes
  - Efficient Eloquent relationships

- **Validation & Security**
  - Request parameter validation
  - SQL injection protection
  - XSS prevention

## ⚙️ System Requirements

- PHP 8.1+ with extensions:
  - BCMath
  - Ctype
  - JSON
  - Mbstring
  - PDO
  - Tokenizer
  - XML
- MySQL 5.7+/MariaDB 10.3+
- Redis 6+ (recommended)
- Composer 2.x

## 🚀 Installation Guide

### 1. Clone Repository
```bash
git clone https://github.com/yourrepo/dynamic-pricing-system.git
cd dynamic-pricing-system
```

### 2. Configure Environment
```bash
cp .env.example .env
# Edit .env with your credentials
```

### 3. Install Dependencies
```bash
composer install --optimize-autoloader --no-dev
```

### 4. Database Setup
```bash
php artisan migrate --seed
```

### 5. Optimize
```bash
php artisan optimize:clear
```

### 6. Start Server
```bash
php artisan serve --port=8080
```

## 📚 API Documentation

### Product Listing
```bash
GET /api/v1/products?country_code=US&currency_code=USD&date=2024-03-15&order=lowest-to-highest
```

**Sample Response:**
```json
{
  "data": [
    {
      "id": 1,
      "name": "Premium Widget",
      "base_price": 199.99,
      "final_price": 179.99,
      "currency": "USD",
      "effective_from": "2024-03-01",
      "effective_to": "2024-03-31"
    }
  ],
  "meta": {
    "current_page": 1,
    "per_page": 25
  }
}
```

### Single Product
```bash
GET /api/v1/products/123?country_code=DE&currency_code=EUR
```

**Error Responses:**
```json
{
  "error": {
    "code": 404,
    "message": "Product not found"
  }
}
```

## 🗄️ Database Schema

### Products Table
| Column       | Type         | Description               |
|--------------|--------------|---------------------------|
| id           | BIGINT(20)   | Primary key               |
| name         | VARCHAR(255) | Product name              |
| base_price   | DECIMAL(10,2)| Default price             |
| description  | TEXT         | Product details           |

### Price Lists Table
| Column         | Type         | Constraints               |
|----------------|--------------|---------------------------|
| product_id     | BIGINT(20)   | Foreign key → products.id |
| country_code   | CHAR(2)      | Nullable, index           |
| currency_code  | CHAR(3)      | Nullable, index           |
| price          | DECIMAL(10,2)| NOT NULL                  |
| start_date     | DATE         | Index                     |
| end_date       | DATE         | Index                     |
| priority       | SMALLINT     | Default: 1000             |

**Indexes:**
- Composite index on (country_code, currency_code)
- BTREE index on priority
- Date range index on (start_date, end_date)

## 🔍 Price Resolution Logic

### Priority Rules
1. **Specificity First**
   - Country + Currency match
   - Country match
   - Currency match
   - Global rules

2. **Temporal Relevance**
   - Current date within active period
   - Most recent expired rule
   - Future-dated rules

3. **Priority Numbers**
   - Lower values = higher priority
   - Default priority: 1000

**Example Resolution Flow:**
1. US + USD price list (priority 5)
2. Global USD price list (priority 10)
3. Product base price

## 🧪 Testing Strategy

```bash
php artisan test tests/Feature/PriceListSystemTest
```

### Test Categories
- **Validation Tests**
  - Invalid country/currency codes
  - Malformed dates
  - Non-existent products

- **Price Logic Tests**
  - Priority conflicts
  - Date boundary cases
  - Fallback scenarios

- **Performance Tests**
  - Cache hit/miss rates
  - Query execution times
  - Memory usage profiling

## 📦 Caching Strategy

**Cache Keys:**
```php
$cacheKey = "products:{$countryCode}:{$currencyCode}:{$date}";
```

**Cache Invalidation:**
- Product update → Clear related cache entries
- Price list update → Clear product cache
- Daily cron job → Clear expired promotions

```yaml
# Redis configuration
CACHE_DRIVER=redis
REDIS_CLIENT=predis
REDIS_PREFIX=price_system_
```

## 🤝 Contributing

1. Fork repository
2. Create feature branch
3. Add tests for new features
4. Submit PR with:
   - Code changes
   - Updated documentation
   - Test results
5. Await code review

## 📜 License

MIT License - See [LICENSE](LICENSE) for full text.

## 🔮 Future Roadmap

- [ ] Bulk price list imports
- [ ] Historical price tracking
- [ ] Multi-currency conversion layer
- [ ] Price rule versioning
- [ ] Admin dashboard
```

---

**To use this README:**
1. Create a new file named `README.md`
2. Paste this entire content
3. Replace placeholder values (like `yourrepo` in URLs)
4. Save and commit to your repository

The markdown formatting will automatically render properly on GitHub/GitLab. Let me know if you need any adjustments!
