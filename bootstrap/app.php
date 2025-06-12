<?php

use Fruitcake\Cors\CorsService;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Middleware\HandleCors;
use Illuminate\Support\Facades\Request;
use Mockery\Exception\InvalidOrderException;
use function PHPUnit\Framework\isInstanceOf;
use Illuminate\Support\Facades\Facade;


return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {

        $middleware->api(prepend: [
            Illuminate\Http\Middleware\HandleCors::class,
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
        ]);

        $middleware->alias([ // thiss
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
            'cors' => Illuminate\Http\Middleware\HandleCors::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (InvalidOrderException $e, Request $request){
            if ($request->is('api/*')){
                if ($e instanceof QueryException){
                    if (str_contains($e->getMessage(), 'users_email_unique')){
                        return response()->json([
                            'message' => _('validation.email_registered'),
                        ], 400);
                    }

                    return response()->json([
                        'message' => 'Terjadi kesalahan pada database.',
                        'error' => $e->getMessage()
                    ], 500);
                }
            }
        });
    })->create();
