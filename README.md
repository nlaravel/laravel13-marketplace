# Laravel Marketplace

A modern marketplace web application built with **Laravel**, designed to provide a solid backend architecture for managing users, products, and marketplace operations.

## 🚀 Technologies

* **PHP**
* **Laravel**
* **MySQL**
* **REST APIs**
* **Livewire**
* **Blade**
* **Vite**

## ✨ Features

* User authentication and account management
* Marketplace management
* Product management
* Database-driven architecture
* RESTful API support
* Responsive web interface
* Laravel-based backend structure
* MySQL database integration

## 📁 Project Structure

```text
app/          Application logic
bootstrap/    Framework bootstrapping
config/       Application configuration
database/     Migrations, factories and seeders
public/       Public assets and entry point
resources/    Views, CSS and JavaScript
routes/       Application routes
storage/      Application generated files
tests/        Application tests
```

## ⚙️ Installation

### 1. Clone the repository

```bash
git clone  https://github.com/nlaravel/laravel13-marketplace.git
cd laravel-marketplace
```

### 2. Install PHP dependencies

```bash
composer install
```

### 3. Create the environment file

```bash
cp .env.example .env
```

On Windows, you can also copy `.env.example` manually and rename it to `.env`.

### 4. Generate the application key

```bash
php artisan key:generate
```

### 5. Configure the database

Update the database settings in `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=marketplace
DB_USERNAME=root
DB_PASSWORD=
```

### 6. Run migrations

```bash
php artisan migrate
```

### 7. Install frontend dependencies

```bash
npm install
npm run build
```

### 8. Start the development server

```bash
php artisan serve
```

The application will be available at:

```text
http://127.0.0.1:8000
```

## 🔐 Environment Variables

For security reasons, the `.env` file is not included in this repository.

Use `.env.example` as the starting point for your local environment configuration.

## 🧪 Testing

Run the Laravel test suite with:

```bash
php artisan test
```

## 👨‍💻 Author

**Noor Abed**

Senior PHP & Laravel Developer

## 📄 License

This project is intended for demonstration and portfolio purposes.
