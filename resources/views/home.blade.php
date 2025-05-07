@extends('layouts.app')

@section('title', 'Home')

@section('content')
    <!-- Hero Section -->
    <div class="relative bg-gray-900 overflow-hidden">
        <div class="max-w-7xl mx-auto">
            <div class="relative z-10 pb-8 bg-gray-900 sm:pb-16 md:pb-20 lg:max-w-2xl lg:w-full lg:pb-28 xl:pb-32">
                <div class="pt-10 sm:pt-16 lg:pt-8 lg:pb-14 lg:overflow-hidden">
                    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                        <div class="lg:grid lg:grid-cols-2 lg:gap-8">
                            <div class="mx-auto max-w-md px-4 sm:max-w-2xl sm:px-6 sm:text-center lg:px-0 lg:text-left lg:flex lg:items-center">
                                <div class="lg:py-24">
                                    <h1 class="mt-4 text-4xl tracking-tight font-extrabold text-white sm:mt-5 sm:text-6xl lg:mt-6 xl:text-6xl">
                                        <span class="block">Find your perfect</span>
                                        <span class="block text-blue-400">vacation rental</span>
                                    </h1>
                                    <p class="mt-3 text-base text-gray-300 sm:mt-5 sm:text-xl lg:text-lg xl:text-xl">
                                        Discover and book unique accommodations around the world. From cozy apartments to luxury villas, we have the perfect place for your next getaway.
                                    </p>
                                    <div class="mt-10 sm:mt-12">
                                        <form action="{{ route('properties.search') }}" method="GET" class="sm:mx-auto lg:mx-0">
                                            <div class="sm:flex">
                                                <div class="min-w-0 flex-1">
                                                    <label for="location" class="sr-only">Location</label>
                                                    <input id="location" name="location" type="text" placeholder="Where are you going?" class="block w-full px-4 py-3 rounded-md border-0 text-base text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-400">
                                                </div>
                                                <div class="mt-3 sm:mt-0 sm:ml-3">
                                                    <button type="submit" class="block w-full py-3 px-4 rounded-md shadow bg-blue-500 text-white font-medium hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-300">
                                                        Search
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="lg:absolute lg:inset-y-0 lg:right-0 lg:w-1/2">
            <img class="h-56 w-full object-cover sm:h-72 md:h-96 lg:w-full lg:h-full" src="https://images.unsplash.com/photo-1519046904884-53103b34b206?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1470&q=80" alt="Beach vacation">
        </div>
    </div>

    <!-- Featured Properties -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="text-center">
            <h2 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">
                Featured Properties
            </h2>
            <p class="mt-3 max-w-2xl mx-auto text-xl text-gray-500 sm:mt-4">
                Discover our hand-picked selection of top-rated vacation rentals
            </p>
        </div>

        <div class="mt-10 max-w-lg mx-auto grid gap-5 lg:grid-cols-3 lg:max-w-none">
            @foreach($properties as $property)
                <div class="flex flex-col rounded-lg shadow-lg overflow-hidden">
                    <div class="flex-shrink-0">
                        @if($property->images->count() > 0)
                            <img class="h-48 w-full object-cover" src="{{ asset('storage/' . ($property->mainImage ? $property->mainImage->image_path : $property->images->first()->image_path)) }}" alt="{{ $property->title }}">
                        @else
                            <div class="h-48 w-full bg-gray-200 flex items-center justify-center">
                                <svg class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                        @endif
                    </div>
                    <div class="flex-1 bg-white p-6 flex flex-col justify-between">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-blue-600">
                                {{ ucfirst($property->type) }} in {{ $property->city }}
                            </p>
                            <a href="{{ route('properties.show', $property) }}" class="block mt-2">
                                <p class="text-xl font-semibold text-gray-900">{{ $property->title }}</p>
                                <p class="mt-3 text-base text-gray-500">{{ Str::limit($property->description, 100) }}</p>
                            </a>
                        </div>
                        <div class="mt-6 flex items-center">
                            <div class="flex-shrink-0">
                                <span class="sr-only">{{ $property->user->name }}</span>
                                <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center">
                                    <span class="text-gray-500 font-medium">{{ substr($property->user->name, 0, 1) }}</span>
                                </div>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">
                                    {{ $property->user->name }}
                                </p>
                                <div class="flex space-x-1 text-sm text-gray-500">
                                    <span>{{ $property->bedrooms }} bed</span>
                                    <span aria-hidden="true">&middot;</span>
                                    <span>{{ $property->bathrooms }} bath</span>
                                    <span aria-hidden="true">&middot;</span>
                                    <span>{{ $property->capacity }} guests</span>
                                </div>
                            </div>
                            <div class="ml-auto text-right">
                                <p class="text-sm font-semibold text-gray-900">${{ number_format($property->price_per_night, 2) }} / night</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        @if($properties->count() === 0)
            <div class="mt-10 text-center text-gray-500">
                No featured properties found.
            </div>
        @endif
    </div>
@endsection

