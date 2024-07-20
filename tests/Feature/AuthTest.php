<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\BaseTest;

class AuthTest extends BaseTest
{
    use RefreshDatabase;

    public function test_login_works(): void
    {
        /** @var User $user */
        $user = User::factory()->create();

        $response = $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response
            ->assertJsonStructure([
                'message',
                'data' => ['token'],
                'status',
            ])->assertJson([
                'message' => 'Authenticated',
                'status' => Response::HTTP_OK,
            ])->assertStatus(Response::HTTP_OK);
    }

    public function test_login_fails_without_email(): void
    {
        $response = $this->postJson(route('login'), [
            'password' => 'password',
        ]);

        $response
            ->assertJsonStructure([
                'message',
                'errors' => ['email'],
            ])
            ->assertJsonValidationErrors(
                ['email' => 'The email field is required.'],
            )
            ->assertStatus(422);
    }

    public function test_login_fails_without_password(): void
    {
        $response = $this->postJson(route('login'), [
            'email' => 'test@mail.com',
        ]);

        $response
            ->assertJsonStructure([
                'message',
                'errors' => ['password'],
            ])
            ->assertJsonValidationErrors(
                ['password' => 'The password field is required.'],
            )
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function test_login_fails_with_wrong_credentials(): void
    {
        /** @var User $user */
        $user = User::factory()->create();

        $response = $this->postJson(route('login'), [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $response
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
            ->assertJson([

                'message' => 'Invalid credentials',
                'status' => Response::HTTP_UNAUTHORIZED,
            ])
            ->assertstatus(Response::HTTP_UNAUTHORIZED);
    }

    public function test_users_can_logout(): void
    {
        $this->markTestSkipped('work in this test later');
        /* $user = User::factory()->create(); */

        /* $response = $this->actingAs($user)->delete(route('logout')); */

        /* $this->assertGuest(); */
        /* $response->assertNoContent(); */
    }
}