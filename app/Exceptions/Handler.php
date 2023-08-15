<?php

declare(strict_types=1);

namespace App\Exceptions;

use App\Traits\SendApiResponse;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Throwable;

class Handler extends ExceptionHandler
{
    use SendApiResponse;
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
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
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            Log::error("An error has occured", [$e->getTrace()]);
            return $this->sendApiResponse(false, Response::HTTP_INTERNAL_SERVER_ERROR, "Error occured");
        });
    }
}
