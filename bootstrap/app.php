<?php

declare(strict_types=1);

use App\Exceptions\CartException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(web: __DIR__ . '/../routes/web.php', api: __DIR__ . '/../routes/api.php', commands: __DIR__ . '/../routes/console.php', health: '/up', )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(fn(ModelNotFoundException $exception) => response()->json([
            'message' => 'Resource not found.',
        ], 404));

        $exceptions->render(fn(CartException $exception) => response()->json([
            'message' => $exception->getMessage(),
        ], 422));
    })
    ->create();
