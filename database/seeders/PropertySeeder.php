<?php

namespace Database\Seeders;

use App\Models\Property;
use App\Models\User;
use App\Models\Amenity;
use App\Models\PropertyImage;
use Illuminate\Database\Seeder;

class PropertySeeder extends Seeder
{
    public function run()
    {
        $owners = User::where('role', 'owner')->orWhere('role', 'admin')->get();
        $amenities = Amenity::all();

        // Create properties for each owner
        foreach ($owners as $owner) {
            // Create some properties for each owner
            $properties = Property::factory()->count(rand(2, 5))->create([
                'user_id' => $owner->id,
                'is_approved' => true,
            ]);

            foreach ($properties as $property) {
                // Add random amenities to each property
                $randomAmenities = $amenities->random(rand(3, 8))->pluck('id')->toArray();
                $property->amenities()->attach($randomAmenities);

                // Add images to each property
                for ($i = 1; $i <= rand(3, 6); $i++) {
                    PropertyImage::create([
                        'property_id' => $property->id,
                        'image_path' => 'property_images/placeholder_' . rand(1, 10) . '.jpg',
                        'is_main' => $i === 1, // First image is the main image
                    ]);
                }
            }

            // Create a few unapproved properties
            if ($owner->id % 2 === 0) {
                $unapproved = Property::factory()->count(rand(1, 2))->create([
                    'user_id' => $owner->id,
                    'is_approved' => false,
                ]);

                foreach ($unapproved as $property) {
                    // Add random amenities
                    $randomAmenities = $amenities->random(rand(3, 8))->pluck('id')->toArray();
                    $property->amenities()->attach($randomAmenities);

                    // Add images
                    for ($i = 1; $i <= rand(2, 4); $i++) {
                        PropertyImage::create([
                            'property_id' => $property->id,
                            'image_path' => 'property_images/placeholder_' . rand(1, 10) . '.jpg',
                            'is_main' => $i === 1, // First image is the main image
                        ]);
                    }
                }
            }
        }
    }
}
