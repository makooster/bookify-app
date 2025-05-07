@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h1 class="text-2xl font-bold mb-6">List Your Property</h1>

            <form action="{{ route('properties.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Basic Information -->
                <div class="mb-8">
                    <h2 class="text-xl font-semibold mb-4">Basic Information</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="title" class="block text-gray-700 mb-2">Property Title*</label>
                            <input type="text" id="title" name="title" required
                                   class="w-full p-2 border rounded @error('title') border-red-500 @enderror"
                                   value="{{ old('title') }}">
                            @error('title') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="type" class="block text-gray-700 mb-2">Property Type*</label>
                            <select id="type" name="type" required
                                    class="w-full p-2 border rounded @error('type') border-red-500 @enderror">
                                <option value="">Select Type</option>
                                <option value="apartment" {{ old('type') == 'apartment' ? 'selected' : '' }}>Apartment</option>
                                <option value="house" {{ old('type') == 'house' ? 'selected' : '' }}>House</option>
                                <option value="villa" {{ old('type') == 'villa' ? 'selected' : '' }}>Villa</option>
                                <option value="room" {{ old('type') == 'room' ? 'selected' : '' }}>Room</option>
                            </select>
                            @error('type') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="price_per_night" class="block text-gray-700 mb-2">Price Per Night ($)*</label>
                            <input type="number" id="price_per_night" name="price_per_night" min="1" required
                                   class="w-full p-2 border rounded @error('price_per_night') border-red-500 @enderror"
                                   value="{{ old('price_per_night') }}">
                            @error('price_per_night') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="capacity" class="block text-gray-700 mb-2">Max Guests*</label>
                            <input type="number" id="capacity" name="capacity" min="1" required
                                   class="w-full p-2 border rounded @error('capacity') border-red-500 @enderror"
                                   value="{{ old('capacity') }}">
                            @error('capacity') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="bedrooms" class="block text-gray-700 mb-2">Bedrooms*</label>
                            <input type="number" id="bedrooms" name="bedrooms" min="1" required
                                   class="w-full p-2 border rounded @error('bedrooms') border-red-500 @enderror"
                                   value="{{ old('bedrooms') }}">
                            @error('bedrooms') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="bathrooms" class="block text-gray-700 mb-2">Bathrooms*</label>
                            <input type="number" id="bathrooms" name="bathrooms" min="1" required
                                   class="w-full p-2 border rounded @error('bathrooms') border-red-500 @enderror"
                                   value="{{ old('bathrooms') }}">
                            @error('bathrooms') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <!-- Location Information -->
                <div class="mb-8">
                    <h2 class="text-xl font-semibold mb-4">Location</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label for="address" class="block text-gray-700 mb-2">Address*</label>
                            <input type="text" id="address" name="address" required
                                   class="w-full p-2 border rounded @error('address') border-red-500 @enderror"
                                   value="{{ old('address') }}">
                            @error('address') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="city" class="block text-gray-700 mb-2">City*</label>
                            <input type="text" id="city" name="city" required
                                   class="w-full p-2 border rounded @error('city') border-red-500 @enderror"
                                   value="{{ old('city') }}">
                            @error('city') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="country" class="block text-gray-700 mb-2">Country*</label>
                            <input type="text" id="country" name="country" required
                                   class="w-full p-2 border rounded @error('country') border-red-500 @enderror"
                                   value="{{ old('country') }}">
                            @error('country') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <!-- Description -->
                <div class="mb-8">
                    <h2 class="text-xl font-semibold mb-4">Description</h2>
                    <textarea id="description" name="description" rows="5" required
                              class="w-full p-2 border rounded @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                    @error('description') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Amenities -->
                <div class="mb-8">
                    <h2 class="text-xl font-semibold mb-4">Amenities</h2>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        @foreach($amenities as $amenity)
                            <div class="flex items-center">
                                <input type="checkbox" id="amenity_{{ $amenity->id }}" name="amenities[]"
                                       value="{{ $amenity->id }}" class="mr-2"
                                    {{ in_array($amenity->id, old('amenities', $property->amenities->pluck('id')->toArray())) ? 'checked' : '' }}>
                                <label for="amenity_{{ $amenity->id }}" class="flex items-center">
                                    @if($amenity->icon)
                                        <span class="mr-2">{!! $amenity->icon !!}</span>
                                    @endif
                                    <span>{{ $amenity->name }}</span>
                                </label>
                            </div>
                        @endforeach
                    </div>
                    @error('amenities') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Images -->
                <div class="mb-8">
                    <h2 class="text-xl font-semibold mb-4">Images</h2>
                    <div class="mb-4">
                        <label class="block text-gray-700 mb-2">Upload Images* (At least 1 required)</label>
                        <input type="file" id="images" name="images[]" multiple accept="image/*"
                               class="w-full p-2 border rounded @error('images') border-red-500 @enderror">
                        @error('images') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        @error('images.*') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-gray-700 mb-2">Select Main Image*</label>
                        <div id="image-preview-container" class="flex space-x-4 mb-4"></div>
                        <input type="hidden" id="main_image" name="main_image" required>
                        <p id="main-image-error" class="text-red-500 text-sm hidden">Please select a main image</p>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="mt-6">
                    <button type="submit" class="bg-green-600 text-white px-6 py-3 rounded hover:bg-green-700">
                        Submit Property
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Image preview and main image selection
        document.getElementById('images').addEventListener('change', function(e) {
            const container = document.getElementById('image-preview-container');
            container.innerHTML = '';

            Array.from(e.target.files).forEach((file, index) => {
                const reader = new FileReader();
                reader.onload = function(event) {
                    const div = document.createElement('div');
                    div.className = 'relative';
                    div.innerHTML = `
                    <img src="${event.target.result}" class="h-32 w-auto rounded-lg cursor-pointer"
                         onclick="selectMainImage(${index})" id="preview-${index}">
                    <div id="main-indicator-${index}" class="hidden absolute top-2 right-2 bg-blue-500 text-white rounded-full w-6 h-6 flex items-center justify-center">
                        ✓
                    </div>
                `;
                    container.appendChild(div);
                };
                reader.readAsDataURL(file);
            });
        });

        function selectMainImage(index) {
            document.querySelectorAll('[id^="main-indicator-"]').forEach(el => {
                el.classList.add('hidden');
            });
            document.getElementById(`main-indicator-${index}`).classList.remove('hidden');
            document.getElementById('main_image').value = index;
            document.getElementById('main-image-error').classList.add('hidden');
        }
    </script>
@endsection
