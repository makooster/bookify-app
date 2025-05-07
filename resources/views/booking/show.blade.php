@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex justify-between items-start">
                        <div>
                            <h1 class="text-2xl font-bold">Booking #{{ $booking->id }}</h1>
                            <p class="text-gray-600">Booked on {{ $booking->created_at->format('M d, Y') }}</p>
                        </div>
                        <span class="px-3 py-1 rounded-full text-sm font-semibold
                          {{ $booking->status === 'confirmed' ? 'bg-green-100 text-green-800' :
                             ($booking->status === 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                        {{ ucfirst($booking->status) }}
                    </span>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 p-6">
                    <!-- Property Information -->
                    <div>
                        <h2 class="text-xl font-semibold mb-4">Property Information</h2>
                        <div class="space-y-4">
                            @if($booking->property->mainImage)
                                <img src="{{ asset('storage/' . $booking->property->mainImage->image_path) }}"
                                     alt="{{ $booking->property->title }}"
                                     class="w-full h-48 object-cover rounded-lg">
                            @endif
                            <div>
                                <h3 class="text-lg font-medium">{{ $booking->property->title }}</h3>
                                <p class="text-gray-600">{{ $booking->property->address }}, {{ $booking->property->city }}, {{ $booking->property->country }}</p>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm text-gray-500">Type</p>
                                    <p class="capitalize">{{ $booking->property->type }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Bedrooms</p>
                                    <p>{{ $booking->property->bedrooms }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Bathrooms</p>
                                    <p>{{ $booking->property->bathrooms }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Max Guests</p>
                                    <p>{{ $booking->property->capacity }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Booking Details -->
                    <div>
                        <h2 class="text-xl font-semibold mb-4">Booking Details</h2>
                        <div class="space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm text-gray-500">Check-in</p>
                                    <p>{{ $booking->check_in->format('M d, Y') }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Check-out</p>
                                    <p>{{ $booking->check_out->format('M d, Y') }}</p>
                                </div>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Guests</p>
                                <p>{{ $booking->guests }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Special Requests</p>
                                <p>{{ $booking->special_requests ?? 'None' }}</p>
                            </div>
                        </div>

                        <!-- Price Summary -->
                        <div class="mt-6 bg-gray-50 p-4 rounded-lg">
                            <h3 class="text-lg font-medium mb-2">Price Summary</h3>
                            <div class="space-y-2">
                                <div class="flex justify-between">
                                    <span>${{ number_format($booking->property->price_per_night, 2) }} × {{ $booking->duration }} nights</span>
                                    <span>${{ number_format($booking->property->price_per_night * $booking->duration, 2) }}</span>
                                </div>
                                <div class="border-t my-2"></div>
                                <div class="flex justify-between font-bold">
                                    <span>Total</span>
                                    <span>${{ number_format($booking->total_price, 2) }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="mt-6 flex space-x-4">
                            @if($booking->status === 'pending' && auth()->user()->id === $booking->property->user_id)
                                <form action="{{ route('bookings.update', $booking) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status" value="confirmed">
                                    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                                        Confirm Booking
                                    </button>
                                </form>
                                <form action="{{ route('bookings.update', $booking) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status" value="cancelled">
                                    <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">
                                        Cancel Booking
                                    </button>
                                </form>
                            @endif

                            @if($booking->status === 'pending' && auth()->user()->id === $booking->user_id)
                                <form action="{{ route('bookings.update', $booking) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status" value="cancelled">
                                    <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">
                                        Cancel Booking
                                    </button>
                                </form>
                            @endif
                        </div>

                        <!-- Review Button -->
                        @auth
                            @if($booking->status === 'confirmed' &&
                                auth()->user()->id === $booking->user_id &&
                                !$booking->review)
                                <div class="mt-4">
                                    <a href="{{ route('reviews.create', ['booking' => $booking]) }}"
                                       class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 inline-block">
                                        Write a Review
                                    </a>
                                </div>
                            @endif
                        @endauth
                    </div>
                </div>

                <!-- Host/Guest Information -->
                <div class="p-6 border-t border-gray-200 grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <h3 class="text-lg font-semibold mb-2">Host Information</h3>
                        <div class="flex items-center">
                            <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center mr-3">
                                {{ substr($booking->property->user->name, 0, 1) }}
                            </div>
                            <div>
                                <p class="font-medium">{{ $booking->property->user->name }}</p>
                                <p class="text-sm text-gray-600">Host since {{ $booking->property->user->created_at->format('M Y') }}</p>
                            </div>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold mb-2">Guest Information</h3>
                        <div class="flex items-center">
                            <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center mr-3">
                                {{ substr($booking->user->name, 0, 1) }}
                            </div>
                            <div>
                                <p class="font-medium">{{ $booking->user->name }}</p>
                                <p class="text-sm text-gray-600">Member since {{ $booking->user->created_at->format('M Y') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
