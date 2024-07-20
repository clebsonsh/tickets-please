<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Laravel\Sanctum\PersonalAccessToken;
use Tests\BaseTest;

class AuthTest extends BaseTest
{
    use RefreshDatabase;

    public function testUsersCanLogin(): void
    {
        $response = $this->loginAs($this->validUser);

        $response
            ->assertJsonMissingValidationErrors(['email', 'password'])
            ->assertJsonStructure([
                'message',
                'data' => ['token'],
                'status',
            ])->assertJson([
                'message' => 'Authenticated',
                'status' => Response::HTTP_OK,
            ])->assertStatus(Response::HTTP_OK);
    }

    public function testLoginFailsWithoutEmail(): void
    {
        $response = $this->loginAs([
            'password' => 'password',
        ]);

        $response
            ->assertJsonMissingValidationErrors(['password'])
            ->assertJsonStructure([
                'message',
                'errors' => ['email'],
            ])
            ->assertJsonValidationErrors(
                ['email' => 'The email field is required.'],
            )
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testLoginFailsWithoutPassword(): void
    {
        $response = $this->loginAs([
            'email' => 'test@mail.com',
        ]);

        $response
            ->assertJsonMissingValidationErrors(['email'])
            ->assertJsonStructure([
                'message',
                'errors' => ['password'],
            ])
            ->assertJsonValidationErrors(
                ['password' => 'The password field is required.'],
            )
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testLoginFailsWithWrongCredentials(): void
    {
        $this->loginAs([
            'email' => 'test@mail.com',
            'password' => 'password',
        ]);

        $response = $this->postJson(route('login'), [
            'email' => $this->user->email,
            'password' => 'wrong-password',
        ]);

        $response
            ->assertJsonMissingValidationErrors(['email', 'password'])
            ->assertJson([

                'message' => 'Invalid credentials',
                'status' => Response::HTTP_UNAUTHORIZED,
            ])
            ->assertstatus(Response::HTTP_UNAUTHORIZED);

        $response = $this->postJson(route('login'), [
            'email' => 'wrong@mail.com',
            'password' => 'password',
        ]);

        $response
            ->assertJsonMissingValidationErrors(['email', 'password'])
            ->assertJson([
                'message' => 'Invalid credentials',
                'status' => Response::HTTP_UNAUTHORIZED,
            ])
            ->assertstatus(Response::HTTP_UNAUTHORIZED);

        $response = $this->postJson(route('login'), [
            'email' => 'wrong@mail.com',
            'password' => 'wrong-password',
        ]);

        $response
            ->assertJsonMissingValidationErrors(['email', 'password'])
            ->assertJson([
                'message' => 'Invalid credentials',
                'status' => Response::HTTP_UNAUTHORIZED,
            ])
            ->assertstatus(Response::HTTP_UNAUTHORIZED);
    }

    public function testUsersCanLogout(): void
    {
        $loginResponse = $this->loginAs($this->validUser);

        /** @var string $token */
        $token = Arr::get($loginResponse, 'data.token');

        $response = $this->withToken($token)->delete(route('logout'));

        $token = PersonalAccessToken::firstWhere('token', $token);

        $this->assertNull($token);

        $response
            ->assertJson([
                'message' => '',
                'data' => [],
                'status' => Response::HTTP_OK,
            ])
            ->assertstatus(Response::HTTP_OK);
    }
}
