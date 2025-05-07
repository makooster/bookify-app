<?php

namespace App\Policies;

use App\Models\Booking;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BookingPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return true;
    }

    public function view(User $user, Booking $booking)
    {
        return $user->id === $booking->user_id ||
            $user->id === $booking->property->user_id ||
            $user->isAdmin();
    }

    public function create(User $user)
    {
        return true;
    }

    public function update(User $user, Booking $booking)
    {
        // Property owner can confirm or cancel booking
        return $user->id === $booking->property->user_id || $user->isAdmin();
    }

    public function delete(User $user, Booking $booking)
    {
        // Only admin can delete bookings
        return $user->isAdmin();
    }

    public function review(User $user, Booking $booking)
    {
        // Only completed bookings can be reviewed by the guest
        return $user->id === $booking->user_id &&
            $booking->status === 'completed' &&
            !$booking->review;
    }
}
