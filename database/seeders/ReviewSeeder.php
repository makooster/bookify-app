<?php

namespace Database\Seeders;

use App\Models\Review;
use App\Models\Booking;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    public function run()
    {
        // Get all completed bookings
        $completedBookings = Booking::where('status', 'completed')->get();

        foreach ($completedBookings as $booking) {
            // 70% chance of having a review
            if (rand(1, 10) <= 7) {
                Review::create([
                    'user_id' => $booking->user_id,
                    'property_id' => $booking->property_id,
                    'booking_id' => $booking->id,
                    'rating' => rand(3, 5), // More positive reviews
                    'comment' => $this->getRandomComment($booking->property->title),
                ]);
            }
        }
    }

    private function getRandomComment($propertyTitle)
    {
        $comments = [
            "Excellent stay at $propertyTitle! The place was clean, comfortable, and exactly as described. Would definitely book again.",
            "Had a wonderful experience at $propertyTitle. Great location and amenities. Hosts were very responsive.",
            "The $propertyTitle exceeded our expectations. Beautiful property and excellent service.",
            "Very nice place. $propertyTitle was perfect for our weekend getaway.",
            "Great value for the price at $propertyTitle. The property had everything we needed.",
            "Lovely stay at $propertyTitle. The host was very accommodating and the property was well-maintained.",
            "Clean, comfortable, and convenient. Would recommend $propertyTitle to anyone visiting the area.",
            "$propertyTitle was a great choice for our trip. We particularly enjoyed the amenities and location.",
            "Good experience overall at $propertyTitle. A few minor issues but nothing significant.",
            "The $propertyTitle was perfect for our needs. Would definitely consider staying here again on future trips."
        ];

        return $comments[array_rand($comments)];
    }
}
