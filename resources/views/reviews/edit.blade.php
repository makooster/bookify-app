@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-md mx-auto bg-white rounded-lg shadow-md p-6">
            <h1 class="text-2xl font-bold mb-6">Edit Your Review</h1>

            <div class="mb-6">
                <h2 class="text-lg font-semibold mb-2">{{ $review->property->title }}</h2>
                <p class="text-gray-600">
                    Reviewed on {{ $review->created_at->format('M d, Y') }}
                </p>
            </div>

            <form action="{{ route('reviews.update', $review) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Rating -->
                <div class="mb-6">
                    <label class="block text-gray-700 mb-2">Your Rating*</label>
                    <div class="flex items-center">
                        @for($i = 1; $i <= 5; $i++)
                            <input type="radio" id="rating-{{ $i }}" name="rating" value="{{ $i }}"
                                   class="hidden peer" {{ old('rating', $review->rating) == $i ? 'checked' : '' }} required>
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
                              placeholder="Share your experience...">{{ old('comment', $review->comment) }}</textarea>
                    @error('comment') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-between">
                    <button type="submit"
                            class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                        Update Review
                    </button>

                    <button type="button" onclick="confirmDelete()"
                            class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">
                        Delete Review
                    </button>
                </div>
            </form>

            <!-- Delete Form -->
            <form id="delete-form" action="{{ route('reviews.destroy', $review) }}" method="POST" class="hidden">
                @csrf
                @method('DELETE')
            </form>
        </div>
    </div>

    <script>
        // Initialize star rating display
        document.addEventListener('DOMContentLoaded', function() {
            const currentRating = {{ old('rating', $review->rating) }};
            document.querySelectorAll('label[for^="rating-"]').forEach((label, index) => {
                if (index < currentRating) {
                    label.classList.add('text-yellow-500');
                    label.classList.remove('text-gray-300');
                }
            });

            // Rating change handler
            document.querySelectorAll('input[name="rating"]').forEach(star => {
                star.addEventListener('change', function() {
                    const rating = this.value;
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

        function confirmDelete() {
            if (confirm('Are you sure you want to delete this review? This action cannot be undone.')) {
                document.getElementById('delete-form').submit();
            }
        }
    </script>
@endsection
