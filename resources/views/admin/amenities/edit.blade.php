@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-md mx-auto bg-white rounded-lg shadow-md p-6">
            <h1 class="text-2xl font-bold mb-6">Edit Amenity</h1>

            <form action="{{ route('admin.amenities.update', $amenity) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="name" class="block text-gray-700 mb-2">Name*</label>
                    <input type="text" id="name" name="name" required
                           class="w-full p-2 border rounded @error('name') border-red-500 @enderror"
                           value="{{ old('name', $amenity->name) }}">
                    @error('name') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="mb-4">
                    <label for="icon" class="block text-gray-700 mb-2">Icon (HTML or Unicode)</label>
                    <input type="text" id="icon" name="icon"
                           class="w-full p-2 border rounded @error('icon') border-red-500 @enderror"
                           value="{{ old('icon', $amenity->icon) }}"
                           placeholder="e.g., &lt;i class='fas fa-wifi'&gt;&lt;/i&gt; or ⚡">
                    @error('icon') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    @if($amenity->icon)
                        <div class="mt-2">
                            <span class="text-sm text-gray-700">Current icon preview:</span>
                            <div class="text-2xl mt-1">{!! $amenity->icon !!}</div>
                        </div>
                    @endif
                </div>

                <div class="mt-6 flex justify-between">
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                        Update Amenity
                    </button>
                    <a href="{{ route('admin.amenities.index') }}"
                       class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
