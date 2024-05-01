<?php

namespace App\Http\Controllers\Api;

use App\Data\LoginData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LoginUserRequest;
use App\Models\User;
use App\Traits\ApiResponses;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AuthController extends Controller
{
    use ApiResponses;

    public function login(LoginUserRequest $request)
    {
        $validated = new LoginData(...$request->validated());

        if (! auth()->attempt(['email' => $validated->email, 'password' => $validated->password])) {
            return $this->error('Invalid credentials', Response::HTTP_UNAUTHORIZED);
        }

        $user = User::firstWhere('email', $validated->email);

        return $this->ok(
            'Authenticated',
            [
                'token' => $user->createToken(
                    'API token for '.$user->name,
                    ['*'],
                    now()->addMonth(),
                )->plainTextToken,
            ],
        );
    }

    public function register()
    {
        return $this->ok('register');
    }

    public function logout(Request $request)
    {

        $request->user()->currentAccessToken()->delete();

        return $this->ok('');
    }
}
