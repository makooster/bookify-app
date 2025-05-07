@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-md mx-auto bg-white rounded-lg shadow-md p-6">
            <h1 class="text-2xl font-bold mb-6">Add New Amenity</h1>

            <form action="{{ route('admin.amenities.store') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label for="name" class="block text-gray-700 mb-2">Name*</label>
                    <input type="text" id="name" name="name" required
                           class="w-full p-2 border rounded @error('name') border-red-500 @enderror"
                           value="{{ old('name') }}">
                    @error('name') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="mb-4">
                    <label for="icon" class="block text-gray-700 mb-2">Icon (HTML or Unicode)</label>
                    <input type="text" id="icon" name="icon"
                           class="w-full p-2 border rounded @error('icon') border-red-500 @enderror"
                           value="{{ old('icon') }}"
                           placeholder="e.g., &lt;i class='fas fa-wifi'&gt;&lt;/i&gt; or ⚡">
                    @error('icon') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    <p class="text-sm text-gray-500 mt-1">You can use Font Awesome icons or any HTML/unicode character</p>
                </div>

                <div class="mt-6">
                    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 w-full">
                        Create Amenity
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
