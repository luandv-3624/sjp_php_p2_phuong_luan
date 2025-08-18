<?php

namespace App\Exceptions;

use App\Enums\HttpStatusCode;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use App\Helpers\ApiResponse;
use Throwable;
use Illuminate\Support\Facades\Log;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    protected function invalidJson($request, ValidationException $exception)
    {
        return ApiResponse::error($exception->getMessage(), $exception->errors(), $exception->status);
    }

    public function render($request, Throwable $exception)
    {
        if ($exception instanceof ValidationException) {
            return ApiResponse::error(
                'validation_failed',
                $exception->errors(),
                $exception->status
            );
        }

        if ($exception instanceof \Exception) {
            Log::error($exception->getMessage(), ['trace' => $exception->getTraceAsString()]);

            return ApiResponse::error(
                'Internal server error',
                [],
                HttpStatusCode::INTERNAL_SERVER_ERROR
            );
        }

        return parent::render($request, $exception);
    }

}
