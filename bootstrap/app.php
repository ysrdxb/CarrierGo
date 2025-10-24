<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\VerifyOTP;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\TenantMiddleware;
use App\Http\Middleware\SetTenantContext;
use App\Exceptions\InvalidException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Global middleware for setting tenant context
        $middleware->append(SetTenantContext::class);

        $middleware->alias([
            'otp' => VerifyOTP::class,
        ]);

        // admin middleware
        $middleware->alias([
            'admin' => AdminMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
		$exceptions->dontReport([
			InvalidException::class,
		]);
    })->create();
