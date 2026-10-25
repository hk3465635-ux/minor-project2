# FD-Trend Fashion E-Commerce Website - Setup Guide

This guide will help you set up and run the FD-Trend fashion e-commerce website on your local machine.

## Prerequisites

- PHP 8.0 or higher
- MySQL or MariaDB
- A web server (Apache, Nginx, or PHP's built-in server)
- VS Code (recommended) or your preferred code editor

## Database Configuration

### Step 1: Configure Database Credentials

1. Open the `.env.example` file in your project root
2. Copy it to `.env` (if not already present)
3. Update the database credentials to match your local MySQL setup:

```env
FD_DB_HOST=127.0.0.1
FD_DB_NAME=fd_trend_shop
FD_DB_USER=root
FD_DB_PASSWORD=your_mysql_password
```

**Common defaults:**
- XAMPP: host=127.0.0.1, user=root, password=(empty)
- MAMP: host=127.0.0.1, user=root, password=root
- Native MySQL: host=127.0.0.1, user=your_username, password=your_password

### Step 2: Create Database

You have two options:

**Option A: Automatic via migration script (Recommended)**
```bash
php migrate.php
```
This will create the database if it doesn't exist and set up all tables.

**Option B: Manual SQL import**
```bash
mysql -u root -p < database.sql
```

### Step 3: Run Migration

Run the migration script to set up all tables, categories, and sample products:

```bash
php migrate.php
```

This will:
- Create all required database tables
- Set up the 6 main categories (Hoodies, Shoes, T-Shirts, Jeans, Jackets, Accessories)
- Add 10 sample products to each category (60 total)
- Create sample marketing content and reels
- Set up proper relationships and indexes

## Creating the First Admin Account

### Option A: Via Command Line (Recommended)

Run the admin creation script:

```bash
php admin/create_admin.php
```

Follow the prompts to enter:
- Admin username
- Admin password (minimum 10 characters)

### Option B: Via Web Interface

1. Visit `http://localhost/minor%20project2/admin/login.php`
2. If no admin exists, you'll see a link to "first-time admin setup"
3. Click that link or visit `admin/setup.php`
4. Fill in the admin credentials form

**Important:** Store your admin credentials securely. Admin passwords are hashed using PHP's `password_hash()` function.

## Starting the Website

### Option A: Using PHP Built-in Server (Quickest)

From your project directory:

```bash
php -S localhost:8000
```

Then visit: `http://localhost:8000`

### Option B: Using VS Code with PHP Server Extension

1. Install the "PHP Server" extension in VS Code
2. Right-click on `welcome.php` in the file explorer
3. Select "PHP Server: Serve project"
4. Choose a port (default is usually 8000)
5. The browser will open automatically

### Option C: Using Apache/XAMPP/MAMP

1. Move the project folder to your web server's document root:
   - XAMPP: `C:/xampp/htdocs/minor_project2`
   - MAMP: `/Applications/MAMP/htdocs/minor_project2`
   - Apache: `/var/www/html/minor_project2`

2. Start Apache and MySQL from your control panel
3. Visit: `http://localhost/minor_project2`

## Key URLs

- **Welcome/Login Page**: `http://localhost:8000/welcome.php` or `http://localhost:8000/`
- **Homepage**: `http://localhost:8000/index.php`
- **User Login**: `http://localhost:8000/login.php`
- **User Registration**: `http://localhost:8000/register.php`
- **Admin Login**: `http://localhost:8000/admin/login.php`
- **Admin Dashboard**: `http://localhost:8000/admin/panel.php`
- **Shop**: `http://localhost:8000/shop.php`
- **Categories**: `http://localhost:8000/categories.php`
- **Reels**: `http://localhost:8000/reels.php`
- **Cart**: `http://localhost:8000/cart.php`
- **User Account**: `http://localhost:8000/account.php`

## User Guide

### For Customers

1. **Registration**: Click "Create a customer account" on the welcome page
2. **Login**: Use your email and password on the welcome page
3. **Browsing**: Browse products by category or use the search function
4. **Shopping Cart**: Add items to cart, update quantities, proceed to checkout
5. **Checkout**: Fill in delivery details (Cash on Delivery only)
6. **Order History**: View past orders in your account section
7. **Wishlist**: Save items for later purchase

### For Administrators

1. **Login**: Use your admin credentials on the welcome page (Admin Login section)
2. **Dashboard Overview**: View store statistics (products, users, orders, sales)
3. **Manage Products**:
   - Add new products with images, sizes, colors, and stock
   - Edit existing product details
   - Archive or delete products
   - Upload product images
4. **Manage Categories**: Create and organize product categories
5. **Manage Orders**:
   - View all customer orders
   - Update order status (Placed → Processing → Shipped → Delivered)
   - Cancel orders (restores stock)
6. **Manage Content**:
   - Add fashion reels with video URLs
   - Create marketing campaigns
   - Write blog articles
7. **Manage Customers**: View registered users and their order history
8. **Manage Reviews**: Moderate customer product reviews

## Security Features

- **Password Hashing**: All passwords use `password_hash()` with PHP's default algorithm
- **CSRF Protection**: All forms include CSRF tokens
- **SQL Injection Prevention**: All database queries use PDO prepared statements
- **Session Security**: Secure session cookies with HttpOnly and SameSite flags
- **File Upload Validation**: Image uploads are validated for type and size
- **Admin Protection**: Admin pages require server-side authentication checks
- **Configuration Protection**: Sensitive files (.env, .sql, config files) are blocked from web access

## Category Pages

The website includes 6 main categories, each with 10+ products:

1. **Hoodies** - `hoodies.php` - Oversized, zip-up, printed, and more
2. **Shoes** - `shoes.php` - Sneakers, canvas, leather, and athletic shoes
3. **T-Shirts** - `tshirts.php` - Heavyweight, pocket, striped, and graphic tees
4. **Jeans** - `jeans.php` - Straight, relaxed, tapered, and wide-leg denim
5. **Jackets** - `jackets.php` - Denim, utility, quilted, and windbreaker jackets
6. **Accessories** - `accessories.php` - Bags, belts, scarves, and sunglasses

## Fashion Reels Section

The reels section (`reels.php`) features:
- Vertical video cards with play/pause controls
- Like and view counters (stored in database)
- Product links for shopping featured items
- Admin can upload video URLs and thumbnails
- 6 sample reels included (one per category)

## File Structure

```
minor project2/
├── admin/                  # Admin panel files
│   ├── panel.php          # Main admin dashboard
│   ├── login.php          # Admin login page
│   ├── setup.php          # First-time admin setup
│   └── create_admin.php   # CLI admin creation script
├── assets/                # Frontend assets
│   └── store.js           # JavaScript functionality
├── partials/              # Reusable PHP components
│   ├── site_header.php    # Navigation header
│   └── reels_feed.php     # Reels listing component
├── uploads/               # User-uploaded content
│   ├── catalog/           # Auto-generated product images
│   ├── products/          # Product images
│   └── reels/             # Reel videos/thumbnails
├── confi.php              # Database configuration
├── config.php             # Main config loader
├── welcome.php            # Welcome/login screen
├── index.php              # Homepage
├── login.php              # User login
├── register.php           # User registration
├── shop.php               # Product catalog
├── cart.php               # Shopping cart
├── checkout.php           # Checkout process
├── account.php            # User account
├── wishlist.php           # User wishlist
├── orders.php             # Guest order tracking
├── reels.php              # Fashion reels listing
├── reel.php               # Individual reel view
├── marketing.php          # Marketing content
├── contact.php            # Contact form
├── migrate.php            # Database migration script
├── database.sql           # Initial database schema
├── .env                   # Database credentials (not in git)
├── .env.example           # Database credentials template
├── .htaccess              # Apache configuration
└── SETUP.md               # This file
```

## Troubleshooting

### Database Connection Error

If you see "Database connection failed":
1. Check that MySQL/MariaDB is running
2. Verify credentials in `.env` match your MySQL setup
3. Ensure the database `fd_trend_shop` exists (or let migrate.php create it)

### Images Not Displaying

If product images don't show:
1. Ensure the `uploads/` directory exists and is writable
2. Run `php migrate.php` to generate catalog illustrations
3. Check file permissions on uploads directory

### Admin Login Not Working

If you can't log in as admin:
1. Create a new admin: `php admin/create_admin.php`
2. Verify you're using the correct username and password
3. Check that the admins table has at least one record

### Session Issues

If login sessions don't persist:
1. Ensure `session_save_path` is writable
2. Check that cookies are enabled in your browser
3. Verify the session configuration in `confi.php`

## Maintenance

### Adding New Products

1. Log in as admin
2. Go to Admin Dashboard → Products
3. Click "Add Product"
4. Fill in product details, upload images, set sizes/colors/stock
5. Save

### Backing Up Database

```bash
mysqldump -u root -p fd_trend_shop > backup_$(date +%Y%m%d).sql
```

### Restoring Database

```bash
mysql -u root -p fd_trend_shop < backup_20231014.sql
```

## Development Notes

- The site uses PDO for all database operations
- Passwords are hashed with `password_hash()` and verified with `password_verify()`
- Admin sessions are separate from user sessions
- Stock is validated server-side during checkout
- Product images are stored in `uploads/products/`
- Reel videos are stored in `uploads/reels/`
- The migration script preserves existing data during updates

## Support

For issues or questions:
1. Check the troubleshooting section above
2. Review the error logs in your PHP error log
3. Verify database connectivity with: `php -r "require 'config.php'; echo 'Connected';"`

## License

This is a private project. All rights reserved.
