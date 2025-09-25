<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthenticationRequest;
use App\Http\Resources\ApiResponseResource;
use App\Http\Services\AuthenticationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticationController extends Controller
{
    private $authService;
    public function __construct(AuthenticationService $authService)
    {
        $this->authService = $authService;
    }

    public function login(AuthenticationRequest $request)
    {
        $response = $this->authService->login($request->only('email', 'password'));
        return (new ApiResponseResource($response))
            ->response()
            ->setStatusCode($response['status']);
    }

    public function user(Request $request)
    {
        return new ApiResponseResource([
            'user' => $request->user()
        ]);
    }

    public function logout()
    {
        Auth::guard('web')->logout();
        return new ApiResponseResource([
            'message' => 'Logged out successfully',
        ]);
    }
}