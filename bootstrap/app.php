<?php

use App\Exceptions\AppException;
use App\Responses\ErrorResponse;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php', // api의 기본 경로는 /api/...로 시작한다.
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (AppException $e, Request $request) {
            $message = $e->getMessage();
            $code = $e->getCode();

            $body = new ErrorResponse($code, $message);

            return response()->json($body, $code);
        });
    })->create();
