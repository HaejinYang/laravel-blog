<?php

use App\Exceptions\BaaseException;
use App\Responses\ErrorResponse;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
        $exceptions->render(function (BaaseException $e, Request $request) {
            $message = $e->getMessage();
            $code = $e->getCode();

            $body = new ErrorResponse($code, $message);

            return response()->json($body, $code);
        });

        // 검증 실패 시 커스텀 응답 반환
        $exceptions->render(function (Throwable $e, Request $request) {
            $response = null;
            $code = Response::HTTP_INTERNAL_SERVER_ERROR;

            if ($e instanceof ValidationException) {
                $code = Response::HTTP_BAD_REQUEST;
                $response = new ErrorResponse($code, $e->getMessage());
                foreach ($e->errors() as $field => $message) {
                    $response->addValidation($field, $message[0]);
                }
            }

            if ($e instanceof NotFoundHttpException) {
                $code = Response::HTTP_NOT_FOUND;
                $response = new ErrorResponse($code, $e->getMessage());
            }

            if ($response === null) {
                $response = new ErrorResponse(Response::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage());
            }

            return response()->json($response, $code);
        });
    })->create();
