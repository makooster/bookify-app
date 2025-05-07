<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Property;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class BookingSeeder extends Seeder
{
    public function run()
    {
        $users = User::where('role', 'user')->get();
        $properties = Property::where('is_approved', true)->get();
        $statuses = ['pending', 'confirmed', 'cancelled', 'completed'];

        foreach ($users as $user) {
            // Create random bookings for each user
            $randomBookingsCount = rand(1, 4);

            for ($i = 0; $i < $randomBookingsCount; $i++) {
                $property = $properties->random();

                // Skip if property belongs to the user
                if ($property->user_id === $user->id) {
                    continue;
                }

                // Generate random dates
                $checkIn = Carbon::now()->subDays(rand(30, 60))->addDays(rand(0, 90));
                $checkOut = (clone $checkIn)->addDays(rand(2, 10));
                $status = $statuses[array_rand($statuses)];

                // Adjust status based on dates
                if ($checkIn->isPast() && $checkOut->isPast()) {
                    $status = 'completed';
                } elseif ($checkIn->isFuture()) {
                    $status = array_rand(array_flip(['pending', 'confirmed', 'cancelled']));
                }

                // Create booking
                Booking::create([
                    'user_id' => $user->id,
                    'property_id' => $property->id,
                    'check_in' => $checkIn->format('Y-m-d'),
                    'check_out' => $checkOut->format('Y-m-d'),
                    'guests' => rand(1, $property->capacity),
                    'total_price' => $property->price_per_night * $checkIn->diffInDays($checkOut),
                    'status' => $status,
                    'special_requests' => rand(0, 1) ? 'Please prepare the room early. We will arrive around noon.' : null,
                ]);
            }
        }
    }
}
