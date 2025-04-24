<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Spatie\Permission\Exceptions\UnauthorizedException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class TransformSpatieExceptions
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if ($response instanceof StreamedResponse) {
            return $response;
        }

        if (property_exists($response, 'exception') &&
            $response->exception instanceof UnauthorizedException) {
            return response()->json([
                'success' => false,
                'message' => 'Forbidden',
                'errors' => [
                    'code' => 403,
                    'message' => $response->exception->getMessage()
                ]
            ], 403);
        }

        return $response;
    }
}
