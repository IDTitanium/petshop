<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Traits\SendApiResponse;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;
use Symfony\Component\HttpFoundation\Response;

class AuthorizeAdmin
{
    use SendApiResponse;
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user()->is_admin) {
            return $this->sendApiResponse(false, HttpResponse::HTTP_UNAUTHORIZED, __('messages.unauthorized'));
        }

        return $next($request);
    }
}
