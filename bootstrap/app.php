<?php

use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\SubscriptionMiddleware;
use HopekellDev\Paystack\Exceptions\PaystackException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin' => AdminMiddleware::class,
            'subscription' => SubscriptionMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );

        $exceptions->reportable(function (PaystackException $e) {
            Log::error('Paystack API error', [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'trace' => $e->getTraceAsString(),
            ]);
        });

        $exceptions->reportable(function (QueryException $e) {
            Log::error('Database query error', [
                'message' => $e->getMessage(),
                'sql' => $e->getSql(),
                'bindings' => $e->getBindings(),
            ]);
        });

        $exceptions->context(fn () => [
            'url' => request()?->fullUrl(),
            'user_id' => request()?->user()?->id,
            'method' => request()?->method(),
        ]);
    })->create();
