@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-3xl mx-auto">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h1 class="text-2xl font-bold mb-6">Book {{ $property->title }}</h1>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Property Summary -->
                    <div>
                        <h2 class="text-xl font-semibold mb-4">Property Details</h2>
                        @if($property->mainImage)
                            <img src="{{ asset('storage/' . $property->mainImage->image_path) }}"
                                 alt="{{ $property->title }}"
                                 class="w-full h-48 object-cover rounded-lg mb-4">
                        @endif
                        <div class="space-y-2">
                            <p><span class="font-semibold">Location:</span> {{ $property->city }}, {{ $property->country }}</p>
                            <p><span class="font-semibold">Price per night:</span> ${{ number_format($property->price_per_night, 2) }}</p>
                            <p><span class="font-semibold">Capacity:</span> {{ $property->capacity }} guests</p>
                            <p><span class="font-semibold">Bedrooms:</span> {{ $property->bedrooms }}</p>
                            <p><span class="font-semibold">Bathrooms:</span> {{ $property->bathrooms }}</p>
                        </div>
                    </div>

                    <!-- Booking Form -->
                    <div>
                        <h2 class="text-xl font-semibold mb-4">Booking Information</h2>
                        <form action="{{ route('bookings.store', $property) }}" method="POST">
                            @csrf

                            <div class="grid grid-cols-1 gap-6">
                                <div>
                                    <label for="check_in" class="block text-sm font-medium text-gray-700">Check-in Date*</label>
                                    <input type="date" id="check_in" name="check_in"
                                           min="{{ date('Y-m-d') }}"
                                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                           required>
                                </div>

                                <div>
                                    <label for="check_out" class="block text-sm font-medium text-gray-700">Check-out Date*</label>
                                    <input type="date" id="check_out" name="check_out"
                                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                           required>
                                </div>

                                <div>
                                    <label for="guests" class="block text-sm font-medium text-gray-700">Number of Guests*</label>
                                    <input type="number" id="guests" name="guests" min="1" max="{{ $property->capacity }}"
                                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                           required>
                                </div>

                                <div>
                                    <label for="special_requests" class="block text-sm font-medium text-gray-700">Special Requests</label>
                                    <textarea id="special_requests" name="special_requests" rows="3"
                                              class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500"></textarea>
                                </div>

                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <h3 class="text-lg font-medium">Price Summary</h3>
                                    <div class="mt-2 space-y-2" id="price-summary">
                                        <p>Select dates to see price</p>
                                    </div>
                                </div>

                                <div>
                                    <button type="submit"
                                            class="w-full bg-green-600 border border-transparent rounded-md py-3 px-4 flex items-center justify-center text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                        Book Now
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Calculate price when dates change
        document.addEventListener('DOMContentLoaded', function() {
            const pricePerNight = {{ $property->price_per_night }};
            const checkInInput = document.getElementById('check_in');
            const checkOutInput = document.getElementById('check_out');
            const priceSummary = document.getElementById('price-summary');

            function calculatePrice() {
                if (checkInInput.value && checkOutInput.value) {
                    const checkIn = new Date(checkInInput.value);
                    const checkOut = new Date(checkOutInput.value);

                    // Ensure check-out is after check-in
                    if (checkOut <= checkIn) {
                        priceSummary.innerHTML = '<p class="text-red-500">Check-out date must be after check-in date</p>';
                        return;
                    }

                    const nights = Math.ceil((checkOut - checkIn) / (1000 * 60 * 60 * 24));
                    const total = nights * pricePerNight;

                    priceSummary.innerHTML = `
                    <div class="flex justify-between">
                        <span>${nights} nights × $${pricePerNight.toFixed(2)}</span>
                        <span>$${(nights * pricePerNight).toFixed(2)}</span>
                    </div>
                    <div class="border-t my-2"></div>
                    <div class="flex justify-between font-bold">
                        <span>Total</span>
                        <span>$${total.toFixed(2)}</span>
                    </div>
                `;
                }
            }

            checkInInput.addEventListener('change', function() {
                checkOutInput.min = this.value;
                calculatePrice();
            });

            checkOutInput.addEventListener('change', calculatePrice);
        });
    </script>
@endsection
