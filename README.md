# BoltBite Food Delivery Platform

## Project Introduction

BoltBite is a comprehensive food delivery platform built with Laravel. The system supports multiple user roles including administrators, merchants, and regular users. 

Client-side features:
- Browse restaurants and menu items
- Add items to shopping cart
- Place orders
- View order history and delivery progress timeline
- Leave comments and ratings on delivered orders

Merchant-side features:
- Manage restaurant menu items (CRUD operations)
- Upload and replace product images
- Toggle items on/off shelf
- View and reply to customer reviews
- Update order status and track delivery progress

## Quick Overview of Scoring Points

CRUD: Complete MenuItem CRUD with validation and error handling. Order creation and viewing with delivery timeline. Comment creation and deletion.

Migration: All database changes in migrations. 21 migrations executed including orders, order_items, comments, delivery_events, users.role.

Auth/Policy: Laravel Breeze authentication. User roles (admin, merchant, user). Policy-based authorization with can middleware. Merchants cannot comment. Merchants can only manage their own items.

Upload/Replace: Image upload service with validation and resizing. Old files deleted on update. storage:link configured. Supports both old and new image formats.

Delivery Progress: Delivery events table tracks order status changes. Timeline displayed in order details. Automatic event creation on status updates.

AWS+HTTPS: Deployed on AWS EC2. Apache configured with HTTPS. HTTP to HTTPS redirection. SSL certificate configured (self-signed).

## Local Run

### Environment

PHP 8.1 or higher
Composer
MySQL 5.7 or higher
Node.js and NPM

### Installation

1. Clone the repository

2. Install PHP dependencies:
composer install

3. Install Node.js dependencies:
npm install

4. Copy environment file:
cp .env.example .env

5. Generate application key:
php artisan key:generate

6. Configure database in .env file:
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=boltbite
DB_USERNAME=your_username
DB_PASSWORD=your_password

7. Build frontend assets:
npm run build

### Database Setup

1. Run migrations:
php artisan migrate

2. Seed database with initial data:
php artisan db:seed

This creates admin, merchant, user, and guest accounts plus sample restaurants and menu items.

3. Create storage link:
php artisan storage:link

## Directory Structure and Main Routes

### Directory Structure

app/Http/Controllers: Application controllers
  BoltBite/: Client-side controllers (RestaurantController, MenuController, CartController, OrderController)
  Merchant/: Merchant-side controllers (MenuItemController, ReviewController)
  Auth/: Authentication controllers

app/Models: Database models
  User, Restaurant, MenuItem, Order, OrderItem, Comment, DeliveryEvent, CartItem, Review

app/Policies: Authorization policies
  MenuItemPolicy, CommentPolicy, OrderPolicy, RestaurantPolicy

app/Services: Business logic services
  ImageUploadService: Handles image upload, resize, validation, deletion

database/migrations: Database schema migrations

resources/views: Blade templates
  boltbite/: Client-side views
  merchant/: Merchant-side views
  orders/: Order views

routes/web.php: Application routes

### Main Routes

Public Routes:
GET /: Homepage
GET /restaurants: Restaurant list
GET /restaurants/{id}: Restaurant details
GET /menu-items/{menuItem}: Menu item details

Authenticated User Routes:
GET /orders: User order list
POST /orders: Create order from cart
GET /orders/{order}: Order details with timeline
POST /orders/{order}/comments: Create comment
DELETE /comments/{comment}: Delete comment
GET /cart: Shopping cart
POST /cart/add: Add item to cart
POST /cart/remove: Remove item from cart
POST /checkout: Process checkout

Merchant Routes (requires merchant role):
GET /merchant/restaurants/{restaurant}/menu: Menu management
POST /merchant/restaurants/{restaurant}/menu-items: Create menu item
PUT /merchant/restaurants/{restaurant}/menu-items/{menuItem}: Update menu item
DELETE /merchant/restaurants/{restaurant}/menu-items/{menuItem}: Delete menu item
POST /merchant/restaurants/{restaurant}/menu-items/{menuItem}/toggle: Toggle item status
PATCH /orders/{order}: Update order status (creates delivery event)

## Demo Accounts and URLs

### Demo Accounts

Administrator:
Email: admin@boltbite.com
Password: password
Role: Admin
Permissions: Full system access

Merchant:
Email: merchant@boltbite.com
Password: password
Role: Merchant
Permissions: Manage restaurants, menu items, reviews

Regular User:
Email: user@boltbite.com
Password: password
Role: User
Permissions: Browse restaurants, add to cart, leave reviews

### Public URL

HTTPS: https://b01812585-uws24.duckdns.org

Note: 
The site uses a self-signed SSL certificate. Browsers will display a security warning. Click Advanced and then Proceed to site to continue.
HTTP requests automatically redirect to HTTPS.

## Key Features

Model Relationships: All models have proper belongsTo and hasMany relationships defined.

Eager Loading: All controllers use with() to prevent N+1 queries.

Form Validation: All forms use Request validation classes with error handling.

Image Management: Images uploaded to storage/app/public/menu-items/. Old images deleted on update.

Delivery Timeline: Order status changes automatically create delivery events. Timeline displayed in order details.

Authorization: Policy-based authorization ensures merchants can only manage their own items and users can only comment on their own delivered orders.

