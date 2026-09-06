---

## 6. Testing Conventions

### Exception Assertions

⚠️ **أي `expectException` مع تحقق بعده → لازم `try/finally`.**

عند استخدام PHPUnit's `expectException()` مع وجود assertions يجب تنفيذها بعد العملية التي ترمي الاستثناء، يجب وضع العملية داخل `try/finally` حتى نضمن تنفيذ الـ assertions.

Example:

```php
$this->expectException(DomainException::class);
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
