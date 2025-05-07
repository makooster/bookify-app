@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <!-- Search Form -->
        <div class="bg-white p-6 rounded-lg shadow-md mb-8">
            <form action="{{ route('properties.search') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-gray-700 mb-2">Location</label>
                    <input type="text" name="location" value="{{ request('location') }}"
                           class="w-full p-2 border rounded" placeholder="City or Country">
                </div>
                <div>
                    <label class="block text-gray-700 mb-2">Check In</label>
                    <input type="date" name="check_in" value="{{ request('check_in') }}" class="w-full p-2 border rounded">
                </div>
                <div>
                    <label class="block text-gray-700 mb-2">Check Out</label>
                    <input type="date" name="check_out" value="{{ request('check_out') }}" class="w-full p-2 border rounded">
                </div>
                <div>
                    <label class="block text-gray-700 mb-2">Guests</label>
                    <input type="number" name="guests" value="{{ request('guests') }}" min="1" class="w-full p-2 border rounded">
                </div>
                <div>
                    <label class="block text-gray-700 mb-2">Min Price</label>
                    <input type="number" name="min_price" value="{{ request('min_price') }}" min="0" class="w-full p-2 border rounded">
                </div>
                <div>
                    <label class="block text-gray-700 mb-2">Max Price</label>
                    <input type="number" name="max_price" value="{{ request('max_price') }}" min="0" class="w-full p-2 border rounded">
                </div>
                <div>
                    <label class="block text-gray-700 mb-2">Bedrooms</label>
                    <input type="number" name="bedrooms" value="{{ request('bedrooms') }}" min="1" class="w-full p-2 border rounded">
                </div>
                <div class="flex items-end">
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 w-full">
                        Search
                    </button>
                </div>
            </form>
        </div>

        <!-- Property Listings -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($properties as $property)
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    @if($property->mainImage)
                        <img src="{{ asset('storage/' . $property->mainImage->image_path) }}"
                             alt="{{ $property->title }}" class="w-full h-48 object-cover">
                    @endif
                    <div class="p-4">
                        <div class="flex justify-between items-start">
                            <h3 class="text-xl font-semibold">{{ $property->title }}</h3>
                            <span class="text-lg font-bold">${{ $property->price_per_night }}/night</span>
                        </div>
                        <p class="text-gray-600 mt-2">{{ $property->city }}, {{ $property->country }}</p>
                        <div class="flex items-center mt-2">
                            <span class="text-yellow-500">★ {{ number_format($property->average_rating, 1) }}</span>
                            <span class="text-gray-500 ml-2">({{ $property->reviews_count }} reviews)</span>
                        </div>
                        <div class="mt-4 flex justify-between items-center">
                            <div class="flex space-x-2 text-sm text-gray-500">
                                <span>{{ $property->bedrooms }} beds</span>
                                <span>{{ $property->bathrooms }} baths</span>
                                <span>{{ $property->capacity }} guests</span>
                            </div>
                            <a href="{{ route('properties.show', $property) }}"
                               class="text-blue-600 hover:text-blue-800 font-medium">
                                View Details
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $properties->links() }}
        </div>
    </div>
@endsection
