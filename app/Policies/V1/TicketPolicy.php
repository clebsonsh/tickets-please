<?php

namespace App\Policies\V1;

use App\Models\Ticket;
use App\Models\User;

class TicketPolicy
{
    public function update(User $user, Ticket $ticket)
    {
        return $user->id === $ticket->user_id;
    }
}
