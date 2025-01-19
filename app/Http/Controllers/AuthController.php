<?php

namespace App\Http\Controllers;

use Exception;
use \Illuminate\Support\Facades\Hash;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\SignupRequest;
use App\Models\User;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class AuthController extends Controller
{
    public function login(LoginRequest $request) {
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw new BadRequestHttpException('Invalid email or password.');
        }

        return $this->getTokenResponse($user);
    }

    public function signup(SignupRequest $request) {
        $user = User::create([
            'name' => $request->name,
            'email'=> $request->email,
            'password'=> Hash::make($request->password),
        ]);

        return $this->getTokenResponse($user);
    }

    private function getTokenResponse(User $user) {
        $token = $user->createToken('access_token')->plainTextToken;
        return $this->sendResponse(['access_token' => $token]);
    }
}
