@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-md mx-auto bg-white rounded-lg shadow-md p-6">
            <h1 class="text-2xl font-bold mb-6">Write a Review</h1>

            <div class="mb-6">
                <h2 class="text-lg font-semibold mb-2">{{ $booking->property->title }}</h2>
                <p class="text-gray-600">
                    {{ $booking->check_in->format('M d, Y') }} - {{ $booking->check_out->format('M d, Y') }}
                </p>
            </div>

            <form action="{{ route('reviews.store', $booking) }}" method="POST">
                @csrf

                <!-- Rating -->
                <div class="mb-6">
                    <label class="block text-gray-700 mb-2">Your Rating*</label>
                    <div class="flex items-center">
                        @for($i = 1; $i <= 5; $i++)
                            <input type="radio" id="rating-{{ $i }}" name="rating" value="{{ $i }}"
                                   class="hidden peer" {{ old('rating') == $i ? 'checked' : '' }} required>
                            <label for="rating-{{ $i }}"
                                   class="text-3xl cursor-pointer peer-checked:text-yellow-500 text-gray-300 hover:text-yellow-400">
                                ★
                            </label>
                        @endfor
                    </div>
                    @error('rating') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Comment -->
                <div class="mb-6">
                    <label for="comment" class="block text-gray-700 mb-2">Your Review*</label>
                    <textarea id="comment" name="comment" rows="5" required
                              class="w-full p-2 border rounded @error('comment') border-red-500 @enderror"
                              placeholder="Share your experience...">{{ old('comment') }}</textarea>
                    @error('comment') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Submit Button -->
                <button type="submit"
                        class="w-full bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Submit Review
                </button>
            </form>
        </div>
    </div>

    <script>
        // Enhance rating selection
        document.addEventListener('DOMContentLoaded', function() {
            const stars = document.querySelectorAll('input[name="rating"]');
            stars.forEach(star => {
                star.addEventListener('change', function() {
                    const rating = this.value;
                    // Update visual display
                    document.querySelectorAll('label[for^="rating-"]').forEach((label, index) => {
                        if (index < rating) {
                            label.classList.add('text-yellow-500');
                            label.classList.remove('text-gray-300');
                        } else {
                            label.classList.add('text-gray-300');
                            label.classList.remove('text-yellow-500');
                        }
                    });
                });
            });
        });
    </script>
@endsection
