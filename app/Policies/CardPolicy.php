<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Card;

class CardPolicy
{
    /**
     * Perform pre-authorization checks.
     */
    public function before(User $user, string $ability): ?bool
    {
        if ($user->hasRole('admin') || $user->hasRole('manager')) {
            return true; // Админы и менеджеры могут всё
        }
        return null;
    }

    public function viewAny(User $user): bool
    {
        return true; // Все аутентифицированные могут видеть список
    }

    public function view(User $user, Card $card): bool
    {
        return true; // Все могут просматривать карточки
    }

    public function create(User $user): bool
    {
        // Разрешаем только менеджерам и админам создавать карточки
        return $user->hasRole(['admin', 'manager']);
    }

    public function update(User $user, Card $card): bool
    {
        // Обычные пользователи могут обновлять только свои карточки
        return $user->id === $card->assigned_to || $user->id === $card->created_by;
    }

    public function delete(User $user, Card $card): bool
    {
        return $user->hasRole(['admin', 'manager']) || $user->id === $card->created_by;
    }

    public function restore(User $user, Card $card): bool
    {
        return false;
    }

    public function forceDelete(User $user, Card $card): bool
    {
        return false;
    }
}
