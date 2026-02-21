-- Products Table Schema
-- Mini Cashier Application - Product Management Module
-- Created: 2026-02-21

-- Drop table if exists (for clean re-creation during development)
DROP TABLE IF EXISTS products;

-- Create products table with proper data types and constraints
CREATE TABLE products (
    -- Primary Key
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    
    -- Product Identification
    sku VARCHAR(50) UNIQUE,                      -- Stock Keeping Unit (optional, can be NULL)
    name VARCHAR(255) NOT NULL,                  -- Product name (required)
    
    -- Pricing (ALWAYS use DECIMAL for money, NEVER FLOAT)
    price DECIMAL(10,2) NOT NULL DEFAULT 0.00,   -- Selling price
    cost DECIMAL(10,2) DEFAULT 0.00,             -- Cost price (for profit calculation)
    
    -- Inventory
    stock INT UNSIGNED NOT NULL DEFAULT 0,       -- Current stock (UNSIGNED prevents negative)
    min_stock INT UNSIGNED DEFAULT 10,           -- Minimum stock alert threshold
    
    -- Categorization
    category VARCHAR(100),                       -- Product category
    unit VARCHAR(20) DEFAULT 'pcs',              -- Unit: pcs, kg, liter, etc.
    
    -- Status
    is_active TINYINT(1) NOT NULL DEFAULT 1,     -- 1=active, 0=inactive/archived
    
    -- Audit Trail
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    -- MySQL 8.0+ CHECK Constraints (prevent invalid data at database level)
    CONSTRAINT chk_price_positive CHECK (price >= 0),
    CONSTRAINT chk_stock_non_negative CHECK (stock >= 0),
    
    -- Indexes for performance
    INDEX idx_name (name),
    INDEX idx_category (category),
    INDEX idx_is_active (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Optional: Insert sample data for testing (commented out for production)
-- INSERT INTO products (name, price, cost, stock, min_stock, category, unit) VALUES
-- ('Kopi Susu Gula Aren', 18000.00, 12000.00, 50, 10, 'Minuman', 'pcs'),
-- ('Nasi Goreng Spesial', 25000.00, 15000.00, 30, 10, 'Makanan', 'pcs'),
-- ('Es Teh Manis', 5000.00, 2000.00, 100, 20, 'Minuman', 'pcs');
