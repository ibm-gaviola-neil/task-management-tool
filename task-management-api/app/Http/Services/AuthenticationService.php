<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\Auth;

class AuthenticationService 
{
    public function login(array $payload): array
    {
        $data = array();

        if (!Auth::attempt($payload)) {
            return [
                'message' => 'Invalid credentials',
                'status'  => 401
            ];
        }

        $data = [
            'status' => 200,
            'message' => 'Login successfully'
        ];

        return $data;
    }
}