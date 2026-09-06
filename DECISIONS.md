# Architecture Decisions

This document records important architectural decisions made during the development of the marketplace API.

The goal is to document not only **what** was implemented, but also **why** it was implemented this way.

---

## 1. Service Layer

### Decision

Business logic is delegated to dedicated Service classes instead of placing it directly inside Controllers or Livewire components.

### Why

The application contains business operations that involve multiple models, database transactions, validation rules, inventory handling, payments, and order workflows.

Keeping this logic inside Controllers or Livewire components would make those classes:

* harder to test
* harder to reuse
* harder to maintain
* tightly coupled to HTTP/UI concerns
* more likely to become large over time

The Service Layer gives business operations a dedicated place and allows Controllers and Livewire components to remain thin.

The intended flow is:

```text
Controller / Livewire
        ↓
Service
        ↓
Models / Database
```

Controllers and Livewire components are responsible primarily for presentation, input handling, authorization, and calling the appropriate Service.

---

## 2. `DomainException` as the Base Domain Exception

### Decision

Application business-rule failures use an application-level `DomainException` as the common base exception.

The base `DomainException` is abstract. Each feature area can define a more specific concrete exception extending it.

Examples include:

* `CartException`
* `CheckoutException`
* `PaymentException`
* `DeliveryException`
* `InventoryException`

### Why

The application previously used different generic exceptions such as:

* `InvalidArgumentException`
* `RuntimeException`
* feature-specific exceptions

This made domain failures inconsistent and required different exception handling in different parts of the application.

Using one common domain exception gives the application a predictable boundary:

```text
Business rule violation
        ↓
Feature-specific DomainException
        ↓
DomainException
        ↓
Application exception handling
        ↓
HTTP/API response
```

The base exception is abstract so that it represents a category of domain failures rather than being instantiated directly.

Each feature area has its own concrete exception extending `DomainException`.

This keeps error handling in `bootstrap/app.php` centralized while still allowing callers and tests to catch a specific exception type when needed, such as distinguishing a `PaymentException` from a `CheckoutException`.

---

## 3. `lockForUpdate()` for Concurrent Cart and Inventory Operations

### Decision

`lockForUpdate()` is used inside database transactions whenever a row is read and then its quantity/state is modified as part of the same business operation.

### Why

Cart and inventory operations are vulnerable to race conditions.

For example, two requests could attempt to reserve or add stock at almost the same time:

```text
Request A → reads stock = 5
Request B → reads stock = 5

Request A → adds/reserves 4
Request B → adds/reserves 4
```

Without row locking, both requests could make decisions based on the same stale stock value.

`lockForUpdate()` makes the relevant database row unavailable for conflicting updates until the current transaction completes.

This protects critical state such as:

* inventory quantities
* reserved inventory
* existing cart items
* orders being transitioned during payment confirmation

The lock is intentionally used together with a transaction. A row lock without the surrounding transaction would not provide the intended protection.

---

## 4. Livewire Instead of Vue/React for the Web UI

### Decision

The customer-facing web interface uses Traditional Livewire rather than introducing Vue or React.

### Why

The project is primarily a Laravel backend/application and the current UI requirements are server-driven rather than requiring a large client-side application architecture.

Traditional Livewire provides:

* Laravel-native development
* PHP-based component logic
* server-side validation
* easy integration with Laravel authorization and Services
* less frontend infrastructure
* less duplicated business logic between PHP and JavaScript

The project deliberately uses Traditional Livewire:

```text
Component PHP class
        +
Blade view
```

rather than Volt.

This keeps the web layer consistent with the Laravel application while the REST API remains available for external clients such as mobile applications.

---

## 5. Separate `addItem()` and `addItems()`

### Decision

`CartService` exposes separate methods for single-item and batch cart operations:

```php
addItem(...)
addItems(...)
```

### Why

The two operations represent different application use cases.

`addItem()` is optimized for the common single-product operation.

`addItems()` represents a batch operation where multiple items must be processed as one logical transaction.

Keeping them separate makes their responsibilities explicit and allows batch operations to guarantee atomic behavior:

```text
addItems()
    ↓
item 1
    ↓
item 2
    ↓
item 3
    ↓
failure?
    ↓
rollback everything
```

The internal shared logic is kept in a private method so that the two public operations do not duplicate the actual cart-item mutation logic.

This gives us:

* clear public APIs
* transaction control appropriate to each use case
* reusable internal logic
* easier testing
* explicit intent

---

## 6. Testing Conventions

### Exception Assertions

⚠️ **أي `expectException` مع تحقق بعده → لازم `try/finally`.**

عند استخدام PHPUnit's `expectException()` مع وجود assertions يجب تنفيذها بعد العملية التي ترمي الاستثناء، يجب وضع العملية داخل `try/finally` حتى نضمن تنفيذ الـ assertions.

Example:

```php
$this->expectException(CheckoutException::class);
$this->expectExceptionMessage('Cart is empty.');

try {
    $this
        ->actingAs($customer, 'sanctum')
        ->postJson('/api/v1/customer/checkout');
} finally {
    $this->assertDatabaseCount('orders', 0);
}
```

إذا لم توجد أي assertions تحتاج إلى التنفيذ بعد الاستثناء، فلا حاجة لاستخدام `try/finally`.

This convention applies to all Feature and Unit tests where `expectException()` is used.
