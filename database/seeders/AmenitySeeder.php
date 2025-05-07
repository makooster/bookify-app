<?php

namespace Database\Seeders;

use App\Models\Amenity;
use Illuminate\Database\Seeder;

class AmenitySeeder extends Seeder
{
    public function run()
    {
        $amenities = [
            ['name' => 'Wi-Fi', 'icon' => 'fa-wifi'],
            ['name' => 'Air Conditioning', 'icon' => 'fa-snowflake'],
            ['name' => 'Heating', 'icon' => 'fa-temperature-high'],
            ['name' => 'Kitchen', 'icon' => 'fa-utensils'],
            ['name' => 'TV', 'icon' => 'fa-tv'],
            ['name' => 'Free Parking', 'icon' => 'fa-parking'],
            ['name' => 'Swimming Pool', 'icon' => 'fa-swimming-pool'],
            ['name' => 'Gym', 'icon' => 'fa-dumbbell'],
            ['name' => 'Washer', 'icon' => 'fa-washing-machine'],
            ['name' => 'Hot Tub', 'icon' => 'fa-hot-tub'],
            ['name' => 'Workspace', 'icon' => 'fa-laptop'],
            ['name' => 'Smoke Alarm', 'icon' => 'fa-smoke-detector'],
            ['name' => 'Pets Allowed', 'icon' => 'fa-paw'],
            ['name' => 'Beachfront', 'icon' => 'fa-umbrella-beach'],
            ['name' => 'Ski-in/Ski-out', 'icon' => 'fa-skiing'],
        ];

        foreach ($amenities as $amenity) {
            Amenity::create($amenity);
        }
    }
}
