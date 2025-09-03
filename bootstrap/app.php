<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Console\Scheduling\Schedule;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
        'role' => \App\Http\Middleware\RoleMiddleware::class,
    'wallet.pin' => \App\Http\Middleware\EnsureWalletPinVerified::class,
    ]);
    })
    ->withSchedule(function (Schedule $schedule) {
        // Chạy command hết hạn giao dịch ví mỗi phút
        $schedule->command('wallet:expire-stale-transactions')
                 ->everyMinute()
                 ->withoutOverlapping()
                 ->runInBackground();
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
