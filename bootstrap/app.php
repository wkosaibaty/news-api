<?php

use App\Http\Middleware\SetUserIfExists;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->append(SetUserIfExists::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (Exception $e, Request $request) {
            $code = 500;
            $message = "Server error.";

            if ($e instanceof BadRequestHttpException) {
                $code = 400;
                $message = $e->getMessage();
            }

            if ($e instanceof AuthenticationException) {
                $code = 401;
                $message = $e->getMessage();
            }

            if ($e instanceof NotFoundHttpException) {
                $code = 404;
                $message = "Not found";
            }

            return response()->json([
                'success' => false,
                'message' => $message,
            ], $code);
        });
    })->create();
