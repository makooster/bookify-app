<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'check_in' => $this->check_in->format('Y-m-d'),
            'check_out' => $this->check_out->format('Y-m-d'),
            'guests' => $this->guests,
            'total_price' => $this->total_price,
            'status' => $this->status,
            'special_requests' => $this->special_requests,
            'duration' => $this->duration,
            'property' => [
                'id' => $this->property->id,
                'title' => $this->property->title,
                'main_image' => $this->property->mainImage ?
                    asset('storage/' . $this->property->mainImage->image_path) : null,
            ],
            'user' => $this->whenLoaded('user', function() {
                return [
                    'id' => $this->user->id,
                    'name' => $this->user->name,
                    'email' => $this->user->email,
                ];
            }),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
