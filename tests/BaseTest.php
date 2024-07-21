<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\TestCase;
use Illuminate\Support\Arr;
use Illuminate\Testing\TestResponse;

abstract class BaseTest extends TestCase
{
    protected User $user;

    protected string $token;

    /** @var array <string, string> */
    protected array $validUser = [
        'email' => 'valid@email.com',
        'password' => 'password',
    ];

    /**
     * @param  array <string, string>  $userData
     */
    public function loginAs(array $userData): TestResponse
    {
        $this->user = User::factory($userData)->create();

        $response = $this->postJson(route('login'), [
            'email' => $userData['email'] ?? '',
            'password' => $userData['password'] ?? '',
        ]);

        /** @var string $token */
        $token = Arr::get($response, 'data.token') ?? '';

        $this->token = $token;

        return $response;
    }
}
