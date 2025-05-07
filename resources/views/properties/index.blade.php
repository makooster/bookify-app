<!-- resources/views/properties/index.blade.php -->
@extends('layouts.app')

@section('title', 'Browse Properties')

@section('styles')
    <style>
        .filter-card {
            position: sticky;
            top: 20px;
        }

        .property-card {
            transition: transform 0.3s;
        }

        .property-card:hover {
            transform: translateY(-5px);
        }

        .property-image {
            height: 200px;
            object-fit: cover;
        }

        .property-amenities {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
    </style>
@endsection

@section('content')
    <div class="container py-4">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Properties</li>
            </ol>
        </nav>

        <!-- Search Filters -->
        <div class="row mb-4">
            <div class="col">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <form action="{{ route('properties.search') }}" method="GET">
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <label for="location" class="form-label">Location</label>
                                    <input type="text" class="form-control" id="location" name="location"
                                           value="{{ request('location') }}" placeholder="City, Country">
                                </div>

                                <div class="col-md-2">
                                    <label for="check_in" class="form-label">Check-in</label>
                                    <input type="date" class="form-control" id="check_in" name="check_in"
                                           value="{{ request('check_in') }}" min="{{ date('Y-m-d') }}">
                                </div>

                                <div class="col-md-2">
                                    <label for="check_out" class="form-label">Check-out</label>
                                    <input type="date" class="form-control" id="check_out" name="check_out"
                                           value="{{ request('check_out') }}" min="{{ request('check_in') ? date('Y-m-d', strtotime(request('check_in') . ' +1 day')) : date('Y-m-d', strtotime('+1 day')) }}">
                                </div>

                                <div class="col-md-1">
                                    <label for="guests" class="form-label">Guests</label>
                                    <select class="form-select" id="guests" name="guests">
                                        <option value="">Any</option>
                                        @for ($i = 1; $i <= 10; $i++)
                                            <option value="{{ $i }}" {{ request('guests') == $i ? 'selected' : '' }}>{{ $i }}+</option>
                                        @endfor
                                    </select>
                                </div>

                                <div class="col-md-2">
                                    <label for="type" class="form-label">Property Type</label>
                                    <select class="form-select" id="type" name="type">
                                        <option value="">Any Type</option>
                                        <option value="apartment" {{ request('type') == 'apartment' ? 'selected' : '' }}>Apartment</option>
                                        <option value="house" {{ request('type') == 'house' ? 'selected' : '' }}>House</option>
                                        <option value="villa" {{ request('type') == 'villa' ? 'selected' : '' }}>Villa</option>
                                        <option value="room" {{ request('type') == 'room' ? 'selected' : '' }}>Room</option>
                                    </select>
                                </div>

                                <div class="col-md-2">
                                    <label for="bedrooms" class="form-label">Bedrooms</label>
                                    <select class="form-select" id="bedrooms" name="bedrooms">
                                        <option value="">Any</option>
                                        @for ($i = 1; $i <= 5; $i++)
                                            <option value="{{ $i }}" {{ request('bedrooms') == $i ? 'selected' : '' }}>{{ $i }}+</option>
                                        @endfor
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label for="price_range" class="form-label">Price Range</label>
                                    <div class="row">
                                        <div class="col">
                                            <div class="input-group">
                                                <span class="input-group-text">$</span>
                                                <input type="number" class="form-control" id="min_price" name="min_price"
                                                       value="{{ request('min_price') }}" placeholder="Min">
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="input-group">
                                                <span class="input-group-text">$</span>
                                                <input type="number" class="form-control" id="max_price" name="max_price"
                                                       value="{{ request('max_price') }}" placeholder="Max">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-8 align-self-end text-end">
                                    <button type="submit" class="btn btn-primary"><i class="fas fa-search me-2"></i> Search</button>
                                    <a href="{{ route('properties.index') }}" class="btn btn-outline-secondary ms-2">Reset</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Results Section -->
        <div class="row mb-4">
            <div class="col">
                <h2>{{ request()->has('location') ? 'Search Results' : 'All Properties' }}</h2>
                <p class="text-muted">{{ $properties->total() }} properties found</p>

                @if(request()->has('location') || request()->has('check_in') || request()->has('guests') || request()->has('type') || request()->has('min_price') || request()->has('max_price') || request()->has('bedrooms'))
                    <div class="mb-3">
                        <span class="me-2">Filters:</span>
                        @if(request()->has('location'))
                            <span class="badge bg-primary me-2">Location: {{ request('location') }}</span>
                        @endif
                        @if(request()->has('check_in') && request()->has('check_out'))
                            <span class="badge bg-primary me-2">Dates: {{ request('check_in') }} to {{ request('check_out') }}</span>
                        @endif
                        @if(request()->has('guests'))
                            <span class="badge bg-primary me-2">Guests: {{ request('guests') }}+</span>
                        @endif
                        @if(request()->has('type'))
                            <span class="badge bg-primary me-2">Type: {{ ucfirst(request('type')) }}</span>
                        @endif
                        @if(request()->has('min_price') || request()->has('max_price'))
                            <span class="badge bg-primary me-2">Price: ${{ request('min_price', '0') }} - ${{ request('max_price', '∞') }}</span>
                        @endif
                        @if(request()->has('bedrooms'))
                            <span class="badge bg-primary me-2">Bedrooms: {{ request('bedrooms') }}+</span>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        <!-- Property Listings -->
        <div class="row">
            @forelse ($properties as $property)
                <div class="col-md-4 mb-4">
                    <div class="card property-card shadow-sm h-100">
                        <img src="{{ $property->images->where('is_main', true)->first()
                        ? asset('storage/' . $property->images->where('is_main', true)->first()->path)
: 'https://via.placeholder.com/400x200?text=No+Image' }}"
                             class="card-img-top property-image" alt="{{ $property->title }}">

                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">{{ $property->title }}</h5>
                            <p class="card-text text-muted mb-1"><i class="fas fa-map-marker-alt me-1"></i> {{ $property->location }}</p>
                            <p class="card-text fw-bold mb-2">${{ number_format($property->price_per_night, 2) }} / night</p>
                            <p class="card-text property-amenities">
                                <i class="fas fa-bed me-1"></i> {{ $property->bedrooms }} Bedrooms &nbsp;
                                <i class="fas fa-user-friends me-1"></i> {{ $property->max_guests }} Guests
                            </p>
                            <a href="{{ route('properties.show', $property->id) }}" class="btn btn-sm btn-outline-primary mt-auto">View Details</a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col">
                    <div class="alert alert-warning text-center">
                        <i class="fas fa-info-circle me-2"></i> No properties found matching your criteria.
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            {{ $properties->appends(request()->query())->links() }}
        </div>
    </div>
@endsection

