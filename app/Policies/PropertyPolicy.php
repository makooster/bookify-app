<?php

namespace App\Policies;

use App\Models\Property;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PropertyPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return true;
    }

    public function view(?User $user, Property $property)
    {
        if ($property->is_approved) {
            return true;
        }

        if (!$user) {
            return false;
        }

        return $user->id === $property->user_id || $user->isAdmin();
    }

    public function create(User $user)
    {
        return $user->isOwner() || $user->isAdmin();
    }

    public function update(User $user, Property $property)
    {
        return $user->id === $property->user_id || $user->isAdmin();
    }

    public function delete(User $user, Property $property)
    {
        return $user->id === $property->user_id || $user->isAdmin();
    }

    public function approve(User $user, Property $property)
    {
        return $user->isAdmin();
    }

    public function book(User $user, Property $property)
    {
        return $property->is_approved &&
            $property->is_available &&
            $property->user_id !== $user->id;
    }
}
