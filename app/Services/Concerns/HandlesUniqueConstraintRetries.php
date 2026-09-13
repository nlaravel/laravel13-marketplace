<?php

declare(strict_types=1);

namespace App\Services\Concerns;

use Closure;
use Illuminate\Database\QueryException;

trait HandlesUniqueConstraintRetries
{
    protected function attemptWithRetry(Closure $operation, int $maxAttempts = 3): mixed
    {
        $attempts = 0;

        while ($attempts < $maxAttempts) {
            $attempts++;

            try {
                return $operation();
            } catch (QueryException $exception) {
                if (
                    ! $this->isUniqueConstraintViolation($exception)
                    || $attempts >= $maxAttempts
                ) {
                    throw $exception;
                }
            }
        }

        throw new \LogicException('Retry operation failed unexpectedly.');
    }

    private function isUniqueConstraintViolation(QueryException $exception): bool
    {
        return ($exception->errorInfo[1] ?? null) === 1062;
    }
}
