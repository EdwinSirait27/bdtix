<?php

// use Illuminate\Foundation\Application;
// use Illuminate\Foundation\Configuration\Exceptions;
// use Illuminate\Foundation\Configuration\Middleware;
// use Illuminate\Auth\AuthenticationException;

// return Application::configure(basePath: dirname(__DIR__))
//     ->withRouting(
//         web: __DIR__.'/../routes/web.php',
//         commands: __DIR__.'/../routes/console.php',
//         health: '/up',
//     )
//     ->withMiddleware(function (Middleware $middleware): void {
//         //
//          $middleware->appendToGroup('web', [
//         \App\Http\Middleware\SetLocale::class,
//     ]);

//     })
//      ->withExceptions(function ($exceptions) {
//         $exceptions->render(function (AuthenticationException $e, $request) {
//             return redirect()
//                 ->route('login')
//                 ->with('error', 'you must login first.');
//         });
//     })
//     ->withExceptions(function (Exceptions $exceptions): void {
        
//     })->create();
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Auth\AuthenticationException;
use Spatie\Permission\Middleware\RoleMiddleware;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Spatie\Permission\Middleware\RoleOrPermissionMiddleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {

        // Web middleware group
        $middleware->appendToGroup('web', [
            \App\Http\Middleware\SetLocale::class,
        ]);

        // Alias middleware (WAJIB Laravel 11/12)
        $middleware->alias([
            'role' => RoleMiddleware::class,
            'permission' => PermissionMiddleware::class,
            'role_or_permission' => RoleOrPermissionMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {

        $exceptions->render(function (AuthenticationException $e, $request) {
            return redirect()
                ->route('login')
                ->with('error', 'you must login first.');
        });

    })
    ->create();