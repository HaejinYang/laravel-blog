<?php

use App\Exceptions\AppException;
use App\Responses\ErrorResponse;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

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

        // 검증 실패 시 커스텀 응답 반환
        $exceptions->render(function (Throwable $e, Request $request) {
            $response = new ErrorResponse(Response::HTTP_BAD_REQUEST, $e->getMessage());
            if ($e instanceof ValidationException) {
                foreach ($e->errors() as $field => $message) {
                    $response->addValidation($field, $message[0]);
                }
            }

            return response()->json($response, Response::HTTP_BAD_REQUEST);
        });
    })->create();
