<?php
// app/Http/Resources/PropertyResource.php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PropertyResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'type' => $this->type,
            'price_per_night' => $this->price_per_night,
            'capacity' => $this->capacity,
            'bedrooms' => $this->bedrooms,
            'bathrooms' => $this->bathrooms,
            'address' => $this->address,
            'city' => $this->city,
            'country' => $this->country,
            'is_available' => $this->is_available,
            'is_approved' => $this->is_approved,
            'average_rating' => $this->average_rating,
            'owner' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
            ],
            'images' => $this->whenLoaded('images', function() {
                return $this->images->map(function($image) {
                    return [
                        'id' => $image->id,
                        'image_path' => asset('storage/' . $image->image_path),
                        'is_main' => $image->is_main,
                    ];
                });
            }),
            'amenities' => $this->whenLoaded('amenities', function() {
                return $this->amenities->map(function($amenity) {
                    return [
                        'id' => $amenity->id,
                        'name' => $amenity->name,
                        'icon' => $amenity->icon,
                    ];
                });
            }),
            'reviews' => $this->whenLoaded('reviews', function() {
                return $this->reviews->map(function($review) {
                    return [
                        'id' => $review->id,
                        'rating' => $review->rating,
                        'comment' => $review->comment,
                        'created_at' => $review->created_at->format('Y-m-d H:i:s'),
                        'user' => [
                            'id' => $review->user->id,
                            'name' => $review->user->name,
                        ],
                    ];
                });
            }),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
