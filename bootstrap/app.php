<?php

declare(strict_types=1);

use App\Shared\Domain\Exceptions\DomainException;
use App\Shared\Domain\Exceptions\ResourceNotFoundException;
use App\Shared\Http\Responses\ApiResponse;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (
            ResourceNotFoundException $exception,
            Request $request,
        ) {
            if (! $request->expectsJson()) {
                return null;
            }

            return ApiResponse::error(
                code: $exception->errorCode(),
                message: $exception->getMessage(),
                status: 404,
                details: $exception->context(),
            );
        });

        $exceptions->render(function (
            DomainException $exception,
            Request $request,
        ) {
            if (! $request->expectsJson()) {
                return null;
            }

            return ApiResponse::error(
                code: $exception->errorCode(),
                message: $exception->getMessage(),
                status: 422,
                details: $exception->context(),
            );
        });

        $exceptions->render(function (
            ValidationException $exception,
            Request $request,
        ) {
            if (! $request->expectsJson()) {
                return null;
            }

            return ApiResponse::error(
                code: 'validation_error',
                message: 'Переданные данные не прошли проверку.',
                status: 422,
                details: [
                    'fields' => $exception->errors(),
                ],
            );
        });

        $exceptions->render(function (
            AuthenticationException $exception,
            Request $request,
        ) {
            if (! $request->expectsJson()) {
                return null;
            }

            return ApiResponse::error(
                code: 'unauthenticated',
                message: 'Требуется авторизация.',
                status: 401,
            );
        });

        $exceptions->render(function (
            ModelNotFoundException $exception,
            Request $request,
        ) {
            if (! $request->expectsJson()) {
                return null;
            }

            return ApiResponse::error(
                code: 'resource_not_found',
                message: 'Запрашиваемый ресурс не найден.',
                status: 404,
            );
        });

        $exceptions->render(function (
            NotFoundHttpException $exception,
            Request $request,
        ) {
            if (! $request->expectsJson()) {
                return null;
            }

            return ApiResponse::error(
                code: 'route_not_found',
                message: 'Запрашиваемый маршрут не найден.',
                status: 404,
            );
        });

        $exceptions->render(function (
            Throwable $exception,
            Request $request,
        ) {
            if (! $request->expectsJson()) {
                return null;
            }

            return ApiResponse::error(
                code: 'internal_error',
                message: app()->isProduction()
                    ? 'Внутренняя ошибка сервера.'
                    : $exception->getMessage(),
                status: 500,
            );
        });
    })
    ->create();
