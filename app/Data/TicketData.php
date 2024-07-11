<?php

namespace App\Data;

class TicketData
{
    public function __construct(
        public string $title,
        public string $description,
        public string $status,
        public int $user_id,
    ) {
    }
}
