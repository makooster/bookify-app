<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Property extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'type',
        'price_per_night',
        'capacity',
        'bedrooms',
        'bathrooms',
        'address',
        'city',
        'country',
        'is_available',
        'is_approved',
    ];

    protected $casts = [
        'is_available' => 'boolean',
        'is_approved' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function amenities()
    {
        return $this->belongsToMany(Amenity::class);
    }

    public function images()
    {
        return $this->hasMany(PropertyImage::class);
    }

    public function mainImage()
    {
        return $this->hasOne(PropertyImage::class)->where('is_main', true);
    }

    public function getAverageRatingAttribute()
    {
        return $this->reviews()->avg('rating') ?? 0;
    }
}
