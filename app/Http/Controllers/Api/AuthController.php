<?php

namespace App\Http\Controllers\Api;

use App\Data\LoginData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LoginUserRequest;
use App\Models\User;
use App\Traits\ApiResponses;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Laravel\Sanctum\PersonalAccessToken;

class AuthController extends Controller
{
    use ApiResponses;

    public function login(LoginUserRequest $request): JsonResponse
    {
        $validated = new LoginData($request->string('email'), $request->string('password'));

        if (! auth()->attempt(['email' => $validated->email, 'password' => $validated->password])) {
            return $this->error('Invalid credentials', Response::HTTP_UNAUTHORIZED);
        }

        /** @var User $user */
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

    public function register(): JsonResponse
    {
        return $this->ok('register');
    }

    public function logout(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = auth()->user();

        /** @var PersonalAccessToken $currentToken */
        $currentToken = $user->currentAccessToken();
        $currentToken->delete();

        return $this->ok('');
    }
}
