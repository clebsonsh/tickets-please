<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\TestCase;
use Illuminate\Testing\TestResponse;

abstract class BaseTest extends TestCase
{
    protected User $user;

    /**
     * @var array <string, string>
     */
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

        return $this->postJson(route('login'), [
            'email' => $userData['email'] ?? '',
            'password' => $userData['password'] ?? '',
        ]);
    }
}
