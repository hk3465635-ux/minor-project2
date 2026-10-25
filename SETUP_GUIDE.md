# FD-Trend Fashion E-Commerce Website - Setup Guide

## Overview
This is a fully functional PHP-based fashion e-commerce website with:
- 11 product categories (T-Shirts, Shirts, Jeans, Trousers, Jackets, Hoodies, Watches, Sunglasses, Bags & Purses, Shoes, Accessories)
- 110+ authentic products with real images from Unsplash
- Separate customer and admin authentication systems
- Shopping cart with stock validation
- Order management with Cash on Delivery (simulated payment)
- Admin dashboard for product and order management

## Prerequisites

### Required Software
1. **PHP 8.0 or higher** - Download from [php.net](https://www.php.net/downloads)
2. **MySQL/MariaDB** - Download from [mysql.com](https://dev.mysql.com/downloads/mysql/) or [mariadb.org](https://mariadb.org/downloads/)
3. **Web Server** (choose one):
   - Apache (with mod_php)
   - Nginx (with PHP-FPM)
   - PHP built-in server (for development only)

### For macOS Users
You can use Homebrew:
```bash
brew install php
brew install mysql
brew services start mysql
```

### For Windows Users
- Install XAMPP from [apachefriends.org](https://www.apachefriends.org/) (includes PHP, MySQL, Apache)
- Or install PHP and MySQL separately

### For Linux Users
```bash
sudo apt update
sudo apt install php php-mysql mysql-server
sudo systemctl start mysql
```

## Database Setup

### Step 1: Create Database
Open your MySQL command line or phpMyAdmin and run:

```sql
CREATE DATABASE IF NOT EXISTS fd_trend_shop CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### Step 2: Configure Database Connection
Create a `.env` file in your project root:

```bash
# Copy the example file
cp .env.example .env
```

Edit `.env` with your database credentials:

```env
APP_ENV=development
FD_DB_HOST=127.0.0.1
FD_DB_NAME=fd_trend_shop
FD_DB_USER=root
FD_DB_PASSWORD=your_mysql_password
```

**Important:** Replace `your_mysql_password` with your actual MySQL root password. If you have no password, leave it empty.

### Step 3: Run Database Migration
Run the migration script to create all required tables:

```bash
php migrate.php
```

This will create:
- `admins` - Admin accounts
- `users` - Customer accounts
- `categories` - Product categories
- `products` - Product catalog
- `product_variants` - Size/color variants with stock
- `product_images` - Product image gallery
- `cart_items` - Shopping cart persistence
- `wishlist_items` - Customer wishlists
- `orders` - Customer orders
- `order_items` - Order line items
- `product_reviews` - Customer reviews
- `marketing_posts` - Content and reels
- `newsletter_subscribers` - Email subscriptions
- `contact_messages` - Contact form submissions
- `store_settings` - Site configuration

### Step 4: Seed Product Catalog
Run the seed script to populate the database with 110+ authentic products:

```bash
php seed_full_catalog.php
```

This will:
- Create/verify 11 main categories with subcategories
- Add 10-15 products per category (110+ total)
- Each product includes: name, brand, description, price, MRP, stock, sizes, colors, images
- Create admin account (username: `admin`, password: `Admin123456`)

**Default Admin Credentials:**
- Username: `admin`
- Password: `Admin123456`

⚠️ **Security Note:** Change the admin password after first login!

## Running the Website

### Option 1: PHP Built-in Server (Development)
From your project directory:

```bash
php -S localhost:8080
```

Then open your browser to: `http://localhost:8080`

### Option 2: Apache (Production)
1. Copy project files to Apache web root (e.g., `/var/www/html/` or `htdocs`)
2. Ensure `.htaccess` is present (for URL rewriting)
3. Configure virtual host if needed
4. Restart Apache

### Option 3: Nginx (Production)
Configure Nginx with PHP-FPM:

```nginx
server {
    listen 80;
    server_name your-domain.com;
    root /path/to/project;
    index index.php index.html;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.0-fpm.sock;
        fastcgi_index index.php;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
    }
}
```

## Website Features

### Customer Features
1. **Browse Products**
   - Homepage with featured products
   - 11 category pages (T-Shirts, Shirts, Jeans, etc.)
   - Search functionality
   - Filter by size, color, price, availability

2. **Product Details**
   - High-quality product images
   - Size and color selection
   - Stock availability display
   - Customer reviews
   - Related products

3. **Shopping Cart**
   - Add products with size/color variants
   - Quantity adjustment
   - Stock validation (cannot exceed available stock)
   - Automatic subtotal calculation
   - Persistent cart (saved to database when logged in)

4. **Checkout**
   - Cash on Delivery (simulated)
   - Customer information form
   - Order summary
   - Order confirmation page

5. **Account Management**
   - Customer registration
   - Login/logout
   - Order history
   - Wishlist

### Admin Features
1. **Admin Dashboard**
   - Overview statistics (products, categories, stock, orders, customers, sales)
   - Recent orders list

2. **Product Management**
   - Add new products
   - Edit existing products
   - Update stock levels
   - Archive/delete products
   - Upload product images

3. **Category Management**
   - Create categories
   - Manage subcategories
   - Archive/restore categories

4. **Order Management**
   - View order details
   - Update order status (Placed, Processing, Shipped, Delivered, Cancelled)
   - Customer information

5. **Customer Management**
   - View customer list
   - Order history per customer
   - Lifetime total spent

6. **Content Management**
   - Add marketing posts
   - Create fashion reels
   - Manage content publication

## Testing Checklist

### 1. Homepage
- [ ] Open `http://localhost:8080`
- [ ] Verify hero section displays
- [ ] Check category cards show correct product counts
- [ ] Verify featured products display

### 2. Category Filtering
- [ ] Click "T-Shirts" - verify only T-Shirts appear (15 products)
- [ ] Click "Watches" - verify only Watches appear (13 products)
- [ ] Click "Goggles" - verify only Sunglasses appear (13 products)
- [ ] Click "Bags and Purses" - verify only Bags appear (14 products)
- [ ] Verify each category has at least 10 products

### 3. Customer Authentication
- [ ] Click "Register" - fill form and submit
- [ ] Verify account created
- [ ] Click "Login" - enter credentials
- [ ] Verify successful login and redirect
- [ ] Click "Logout" - verify session cleared
- [ ] Try accessing account page without login - should redirect to login

### 4. Admin Authentication
- [ ] Click "Admin Login" in footer
- [ ] Enter username: `admin`, password: `Admin123456`
- [ ] Verify admin dashboard loads
- [ ] Verify customer login doesn't grant admin access
- [ ] Click "Admin Logout" - verify session cleared

### 5. Product Details
- [ ] Click any product card
- [ ] Verify product details page loads
- [ ] Check size/color selector displays variants
- [ ] Verify stock availability shown
- [ ] Test "Add to Cart" button
- [ ] Test "Buy Now" button

### 6. Shopping Cart
- [ ] Add multiple products to cart
- [ ] Verify cart shows correct images, names, sizes, colors
- [ ] Test quantity increase
- [ ] Test quantity decrease
- [ ] Test remove item
- [ ] Verify subtotal updates automatically
- [ ] Try adding more than available stock - should show error

### 7. Checkout
- [ ] With items in cart, click "Proceed to Checkout"
- [ ] Fill delivery details form
- [ ] Submit order
- [ ] Verify order confirmation page
- [ ] Check cart is empty after order
- [ ] Verify order appears in admin dashboard

### 8. Admin Dashboard
- [ ] Login as admin
- [ ] View overview statistics
- [ ] Add a new product
- [ ] Verify product appears in correct category
- [ ] Edit product stock
- [ ] Verify stock updated on customer-facing site
- [ ] View orders
- [ ] Change order status

## File Structure

```
minor project2/
├── admin/                      # Admin panel
│   ├── login.php              # Admin login page
│   ├── logout.php             # Admin logout
│   ├── panel.php              # Admin dashboard
│   ├── config.php             # Admin config
│   └── setup.php              # First-time admin setup
├── partials/                   # Reusable components
│   └── site_header.php        # Navigation header
├── assets/                     # Static assets
│   └── store.js               # JavaScript functionality
├── uploads/                    # User uploads
│   ├── products/              # Product images
│   └── catalog/               # Generated illustrations
├── config.php                  # Main config (includes confi.php)
├── confi.php                   # Database connection & helper functions
├── index.php                   # Homepage
├── shop.php                    # Product listing with filters
├── product.php                 # Product details page
├── cart.php                    # Shopping cart
├── checkout.php                # Checkout page
├── order_success.php           # Order confirmation
├── login.php                   # Customer login
├── register.php                # Customer registration
├── logout.php                  # Customer logout
├── account.php                 # Customer account page
├── orders.php                  # Order tracking
├── wishlist.php                # Wishlist
├── categories.php              # Category listing
├── tshirts.php                 # T-Shirts category
├── shirts.php                  # Shirts category
├── jeans.php                   # Jeans category
├── trousers.php                # Trousers category
├── jackets.php                 # Jackets category
├── hoodies.php                 # Hoodies category
├── watches.php                 # Watches category
├── goggles.php                 # Sunglasses category
├── bags-and-purses.php         # Bags category
├── shoes.php                   # Shoes category
├── accessories.php             # Accessories category
├── contact.php                 # Contact form
├── about.php                   # About page
├── marketing.php               # Marketing content
├── reels.php                   # Fashion reels
├── newsletter.php              # Newsletter subscription
├── style.css                   # Main stylesheet
├── database.sql                # Initial database schema
├── migrate.php                 # Database migration script
├── seed_full_catalog.php       # Product catalog seeder
├── catalog_data.php            # Product data definitions
├── .env.example                # Environment variables example
├── .env                        # Your environment variables (create this)
├── .htaccess                   # URL rewriting rules
└── SETUP_GUIDE.md             # This file
```

## Security Considerations

### For Development
- Change default admin password immediately
- Keep `.env` file private (it's in .gitignore)
- Don't commit sensitive data

### For Production
1. **Environment Variables**
   - Use strong database passwords
   - Set `APP_ENV=production` in `.env`

2. **File Permissions**
   ```bash
   chmod 755 uploads/
   chmod 644 .env
   ```

3. **HTTPS**
   - Enable SSL/TLS certificate
   - Force HTTPS redirects

4. **Session Security**
   - Already configured with secure cookies
   - Session regeneration on login

5. **CSRF Protection**
   - All forms include CSRF tokens
   - Verified on form submission

6. **SQL Injection Prevention**
   - All queries use PDO prepared statements
   - Never concatenate user input into SQL

7. **XSS Prevention**
   - All output escaped with `e()` function
   - HTML entities encoded

## Troubleshooting

### Database Connection Failed
- Check MySQL is running: `sudo systemctl status mysql` (Linux) or check Services (Windows)
- Verify credentials in `.env` file
- Ensure database `fd_trend_shop` exists

### Products Not Showing
- Run `php seed_full_catalog.php` to populate catalog
- Check categories are active in database
- Verify products have `active=1`

### Login Not Working
- Check session_start() is called (should be in confi.php)
- Verify browser accepts cookies
- Check PHP session directory permissions

### Images Not Loading
- Check uploads directory exists and is writable
- Verify image URLs in database are correct
- Check Unsplash images are accessible

### Admin Panel Not Accessible
- Verify admin account exists in database
- Check password hash is correct
- Ensure session variables are set correctly

## Maintenance

### Adding New Products
1. Login as admin
2. Go to Products section
3. Click "Add new product"
4. Fill product details
5. Upload images or use image URLs
6. Set stock and variants
7. Save

### Updating Stock
1. Login as admin
2. Go to Products section
3. Find product
4. Click "Update stock"
5. Enter new quantity
6. Save

### Managing Orders
1. Login as admin
2. Go to Orders section
3. Click order to view details
4. Change status as needed
5. Cancelled orders restore stock automatically

## Support

For issues or questions:
1. Check this SETUP_GUIDE.md first
2. Review error logs in your PHP error log
3. Check browser console for JavaScript errors
4. Verify database connection and data

## License

This is a demonstration e-commerce project. Product images are from Unsplash (free for commercial use). Brand names are used for demonstration purposes only.
