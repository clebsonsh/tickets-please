<?php

namespace App\Permissions\V1;

use App\Models\User;

final class Abilities
{
    public const StoreTicket = 'ticket:store';

    public const UpdateTicket = 'ticket:update';

    public const ReplaceTicket = 'ticket:replace';

    public const DeleteTicket = 'ticket:delete';

    public const UpdateOwnTicket = 'ticket:own:update';

    public const DeleteTOwnicket = 'ticket:own:delete';

    public const StoreUser = 'user:store';

    public const UpdateUser = 'user:update';

    public const ReplaceUser = 'user:replace';

    public const DeleteUser = 'user:delete';

    /**
     * @return array <int, string>
     */
    public static function getAbilities(User $user): array
    {
        if ($user->is_manager) {
            return [
                self::StoreTicket,
                self::UpdateTicket,
                self::ReplaceTicket,
                self::DeleteTicket,
                self::StoreUser,
                self::UpdateUser,
                self::ReplaceUser,
                self::DeleteUser,
            ];
        }

        return [
            self::StoreTicket,
            self::UpdateOwnTicket,
            self::DeleteTOwnicket,
        ];
    }
}
