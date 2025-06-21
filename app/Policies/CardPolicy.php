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

    public function update(User $user, Card $card, array $data = [])
    {
        // Админы и менеджеры могут всё
        if ($user->hasRole(['admin', 'manager'])) {
            return true;
        }

        // Обычный пользователь может:
        // 1. Взять карточку (назначить себя)
        if (isset($data['assigned_to']) && $data['assigned_to'] === $user->id) {
            return $card->status === 'todo';
        }

        // 2. Переместить свою карточку в проверку
        if (isset($data['status']) && $data['status'] === 'review') {
            return $card->assigned_to === $user->id && $card->status === 'in_progress';
        }

        return false;
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
