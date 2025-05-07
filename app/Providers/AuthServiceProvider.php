<?php

namespace App\Providers;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Property;
use App\Models\Booking;
use App\Models\Review;
use App\Policies\PropertyPolicy;
use App\Policies\BookingPolicy;
use App\Policies\ReviewPolicy;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Property::class => PropertyPolicy::class,
        Booking::class => BookingPolicy::class,
        Review::class => ReviewPolicy::class,
    ];

    public function boot()
    {
        $this->registerPolicies();
    }
}
