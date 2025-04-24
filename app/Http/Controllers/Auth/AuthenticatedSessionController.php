<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Resources\AuthUserResource;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): JsonResponse
    {
        $request->authenticate();
        $user = Auth::guard('web')->user();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'token' => $token,
            'token_type' => 'Bearer',
            'user' => new AuthUserResource($user),
            'login_channel' => $this->determineLoginChannel($request)
        ]);
    }


    /**
     * Determine the login channel based on request.
     */
    protected function determineLoginChannel(Request $request): string
    {
        $userAgent = strtolower($request->userAgent() ?? '');

        if (str_contains($userAgent, 'postman')) {
            return 'postman';
        }

        if ($request->wantsJson() || $request->is('api/*')) {
            return 'api';
        }

        $mobilePatterns = ['android', 'ios', 'okhttp', 'alamofire'];
        foreach ($mobilePatterns as $pattern) {
            if (str_contains($userAgent, $pattern)) {
                return 'mobile';
            }
        }

        return 'web';
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): Response
    {
        $request->user()->tokens()->delete();
        Auth::guard('web')->logout();

        return response(new Response([
            'success' => true,
            'message' => 'Logged out successfully'
        ]));
    }
}
