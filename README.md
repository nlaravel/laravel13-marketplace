# Marketplace API

A modern and scalable marketplace backend built with **Laravel 13**, designed to demonstrate production-ready backend architecture, authentication, APIs, real-time communication, background jobs, payments, delivery, caching, testing, CI/CD, and AI-powered search.

The project is being developed incrementally with a focus on clean architecture, security, scalability, and maintainability.

---

## 🚀 Project Overview

Marketplace is a full-featured e-commerce/marketplace backend where users will be able to:

* Create and manage accounts
* Browse products
* Manage inventory
* Add products to a shopping cart
* Place orders
* Complete payments
* Track delivery
* Receive notifications
* Communicate with real-time services
* Search products using AI-powered search
* Manage marketplace operations through role-based permissions

The project is designed as a **portfolio-quality Laravel application** and follows a progressive development roadmap.

---

# 🛠 Tech Stack

## Backend

* PHP 8.5+
* Laravel 13
* Laravel Fortify
* Laravel Livewire
* MySQL
* Redis
* Laravel Reverb
* Laravel Queue / Jobs
* Laravel Events & Listeners
* Laravel Notifications

## Frontend

* Blade
* Livewire
* Tailwind CSS 4
* Vite
* JavaScript

## Development Tools

* Git
* GitHub
* Laragon
* Mailpit
* Composer
* npm

## Testing

* PHPUnit
* Laravel Feature Tests
* Laravel Unit Tests

## DevOps

* Docker
* GitHub Actions
* CI/CD

## API

* RESTful APIs
* API Authentication
* API Documentation

## AI

* AI-powered product search
* Semantic search
* Search optimization

---

# 📋 Current Project Status

The project is currently under active development.

### Authentication

* [x] User Registration
* [x] User Login
* [x] User Logout
* [x] Forgot Password
* [x] Reset Password
* [x] Password Reset Email
* [x] Two-Factor Authentication
* [x] Passkeys
* [x] Login Rate Limiting
* [x] Password Confirmation
* [ ] Email Verification

### Marketplace

* [ ] Roles & Permissions
* [ ] Products
* [ ] Categories
* [ ] Product Images
* [ ] Inventory
* [ ] Shopping Cart
* [ ] Checkout
* [ ] Orders
* [ ] Payments
* [ ] Delivery
* [ ] Notifications
* [ ] Real-time Tracking

### Infrastructure

* [ ] Redis
* [ ] Queue Workers
* [ ] Events & Listeners
* [ ] Laravel Reverb
* [ ] Automated Tests
* [ ] Docker
* [ ] CI/CD
* [ ] API Documentation

### AI

* [ ] AI Product Search
* [ ] Semantic Search
* [ ] Search Ranking
* [ ] AI-assisted Marketplace Features

---

# 🔐 Authentication

Authentication is implemented using **Laravel Fortify**.

Current authentication features include:

* Registration
* Login
* Logout
* Password reset
* Password reset email
* Two-factor authentication
* Passkeys
* Password confirmation
* Rate limiting

Password reset emails are currently tested locally using **Mailpit**.

---

# 📧 Local Email Testing

During development, the project uses **Mailpit** to capture outgoing emails locally.

Mailpit SMTP:

```text
Host: 127.0.0.1
Port: 1025
```

Mailpit Web Interface:

```text
http://localhost:8025
```

This allows password reset emails and other application emails to be tested without sending real emails.

---

# 💻 Requirements

Before running the project, make sure you have:

* PHP 8.5+
* Composer
* Node.js 20.19+ or 22.12+
* npm
* MySQL
* Git

For local development, **Laragon** can be used.

---

# 📥 Installation

## 1. Clone the repository

```bash
git clone YOUR_GITHUB_REPOSITORY_URL
```

Move into the project:

```bash
cd marketplace-api
```

---

## 2. Install PHP dependencies

```bash
composer install
```

---

## 3. Install JavaScript dependencies

```bash
npm install
```

---

## 4. Create environment file

Copy:

```text
.env.example
```

to:

```text
.env
```

On Windows:

```bash
copy .env.example .env
```

---

## 5. Generate application key

```bash
php artisan key:generate
```

---

# 🗄 Database Configuration

Create a MySQL database for the project.

Example:

```text
Database Name:
marketplace
```

Update `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=marketplace
DB_USERNAME=root
DB_PASSWORD=
```

Adjust the username and password according to your local MySQL installation.

---

# 🧱 Run Migrations

Run:

```bash
php artisan migrate
```

To recreate the database during development:

```bash
php artisan migrate:fresh
```

> ⚠️ `migrate:fresh` deletes existing database tables. Do not use it on production data.

---

# 📧 Mailpit Configuration

Configure local email delivery in `.env`:

```env
MAIL_MAILER=smtp
MAIL_SCHEME=null
MAIL_HOST=127.0.0.1
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_FROM_ADDRESS="no-reply@marketplace.test"
MAIL_FROM_NAME="Marketplace"
```

Clear Laravel configuration cache:

```bash
php artisan optimize:clear
```

Start Mailpit and open:

```text
http://localhost:8025
```

---

# 🎨 Frontend Assets

Install dependencies:

```bash
npm install
```

For development:

```bash
npm run dev
```

For production:

```bash
npm run build
```

---

# ▶️ Running the Application

Start Laravel:

```bash
php artisan serve
```

Or use Laragon's local domain.

Example:

```text
http://marketplace-api.test
```

---

# 🔑 Authentication Routes

Current authentication routes include:

```text
/register
/login
/logout
/forgot-password
/reset-password/{token}
```

Additional security routes are provided by Laravel Fortify.

---

# 📦 Project Structure

```text
marketplace-api/
│
├── app/
│   ├── Actions/
│   │   └── Fortify/
│   │
│   ├── Http/
│   │   └── Controllers/
│   │
│   ├── Models/
│   │
│   └── Providers/
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
├── public/
│
├── resources/
│   ├── css/
│   ├── js/
│   └── views/
│
├── routes/
│
├── storage/
│
├── tests/
│   ├── Feature/
│   └── Unit/
│
├── .env.example
├── composer.json
├── package.json
└── vite.config.js
```

---

# 🏗 Development Roadmap

The project follows the following development plan:

```text
1. Laravel 13
        ↓
2. GitHub
        ↓
3. Database Design
        ↓
4. Authentication
        ↓
5. Roles & Permissions
        ↓
6. Products
        ↓
7. Inventory
        ↓
8. Cart
        ↓
9. Checkout
        ↓
10. Orders
        ↓
11. Payments
        ↓
12. Events & Jobs
        ↓
13. Notifications
        ↓
14. Delivery
        ↓
15. Laravel Reverb / Real-time
        ↓
16. Redis / Caching
        ↓
17. Tests
        ↓
18. Docker
        ↓
19. CI/CD
        ↓
20. API Documentation
        ↓
21. README
        ↓
22. AI Search
```

---

# 👥 Planned Roles

The marketplace will support role-based access control.

Planned roles:

```text
Admin
Seller
Customer
Delivery
```

Each role will have different permissions and responsibilities.

---

# 🛒 Planned Marketplace Features

## Products

The product system will support:

* Product creation
* Product updates
* Product deletion
* Product categories
* Product images
* Product pricing
* Product status
* Product availability

---

## Inventory

The inventory system will handle:

* Stock quantities
* Stock updates
* Stock reservations
* Low-stock detection
* Inventory history

---

## Cart

Users will be able to:

* Add products
* Remove products
* Update quantities
* View cart
* Calculate totals

---

## Checkout

Checkout will handle:

* Customer information
* Address selection
* Cart validation
* Stock validation
* Order creation
* Payment processing

---

# 📦 Orders

Orders will have a lifecycle such as:

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

Orders may also be:

```text
Cancelled
Refunded
Failed
```

---

# 💳 Payments

The payment system will be designed to support:

* Payment creation
* Payment status tracking
* Payment confirmation
* Failed payments
* Refunds
* Payment webhooks

The exact payment provider will be integrated later in the development process.

---

# 🚚 Delivery

Delivery is a core part of the Marketplace architecture.

Planned functionality:

* Delivery assignment
* Delivery status
* Driver information
* Order tracking
* Delivery location
* Delivery history
* Real-time delivery updates

---

# ⚡ Real-Time Features

The project will use **Laravel Reverb** for real-time communication.

Planned features include:

* Real-time order status
* Delivery tracking
* Live location updates
* Notifications
* Seller/customer updates

Example architecture:

```text
Customer
    ↓
Laravel API
    ↓
Event
    ↓
Laravel Reverb
    ↓
Customer / Seller / Delivery
```

---

# ⚙️ Events & Jobs

Background processing will be handled using Laravel Jobs and Queues.

Planned jobs include:

* Send emails
* Process notifications
* Update order status
* Process payments
* Inventory operations
* Delivery updates

---

# 🔔 Notifications

The notification system will support:

* Database notifications
* Email notifications
* Real-time notifications

Examples:

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

The goal is to improve application performance and scalability.

---

# 🧪 Testing

The project will include automated tests for critical business logic.

Testing areas include:

* Authentication
* Permissions
* Products
* Inventory
* Cart
* Checkout
* Orders
* Payments
* Delivery
* APIs

Run tests:

```bash
php artisan test
```

---

# 🐳 Docker

Docker support will be added to provide a consistent development and deployment environment.

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

# 🔄 CI/CD

GitHub Actions will be used for automated workflows.

Planned pipeline:

```text
Push / Pull Request
        ↓
Install Dependencies
        ↓
Run Code Checks
        ↓
Run Tests
        ↓
Build Assets
        ↓
Deployment
```

---

# 📚 API Documentation

The project will provide API documentation for external clients such as:

* Web applications
* Mobile applications
* Third-party integrations

API documentation will include:

* Authentication
* Endpoints
* Request parameters
* Responses
* Validation errors
* HTTP status codes
* Examples

---

# 🤖 AI Search

The final stage of the project will introduce AI-powered product search.

Planned functionality:

```text
User Query
    ↓
Search Processing
    ↓
Semantic Understanding
    ↓
Product Retrieval
    ↓
Ranking
    ↓
Relevant Products
```

The goal is to allow users to search for products using natural language instead of relying only on exact keywords.

---

# 🔒 Security

Security is a major part of the project.

The application will include:

* Authentication
* Authorization
* Password hashing
* Rate limiting
* Input validation
* CSRF protection
* Secure API authentication
* Permission checks
* Secure payment handling
* Environment-based secrets

Sensitive configuration must never be committed to GitHub.

The `.env` file is intentionally excluded from Git.

---

# 🌱 Environment Variables

Never commit:

```text
.env
```

Only:

```text
.env.example
```

should be included in the repository.

Before deploying the application, configure environment variables appropriate for the target environment.

---

# 🤝 Contributing

Contributions are welcome.

1. Fork the repository.
2. Create a feature branch.

```bash
git checkout -b feature/your-feature
```

3. Make your changes.
4. Run tests.

```bash
php artisan test
```

5. Commit your changes.

```bash
git commit -m "Add your feature"
```

6. Push your branch.

```bash
git push origin feature/your-feature
```

7. Open a Pull Request.

---

# 📄 License

This project is currently developed as a portfolio and learning project.

License information will be added as the project approaches its production-ready stage.

---

# 👨‍💻 Author

**Noor Abed**

Senior PHP & Laravel Developer

Specialized in:

* PHP
* Laravel
* Backend Development
* REST APIs
* MySQL
* Laravel Livewire
* Web Applications

---

# ⭐ Project Goals

The main goals of this project are to demonstrate practical experience with:

* Modern Laravel development
* Backend architecture
* Authentication and authorization
* Database design
* REST APIs
* E-commerce workflows
* Payment integration
* Delivery systems
* Real-time applications
* Queues and background processing
* Redis
* Automated testing
* Docker
* CI/CD
* API documentation
* AI-powered search

---

## 📌 Status

🚧 **Currently in development**

The project is being built incrementally, and features marked as incomplete will be implemented throughout the development roadmap.
