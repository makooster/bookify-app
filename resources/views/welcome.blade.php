<!-- resources/views/welcome.blade.php -->
@extends('layouts.app')

@section('title', 'Find Your Perfect Stay')

@section('styles')
    <style>
        .hero-section {
            background-image: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('{{ asset('images/hero-bg.jpg') }}');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 100px 0;
            margin-top: -24px;
        }

        .search-box {
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
            padding: 25px;
        }

        .property-card {
            transition: transform 0.3s;
            height: 100%;
        }

        .property-card:hover {
            transform: translateY(-5px);
        }

        .property-image {
            height: 200px;
            object-fit: cover;
        }

        .amenity-icon {
            font-size: 2rem;
            color: #0d6efd;
        }
    </style>
@endsection

@section('content')
    <!-- Hero Section with Search -->
    <section class="hero-section">
        <div class="container">
            <div class="row">
                <div class="col-md-8 offset-md-2 text-center mb-4">
                    <h1 class="display-4 fw-bold">Find Your Perfect Stay</h1>
                    <p class="lead">Discover amazing properties at the best prices worldwide</p>
                </div>
            </div>

            <div class="row">
                <div class="col-md-10 offset-md-1">
                    <div class="search-box shadow">
                        <form action="{{ route('properties.search') }}" method="GET">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label for="location" class="form-label text-dark">Where are you going?</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                                        <input type="text" class="form-control" id="location" name="location" placeholder="City, Country">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <label for="check_in" class="form-label text-dark">Check-in</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                        <input type="date" class="form-control" id="check_in" name="check_in" min="{{ date('Y-m-d') }}">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <label for="check_out" class="form-label text-dark">Check-out</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                        <input type="date" class="form-control" id="check_out" name="check_out" min="{{ date('Y-m-d', strtotime('+1 day')) }}">
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <label for="guests" class="form-label text-dark">Guests</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-user-friends"></i></span>
                                        <select class="form-select" id="guests" name="guests">
                                            @for ($i = 1; $i <= 10; $i++)
                                                <option value="{{ $i }}">{{ $i }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12 text-center mt-4">
                                    <button type="submit" class="btn btn-primary btn-lg px-5"><i class="fas fa-search me-2"></i> Search</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Properties -->
    <section class="py-5">
        <div class="container">
            <h2 class="text-center mb-4">Featured Properties</h2>
            <div class="row">
                @foreach ($featuredProperties as $property)
                    <div class="col-md-4 mb-4">
                        <div class="card property-card shadow-sm h-100">
                            <img src="{{ $property->images->where('is_main', true)->first()
                            ? asset('storage/' . $property->images->where('is_main', true)->first()->image_path)
                            : asset('images/property-placeholder.jpg') }}"
                                 class="card-img-top property-image" alt="{{ $property->title }}">

                            <div class="card-body">
                                <h5 class="card-title">{{ $property->title }}</h5>
                                <p class="card-text text-muted">
                                    <i class="fas fa-map-marker-alt"></i> {{ $property->city }}, {{ $property->country }}
                                </p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="fs-5 fw-bold text-primary">${{ $property->price_per_night }} <small class="text-muted fw-normal">/ night</small></span>
                                    <div>
                                        <i class="fas fa-star text-warning"></i>
                                        <span>{{ $property->reviews->avg('rating') ? number_format($property->reviews->avg('rating'), 1) : 'New' }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer bg-white border-top-0">
                                <small class="text-muted">
                                    <i class="fas fa-bed"></i> {{ $property->bedrooms }} Bedrooms •
                                    <i class="fas fa-bath"></i> {{ $property->bathrooms }} Bathrooms •
                                    <i class="fas fa-user-friends"></i> {{ $property->capacity }} Guests
                                </small>
                            </div>
                            <a href="{{ route('properties.show', $property) }}" class="stretched-link"></a>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="text-center mt-4">
                <a href="{{ route('properties.index') }}" class="btn btn-outline-primary btn-lg">View All Properties</a>
            </div>
        </div>
    </section>

    <!-- How It Works -->
    <section class="py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-5">How It Works</h2>
            <div class="row g-4">
                <div class="col-md-4 text-center">
                    <div class="p-3">
                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mx-auto mb-4" style="width: 80px; height: 80px;">
                            <i class="fas fa-search fa-2x"></i>
                        </div>
                        <h4>Search</h4>
                        <p class="text-muted">Browse through our wide selection of properties, filter based on your preferences.</p>
                    </div>
                </div>
                <div class="col-md-4 text-center">
                    <div class="p-3">
                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mx-auto mb-4" style="width: 80px; height: 80px;">
                            <i class="far fa-calendar-check fa-2x"></i>
                        </div>
                        <h4>Book</h4>
                        <p class="text-muted">Book your favorite property for the dates you want to travel.</p>
                    </div>
                </div>
                <div class="col-md-4 text-center">
                    <div class="p-3">
                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mx-auto mb-4" style="width: 80px; height: 80px;">
                            <i class="fas fa-suitcase fa-2x"></i>
                        </div>
                        <h4>Travel</h4>
                        <p class="text-muted">Enjoy your stay and create unforgettable memories.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Property Types -->
    <section class="py-5">
        <div class="container">
            <h2 class="text-center mb-4">Browse by Property Type</h2>
            <div class="row g-4">
                <div class="col-6 col-md-3">
                    <a href="{{ route('properties.search', ['type' => 'apartment']) }}" class="text-decoration-none">
                        <div class="card h-100 shadow-sm text-center">
                            <div class="card-body">
                                <i class="fas fa-building fa-3x text-primary mb-3"></i>
                                <h5 class="card-title">Apartments</h5>
                                <p class="text-muted">Urban living spaces</p>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-6 col-md-3">
                    <a href="{{ route('properties.search', ['type' => 'house']) }}" class="text-decoration-none">
                        <div class="card h-100 shadow-sm text-center">
                            <div class="card-body">
                                <i class="fas fa-home fa-3x text-primary mb-3"></i>
                                <h5 class="card-title">Houses</h5>
                                <p class="text-muted">Complete homes</p>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-6 col-md-3">
                    <a href="{{ route('properties.search', ['type' => 'villa']) }}" class="text-decoration-none">
                        <div class="card h-100 shadow-sm text-center">
                            <div class="card-body">
                                <i class="fas fa-landmark fa-3x text-primary mb-3"></i>
                                <h5 class="card-title">Villas</h5>
                                <p class="text-muted">Luxury residences</p>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-6 col-md-3">
                    <a href="{{ route('properties.search', ['type' => 'room']) }}" class="text-decoration-none">
                        <div class="card h-100 shadow-sm text-center">
                            <div class="card-body">
                                <i class="fas fa-door-open fa-3x text-primary mb-3"></i>
                                <h5 class="card-title">Rooms</h5>
                                <p class="text-muted">Private or shared</p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Set min date for checkout based on check-in date
            const checkInInput = document.getElementById('check_in');
            const checkOutInput = document.getElementById('check_out');

            checkInInput.addEventListener('change', function() {
                const checkInDate = new Date(this.value);
                const nextDay = new Date(checkInDate);
                nextDay.setDate(checkInDate.getDate() + 1);

                const year = nextDay.getFullYear();
                const month = String(nextDay.getMonth() + 1).padStart(2, '0');
                const day = String(nextDay.getDate()).padStart(2, '0');

                checkOutInput.min = `${year}-${month}-${day}`;

                // If the current checkout date is before the new min date, update it
                if (new Date(checkOutInput.value) < nextDay) {
                    checkOutInput.value = `${year}-${month}-${day}`;
                }
            });
        });
    </script>
@endsection
