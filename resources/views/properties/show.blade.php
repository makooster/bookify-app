@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <!-- Property Images Carousel -->
            <div class="relative">
                <div class="flex overflow-x-auto space-x-2 p-4">
                    @foreach($property->images as $image)
                        <img src="{{ asset('storage/' . $image->image_path) }}"
                             alt="{{ $property->title }}"
                             class="h-64 w-auto rounded-lg {{ $image->is_main ? 'ring-2 ring-blue-500' : '' }}">
                    @endforeach
                </div>
            </div>

            <!-- Property Details -->
            <div class="p-6">
                <div class="flex justify-between items-start">
                    <div>
                        <h1 class="text-2xl font-bold">{{ $property->title }}</h1>
                        <p class="text-gray-600">{{ $property->address }}, {{ $property->city }}, {{ $property->country }}</p>
                    </div>
                    <div class="text-right">
                        <span class="text-2xl font-bold">${{ $property->price_per_night }}</span>
                        <span class="block text-gray-500">per night</span>
                    </div>
                </div>

                <!-- Property Stats -->
                <div class="flex items-center space-x-4 mt-4">
                    <div class="flex items-center">
                        <span class="text-yellow-500 text-xl">★</span>
                        <span class="ml-1">{{ number_format($property->average_rating, 1) }}</span>
                        <span class="text-gray-500 ml-1">({{ $property->reviews->count() }} reviews)</span>
                    </div>
                    <span>·</span>
                    <div class="flex items-center space-x-2">
                        <span>{{ $property->bedrooms }} bedrooms</span>
                        <span>·</span>
                        <span>{{ $property->bathrooms }} bathrooms</span>
                        <span>·</span>
                        <span>{{ $property->capacity }} guests</span>
                    </div>
                </div>

                <!-- Property Type -->
                <div class="mt-4">
                    <span class="bg-gray-200 px-3 py-1 rounded-full text-sm capitalize">{{ $property->type }}</span>
                </div>

                <!-- Amenities -->
                <div class="mt-6">
                    <h3 class="text-lg font-semibold">Amenities</h3>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-2 mt-2">
                        @foreach($property->amenities as $amenity)
                            <div class="flex items-center">
                                <span class="mr-2">✓</span>
                                <span>{{ $amenity->name }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Description -->
                <div class="mt-6">
                    <h3 class="text-lg font-semibold">Description</h3>
                    <p class="mt-2 text-gray-700">{{ $property->description }}</p>
                </div>

                <!-- Host Info -->
                <div class="mt-6 border-t pt-4">
                    <h3 class="text-lg font-semibold">Hosted by {{ $property->user->name }}</h3>
                    <!-- Add more host info if needed -->
                </div>

                <!-- Action Buttons -->
                <div class="mt-6 flex space-x-4">
                    @auth
                        @if(auth()->user()->id === $property->user_id)
                            <a href="{{ route('properties.edit', $property) }}"
                               class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                                Edit Property
                            </a>
                        @else
                            <a href="{{ route('bookings.create', $property) }}"
                               class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                                Book Now
                            </a>
                        @endif
                    @else
                        <a href="{{ route('login') }}"
                           class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                            Login to Book
                        </a>
                    @endauth
                </div>
            </div>
        </div>

        <!-- Reviews Section -->
        <div class="mt-8 bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold mb-4">Reviews</h2>

            @if($property->reviews->count() > 0)
                <div class="space-y-6">
                    @foreach($property->reviews as $review)
                        <div class="border-b pb-4">
                            <div class="flex justify-between items-start">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center mr-3">
                                        {{ substr($review->user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="font-medium">{{ $review->user->name }}</div>
                                        <div class="text-sm text-gray-500">
                                            {{ $review->created_at->format('M d, Y') }}
                                        </div>
                                    </div>
                                </div>
                                <div class="text-yellow-500">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $review->rating) ★ @else ☆ @endif
                                    @endfor
                                </div>
                            </div>
                            <p class="text-gray-700 mt-2">{{ $review->comment }}</p>

                            @auth
                                @if(auth()->user()->id === $review->user_id)
                                    <div class="mt-2 flex space-x-2">
                                        <a href="{{ route('reviews.edit', $review) }}"
                                           class="text-blue-600 text-sm hover:text-blue-800">
                                            Edit
                                        </a>
                                        <form action="{{ route('reviews.destroy', $review) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="text-red-600 text-sm hover:text-red-800"
                                                    onclick="return confirm('Are you sure?')">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            @endauth
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500">No reviews yet.</p>
            @endif
        </div>
    @endauth
@endsection
