<?php

namespace App\Http\Controllers\Api;

use App\Data\LoginData;
use App\Http\Requests\Api\LoginUserRequest;
use App\Models\User;
use App\Permissions\V1\Abilities;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;

class AuthController extends ApiController
{
    public function login(LoginUserRequest $request): JsonResponse
    {
        $validated = new LoginData($request->string('email'), $request->string('password'));

        if (! Auth::validate(['email' => $validated->email, 'password' => $validated->password])) {
            return $this->error('Invalid credentials', Response::HTTP_UNAUTHORIZED);
        }

        /** @var User $user */
        $user = User::firstWhere('email', $validated->email);

        return $this->ok(
            'Authenticated',
            [
                'token' => $user->createToken(
                    'API token for '.$user->name,
                    Abilities::getAbilities($user),
                    now()->addMonth(),
                )->plainTextToken,
            ],
        );
    }

    public function register(): JsonResponse
    {
        return $this->ok('register');
    }

    public function logout(): JsonResponse
    {
        /** @var User $user */
        $user = auth('sanctum')->user();

        /** @var PersonalAccessToken $currentToken */
        $currentToken = $user->currentAccessToken();
        $currentToken->delete();

        return $this->ok('');
    }
}
