<?php

namespace App\Policies\V1;

use App\Models\Ticket;
use App\Models\User;
use App\Permissions\V1\Abilities;

class TicketPolicy
{
    public function store(User $user, Ticket $ticket): bool
    {
        return $user->tokenCan(Abilities::StoreTicket);
    }

    public function update(User $user, Ticket $ticket): bool
    {
        return match (true) {
            $user->tokenCan(Abilities::UpdateTicket) => true,
            $user->tokenCan(Abilities::UpdateOwnTicket) => $user->id === $ticket->user_id,
            default => false,
        };
    }

    public function replace(User $user, Ticket $ticket): bool
    {
        return $user->tokenCan(Abilities::ReplaceTicket);
    }

    public function delete(User $user, Ticket $ticket): bool
    {
        return match (true) {
            $user->tokenCan(Abilities::DeleteTicket) => true,
            $user->tokenCan(Abilities::DeleteTOwnicket) => $user->id === $ticket->user_id,
            default => false,
        };
    }
}
