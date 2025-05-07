<?php

namespace Database\Factories;

use App\Models\Property;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PropertyFactory extends Factory
{
    protected $model = Property::class;

    public function definition()
    {
        $types = ['apartment', 'house', 'villa', 'room'];
        $cities = ['New York', 'Los Angeles', 'Miami', 'Chicago', 'San Francisco',
            'London', 'Paris', 'Berlin', 'Rome', 'Barcelona', 'Tokyo', 'Sydney'];
        $countries = ['USA', 'United Kingdom', 'France', 'Germany', 'Italy', 'Spain', 'Japan', 'Australia'];

        return [
            'user_id' => User::where('role', 'owner')->orWhere('role', 'admin')->inRandomOrder()->first()->id,
            'title' => $this->faker->words(rand(3, 6), true),
            'description' => $this->faker->paragraphs(rand(3, 5), true),
            'type' => $types[array_rand($types)],
            'price_per_night' => $this->faker->numberBetween(50, 500),
            'capacity' => $this->faker->numberBetween(1, 10),
            'bedrooms' => $this->faker->numberBetween(1, 5),
            'bathrooms' => $this->faker->numberBetween(1, 4),
            'address' => $this->faker->streetAddress,
            'city' => $cities[array_rand($cities)],
            'country' => $countries[array_rand($countries)],
            'is_available' => $this->faker->boolean(90), // 90% available
            'is_approved' => $this->faker->boolean(80), // 80% approved
        ];
    }

    public function approved()
    {
        return $this->state(function (array $attributes) {
            return [
                'is_approved' => true,
            ];
        });
    }

    public function unavailable()
    {
        return $this->state(function (array $attributes) {
            return [
                'is_available' => false,
            ];
        });
    }
}
