<?php

namespace App\Data;

class LoginData
{
    public function __construct(
        public string $email,
        public string $password
    ) {}
}
