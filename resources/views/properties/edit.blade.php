@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h1 class="text-2xl font-bold mb-6">Edit Property</h1>

            <form action="{{ route('properties.update', $property) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Reuse the create form structure but with existing values -->
                <!-- Basic Information -->
                <div class="mb-8">
                    <h2 class="text-xl font-semibold mb-4">Basic Information</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="title" class="block text-gray-700 mb-2">Property Title*</label>
                            <input type="text" id="title" name="title" required
                                   class="w-full p-2 border rounded @error('title') border-red-500 @enderror"
                                   value="{{ old('title', $property->title) }}">
                            @error('title') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Other fields similar to create form but with old('field', $property->field) -->
                        <!-- ... -->
                    </div>
                </div>

                <!-- Existing Images -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold mb-3">Current Images</h3>
                    <div class="flex flex-wrap gap-4">
                        @foreach($property->images as $image)
                            <div class="relative">
                                <img src="{{ asset('storage/' . $image->image_path) }}"
                                     class="h-32 w-auto rounded-lg">
                                <div class="absolute top-2 right-2">
                                    <input type="checkbox" id="delete_image_{{ $image->id }}"
                                           name="delete_images[]" value="{{ $image->id }}"
                                           class="h-5 w-5">
                                    <label for="delete_image_{{ $image->id }}" class="sr-only">Delete</label>
                                </div>
                                <div class="mt-2 text-center">
                                    <input type="radio" id="main_image_{{ $image->id }}"
                                           name="main_image" value="{{ $image->id }}"
                                           {{ $image->is_main ? 'checked' : '' }}
                                           class="h-4 w-4">
                                    <label for="main_image_{{ $image->id }}">Main Image</label>
                                </div>
                            </div>
                        @endforeach
                    </div>
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

                <!-- New Images -->
                <div class="mb-8">
                    <h3 class="text-lg font-semibold mb-3">Add New Images</h3>
                    <input type="file" name="new_images[]" multiple accept="image/*"
                           class="w-full p-2 border rounded">
                    @error('new_images') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    @error('new_images.*') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Submit Button -->
                <div class="mt-6 flex justify-between">
                    <button type="submit" class="bg-blue-600 text-white px-6 py-3 rounded hover:bg-blue-700">
                        Update Property
                    </button>

                    <button type="button" onclick="confirmDelete()"
                            class="bg-red-600 text-white px-6 py-3 rounded hover:bg-red-700">
                        Delete Property
                    </button>
                </div>
            </form>

            <!-- Delete Form -->
            <form id="delete-form" action="{{ route('properties.destroy', $property) }}" method="POST" class="hidden">
                @csrf
                @method('DELETE')
            </form>
        </div>
    </div>

    <script>
        function confirmDelete() {
            if (confirm('Are you sure you want to delete this property? This action cannot be undone.')) {
                document.getElementById('delete-form').submit();
            }
        }
    </script>
@endsection
