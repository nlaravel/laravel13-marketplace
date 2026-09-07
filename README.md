# 🛒 Laravel Marketplace

A production-oriented marketplace backend built with **Laravel 13**, focused on clean architecture, maintainable business logic, automated testing, authentication, authorization, e-commerce workflows, and modern Laravel development practices.

The project is being developed as a **portfolio-quality application** with an incremental roadmap toward a scalable marketplace platform.

---

## 🚀 Overview

Laravel Marketplace is a multi-vendor marketplace application designed to demonstrate real-world backend engineering practices.

The platform is being built around the following core concepts:

* Customer accounts and authentication
* Role-based access control
* Products and product variants
* Inventory management
* Shopping cart
* Checkout
* Orders and order lifecycle
* Addresses
* Seller orders
* Payments
* Notifications
* Background jobs
* Real-time communication
* Redis and caching
* RESTful APIs
* Automated testing
* Docker
* CI/CD
* API documentation
* AI-powered product search

The project emphasizes **clean code, separation of concerns, testability, scalability, and maintainability**.

---

## 🎯 Project Goals

The main goal is to build a realistic Laravel marketplace while demonstrating practical experience with:

* Modern Laravel architecture
* PHP 8.5+
* Laravel 13
* Service Layer architecture
* Dependency Injection
* Livewire
* REST APIs
* MySQL
* Authentication and authorization
* Database design
* E-commerce workflows
* Inventory reservation
* Order management
* Automated testing
* GitHub Actions
* Docker
* Redis
* Real-time applications
* AI-assisted search

---

# 🛠 Tech Stack

## Backend

* PHP 8.5+
* Laravel 13
* Laravel Fortify
* Laravel Livewire 4
* MySQL
* Redis
* Laravel Reverb
* Laravel Queues / Jobs
* Events & Listeners
* Notifications

## Frontend

* Blade
* Livewire
* Tailwind CSS 4
* Vite
* JavaScript

## Development

* Git
* GitHub
* Composer
* npm
* Laragon
* Mailpit

## Testing

* PHPUnit
* Laravel Feature Tests
* Laravel Unit Tests
* Database Testing

## DevOps

* GitHub Actions
* CI/CD
* Docker

## API

* RESTful APIs
* API Authentication
* API Documentation

## AI

* Natural-language product search
* Semantic search
* Search ranking
* AI-assisted marketplace features

---

# 📌 Project Status

The project is under active development.

### ✅ Implemented

* Laravel 13 application
* MySQL database
* Authentication with Laravel Fortify
* User registration
* Login / Logout
* Password reset flow
* Role & permission infrastructure
* Customer dashboard
* Addresses
* Products foundation
* Product variants
* Inventory foundation
* Inventory reservation / release
* Shopping cart
* Checkout workflow
* Orders
* Order items
* Seller orders
* Order addresses
* Order status management
* Customer order listing
* Customer order details
* Order cancellation
* Dashboard statistics
* Livewire components
* Service Layer architecture
* Automated tests
* Laravel Pint
* GitHub Actions CI
* MySQL service in CI
* Automated test execution in GitHub Actions

### 🚧 Planned / In Progress

* Advanced seller management
* Payment gateway integration
* Payment webhooks
* Advanced delivery management
* Real-time delivery tracking
* Laravel Reverb integration
* Redis caching
* Queue workers
* Advanced notifications
* Docker environment
* API documentation
* AI-powered product search
* Semantic search and ranking
* Production deployment

---

# 🏗 Architecture

The project follows a layered approach where business logic is separated from presentation and framework-specific concerns.

### Main principles

* Thin controllers
* Thin Livewire components
* Service Layer for business logic
* Dependency Injection
* Explicit return types
* Form/request validation
* Eloquent models for domain relationships
* Database transactions for critical workflows
* Reusable business services
* Automated tests around business logic

### Example architecture

```text
Livewire / Controller
        │
        ▼
   Service Layer
        │
        ├── Validation
        ├── Business Rules
        ├── Transactions
        └── Domain Operations
        │
        ▼
     Eloquent
        │
        ▼
      MySQL
```

This approach keeps application logic reusable between:

* Web interfaces
* Livewire components
* REST APIs
* Console commands
* Jobs
* Future integrations

---

# 🔐 Authentication

Authentication is implemented using **Laravel Fortify**.

Current authentication functionality includes:

* Registration
* Login
* Logout
* Password reset
* Password reset email
* Password confirmation
* Login rate limiting
* Authentication security

Additional Fortify capabilities can be enabled as the project evolves.

---

# 👥 Roles & Permissions

The marketplace is designed around role-based access control.

Planned roles include:

```text
Admin
Seller
Customer
Delivery
```

Permissions are handled using a dedicated authorization layer.

The goal is to allow each role to access only the operations relevant to its responsibilities.

---

# 🛍 Marketplace

## Products

The product system is designed to support:

* Product creation
* Product updates
* Product deletion
* Categories
* Product images
* Product pricing
* Product variants
* Product status
* Product availability

---

# 📦 Inventory

Inventory management is designed around reliable stock handling.

Current architecture includes inventory reservation and release workflows.

Planned functionality includes:

* Stock quantities
* Stock updates
* Stock reservations
* Stock release
* Low-stock detection
* Inventory history
* Concurrent stock protection

Example checkout flow:

```text
Cart
  │
  ▼
Validate Items
  │
  ▼
Validate Stock
  │
  ▼
Reserve Inventory
  │
  ▼
Create Order
  │
  ▼
Payment
```

---

# 🛒 Shopping Cart

The cart system supports the core shopping workflow.

Features include:

* Add products
* Remove products
* Update quantities
* View cart
* Calculate subtotal
* Track cart items
* Validate cart during checkout

---

# 💳 Checkout

Checkout coordinates several business operations.

The checkout workflow is designed around:

```text
Customer
   │
   ▼
Cart Validation
   │
   ▼
Address Validation
   │
   ▼
Inventory Validation
   │
   ▼
Inventory Reservation
   │
   ▼
Order Creation
   │
   ▼
Payment
```

Critical operations are handled using database transactions to help preserve data consistency.

---

# 📦 Orders

Orders are one of the core domain areas of the application.

The order system includes:

* Orders
* Order items
* Seller orders
* Order addresses
* Customer ownership
* Order totals
* Order status
* Cancellation
* Inventory release

## Order lifecycle

The planned order lifecycle is:

```text
Pending
   ↓
Confirmed
   ↓
Preparing
   ↓
Ready for Delivery
   ↓
Out for Delivery
   ↓
Delivered
```

Orders may also enter terminal states such as:

```text
Cancelled
Refunded
Failed
```

Customers can:

* View their orders
* Open order details
* View order items
* View order addresses
* Cancel eligible orders

---

# 📊 Customer Dashboard

The customer dashboard provides an overview of account activity.

Current dashboard functionality includes:

* Total orders
* Total addresses
* Cart items
* Recent orders
* Default address
* Orders by month
* Orders by status
* Order activity charts

The dashboard uses dedicated services for business/data retrieval while keeping the Livewire component focused on presentation.

---

# ⚡ Livewire Architecture

Livewire is used for interactive server-rendered application functionality.

The project follows a **Traditional Livewire component structure**.

Example:

```text
app/
└── Livewire/
    └── Customer/
        ├── Dashboard.php
        └── Orders/
            ├── Index.php
            └── Show.php
```

The components are intentionally kept thin.

Business logic is delegated to dedicated services.

Example:

```php
public function boot(OrderService $orderService): void
{
    $this->orderService = $orderService;
}
```

Computed properties are used where appropriate:

```php
#[Computed]
public function orders(): LengthAwarePaginator
{
    return $this->orderService->getCustomerOrders(
        auth()->user()
    );
}
```

---

# 🧩 Service Layer

Business logic is organized into dedicated services.

Example:

```text
app/
└── Services/
    ├── Cart/
    ├── Checkout/
    ├── Inventory/
    ├── Orders/
    └── Customer/
```

This provides:

* Reusable business logic
* Easier testing
* Cleaner controllers
* Cleaner Livewire components
* Better separation of concerns
* Easier future API integration

---

# 🧪 Testing

The project uses PHPUnit and Laravel's testing tools.

Tests cover critical application behavior including:

* Authentication
* Authorization
* Cart
* Checkout
* Inventory
* Orders
* Services
* Database operations

Run the test suite with:

```bash
php artisan test
```

---

# 🎨 Code Quality

Laravel Pint is used to maintain consistent PHP formatting.

Run Pint locally:

```bash
php vendor/bin/pint
```

Check formatting without modifying files:

```bash
php vendor/bin/pint --test
```

The CI pipeline uses:

```bash
vendor/bin/pint --test
```

This ensures that improperly formatted code does not pass CI.

---

# 🔄 CI/CD

GitHub Actions is used for continuous integration.

The CI pipeline currently performs:

```text
Push / Pull Request
        │
        ▼
Checkout Repository
        │
        ▼
Setup PHP
        │
        ▼
Install Composer Dependencies
        │
        ▼
Validate Composer
        │
        ▼
Prepare Environment
        │
        ▼
Run Laravel Pint
        │
        ▼
Start MySQL Service
        │
        ▼
Run Automated Tests
```

The CI environment uses a MySQL service container so database-dependent tests can run automatically.

This helps ensure that every change is validated before being merged.

---

# 🗄 Database

The project uses MySQL as the primary relational database.

Local example:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=marketplace
DB_USERNAME=root
DB_PASSWORD=
```

For testing, CI uses a dedicated database:

```text
marketplace_testing
```

---

# 📧 Local Email Testing

Mailpit is used during local development to capture outgoing emails.

Example configuration:

```env
MAIL_MAILER=smtp
MAIL_HOST=127.0.0.1
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_FROM_ADDRESS="no-reply@marketplace.test"
MAIL_FROM_NAME="Marketplace"
```

Mailpit web interface:

```text
http://localhost:8025
```

This allows password reset and application emails to be tested without sending real emails.

---

# 💻 Requirements

Before running the project locally, install:

* PHP 8.5+
* Composer
* Node.js 20.19+ or 22.12+
* npm
* MySQL 8+
* Git

Recommended local development environment:

* Laragon
* PhpStorm
* Mailpit

---

# 📥 Installation

## 1. Clone the repository

```bash
git clone https://github.com/nlaravel/laravel13-marketplace.git
```

```bash
cd laravel13-marketplace
```

## 2. Install PHP dependencies

```bash
composer install
```

## 3. Install JavaScript dependencies

```bash
npm install
```

## 4. Create the environment file

### Windows

```cmd
copy .env.example .env
```

### Linux / macOS

```bash
cp .env.example .env
```

## 5. Generate application key

```bash
php artisan key:generate
```

## 6. Configure MySQL

Create a database named:

```text
marketplace
```

Then configure `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=marketplace
DB_USERNAME=root
DB_PASSWORD=
```

Adjust the credentials according to your local MySQL installation.

## 7. Run migrations

```bash
php artisan migrate
```

For development only:

```bash
php artisan migrate:fresh
```

> ⚠️ `migrate:fresh` deletes existing database tables. Never use it against production data.

## 8. Clear Laravel caches

```bash
php artisan optimize:clear
```

---

# 🎨 Frontend

Start Vite during development:

```bash
npm run dev
```

Build production assets:

```bash
npm run build
```

---

# ▶️ Running the Application

Start Laravel:

```bash
php artisan serve
```

Or use Laragon's local virtual host.

Example:

```text
http://marketplace-api.test
```

---

# 🔑 Authentication Routes

Main authentication routes include:

```text
/register
/login
/logout
/forgot-password
/reset-password/{token}
```

Additional authentication functionality is provided through Laravel Fortify.

---

# 🧱 Project Structure

```text
laravel13-marketplace/
│
├── app/
│   ├── Actions/
│   │   └── Fortify/
│   │
│   ├── Enums/
│   │
│   ├── Exceptions/
│   │
│   ├── Http/
│   │   └── Controllers/
│   │
│   ├── Livewire/
│   │
│   ├── Models/
│   │
│   ├── Providers/
│   │
│   └── Services/
│
├── bootstrap/
│
├── config/
│
├── database/
│   ├── factories/
│   ├── migrations/
│   └── seeders/
│
├── resources/
│   ├── css/
│   ├── js/
│   └── views/
│
├── routes/
│
├── tests/
│   ├── Feature/
│   └── Unit/
│
├── .github/
│   └── workflows/
│
├── .env.example
├── composer.json
├── package.json
├── phpunit.xml
├── vite.config.js
└── README.md
```

---

# 🗺️ Development Roadmap

The project is being developed incrementally.

```text
Laravel 13
    ↓
Database Design
    ↓
Authentication
    ↓
Roles & Permissions
    ↓
Products
    ↓
Inventory
    ↓
Cart
    ↓
Checkout
    ↓
Orders
    ↓
Payments
    ↓
Events & Jobs
    ↓
Notifications
    ↓
Delivery
    ↓
Laravel Reverb
    ↓
Redis / Caching
    ↓
Testing
    ↓
Docker
    ↓
CI/CD
    ↓
API Documentation
    ↓
AI Search
```

---

# 💳 Payments

The payment architecture is designed to support:

* Payment creation
* Payment status
* Payment confirmation
* Failed payments
* Refunds
* Payment webhooks

A specific payment provider will be integrated during the payment implementation stage.

---

# 🚚 Delivery

The delivery module is planned to support:

* Delivery assignment
* Delivery status
* Driver information
* Order tracking
* Delivery location
* Delivery history
* Real-time delivery updates

---

# ⚡ Real-Time Communication

Laravel Reverb is planned for real-time communication.

Potential use cases:

* Order status updates
* Delivery tracking
* Live location updates
* Real-time notifications
* Seller/customer updates

Architecture:

```text
Customer
   │
   ▼
Laravel Application
   │
   ▼
Event
   │
   ▼
Laravel Reverb
   │
   ├── Customer
   ├── Seller
   └── Delivery
```

---

# ⚙️ Events & Queues

Laravel Jobs, Queues, Events, and Listeners will be used for background processing.

Potential jobs include:

* Sending emails
* Processing notifications
* Updating order status
* Payment processing
* Inventory operations
* Delivery updates

---

# 🔔 Notifications

The notification architecture is designed to support:

* Database notifications
* Email notifications
* Real-time notifications

Example events:

```text
Order Created
Payment Successful
Order Confirmed
Order Shipped
Delivery Assigned
Order Delivered
```

---

# 🚀 Redis & Caching

Redis will be introduced for:

* Application caching
* Sessions
* Queues
* Rate limiting
* Frequently accessed data

The goal is to improve performance and scalability.

---

# 🐳 Docker

Docker support is planned to provide a consistent development and deployment environment.

Planned services:

```text
Laravel
MySQL
Redis
Mailpit
Reverb
Queue Worker
```

---

# 📚 API Documentation

The application is designed to expose RESTful APIs for:

* Web applications
* Mobile applications
* Third-party integrations

API documentation will cover:

* Authentication
* Endpoints
* Request parameters
* Validation
* Responses
* HTTP status codes
* Error handling
* Examples

---

# 🤖 AI Product Search

The final stage of the project will introduce AI-powered product search.

Planned architecture:

```text
User Query
    ↓
Query Processing
    ↓
Semantic Understanding
    ↓
Product Retrieval
    ↓
Search Ranking
    ↓
Relevant Products
```

The goal is to allow users to search using natural language rather than relying only on exact keyword matching.

Example:

```text
"comfortable black running shoes under $100"
```

The system can eventually understand:

* Product type
* Attributes
* Color
* Price
* User intent
* Relevance

---

# 🔒 Security

Security is considered throughout the application architecture.

The project includes or plans to include:

* Authentication
* Authorization
* Password hashing
* Rate limiting
* Input validation
* CSRF protection
* Permission checks
* Secure API authentication
* Secure payment handling
* Environment-based secrets

### Environment security

Never commit:

```text
.env
```

Only commit:

```text
.env.example
```

Sensitive credentials must always remain outside the repository.

---

# 🤝 Contributing

Contributions and suggestions are welcome.

## 1. Fork the repository

## 2. Create a feature branch

```bash
git checkout -b feature/your-feature
```

## 3. Make your changes

## 4. Run code formatting

```bash
php vendor/bin/pint
```

## 5. Run tests

```bash
php artisan test
```

## 6. Commit your changes

```bash
git commit -m "Add your feature"
```

## 7. Push your branch

```bash
git push origin feature/your-feature
```

## 8. Open a Pull Request

GitHub Actions will automatically validate the changes.

---

# 📄 License

This project is currently developed as a **portfolio and learning project**.

License information will be added when the project reaches its intended production-ready stage.

---

# 👨‍💻 Author

## Noor Abed

**Senior PHP & Laravel Developer**

Specialized in:

* PHP
* Laravel
* Backend Development
* REST APIs
* MySQL
* Laravel Livewire
* Web Applications
* Clean Architecture
* Automated Testing

---

# ⭐ Why This Project?

This project demonstrates practical experience in building a modern Laravel application beyond simple CRUD functionality.

It focuses on real-world engineering concerns such as:

* Business logic separation
* Service-oriented architecture
* Database transactions
* Inventory reservation
* Order lifecycle management
* Authentication and authorization
* Automated testing
* Code quality
* CI/CD
* Scalability
* Real-time communication
* Background processing
* API design
* AI-powered search

---

## 📊 Engineering Focus

```text
Clean Architecture
        +
Laravel 13
        +
Service Layer
        +
Livewire
        +
MySQL
        +
Automated Testing
        +
GitHub Actions
        +
Docker
        +
Redis
        +
Real-Time Communication
        +
AI Search
```

---

## 📌 Repository

GitHub:

https://github.com/nlaravel/laravel13-marketplace

---

## 🚧 Current Status

**Active Development**

The application is being developed incrementally, with a focus on production-quality architecture, testing, maintainability, and scalability.
