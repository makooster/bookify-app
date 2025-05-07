<?php

namespace App\Http\Controllers;


use App\Http\Controllers\Controller;
use App\Http\Resources\PropertyResource;
use App\Models\Amenity;
use App\Models\Property;
use App\Models\PropertyImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PropertyController extends Controller
{
//     public function __construct()
//     {
//         $this->middleware('auth');
//     }

    public function index()
    {
        $properties = Property::where('is_approved', true)
            ->where('is_available', true)
            ->with(['images', 'amenities', 'user'])
            ->paginate(12);

        return view('properties.index', compact('properties'));
    }

    public function create()
    {
        $this->authorize('create', Property::class);

        $amenities = Amenity::all();
        return view('properties.create', compact('amenities'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Property::class);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|in:apartment,house,villa,room',
            'price_per_night' => 'required|numeric|min:1',
            'capacity' => 'required|integer|min:1',
            'bedrooms' => 'required|integer|min:1',
            'bathrooms' => 'required|integer|min:1',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'amenities' => 'nullable|array',
            'amenities.*' => 'exists:amenities,id',
            'images' => 'required|array|min:1',
            'images.*' => 'image|mimes:jpeg,png,jpg|max:2048',
            'main_image' => 'required|integer|min:0',
        ]);

        $property = Property::create([
            'user_id' => auth()->id(),
            'title' => $validated['title'],
            'description' => $validated['description'],
            'type' => $validated['type'],
            'price_per_night' => $validated['price_per_night'],
            'capacity' => $validated['capacity'],
            'bedrooms' => $validated['bedrooms'],
            'bathrooms' => $validated['bathrooms'],
            'address' => $validated['address'],
            'city' => $validated['city'],
            'country' => $validated['country'],
            'is_available' => true,
            'is_approved' => auth()->user()->isAdmin() ? true : false,
        ]);

        if (isset($validated['amenities'])) {
            $property->amenities()->attach($validated['amenities']);
        }

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('property-images', 'public');

                PropertyImage::create([
                    'property_id' => $property->id,
                    'image_path' => $path,
                    'is_main' => $index == $validated['main_image'],
                ]);
            }
        }

        return redirect()->route('properties.show', $property)
            ->with('success', 'Property created successfully');
    }

    public function show(Property $property)
    {
        if (!$property->is_approved && auth()->id() !== $property->user_id && !auth()->user()?->isAdmin()) {
            abort(404);
        }

        $property->load(['images', 'amenities', 'user', 'reviews.user']);

        return view('properties.show', compact('property'));
    }

    public function edit(Property $property)
    {
        $this->authorize('update', $property);

        $amenities = Amenity::all();
        return view('properties.edit', compact('property', 'amenities'));
    }

    public function update(Request $request, Property $property)
    {
        $this->authorize('update', $property);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|in:apartment,house,villa,room',
            'price_per_night' => 'required|numeric|min:1',
            'capacity' => 'required|integer|min:1',
            'bedrooms' => 'required|integer|min:1',
            'bathrooms' => 'required|integer|min:1',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'is_available' => 'boolean',
            'amenities' => 'nullable|array',
            'amenities.*' => 'exists:amenities,id',
            'new_images' => 'nullable|array',
            'new_images.*' => 'image|mimes:jpeg,png,jpg|max:2048',
            'main_image' => 'nullable|integer',
            'delete_images' => 'nullable|array',
            'delete_images.*' => 'exists:property_images,id',
        ]);

        $property->update([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'type' => $validated['type'],
            'price_per_night' => $validated['price_per_night'],
            'capacity' => $validated['capacity'],
            'bedrooms' => $validated['bedrooms'],
            'bathrooms' => $validated['bathrooms'],
            'address' => $validated['address'],
            'city' => $validated['city'],
            'country' => $validated['country'],
            'is_available' => $request->has('is_available'),
        ]);

        if (isset($validated['amenities'])) {
            $property->amenities()->sync($validated['amenities']);
        } else {
            $property->amenities()->detach();
        }

        // Delete images if requested
        if (isset($validated['delete_images'])) {
            $imagesToDelete = PropertyImage::whereIn('id', $validated['delete_images'])
                ->where('property_id', $property->id)
                ->get();

            foreach ($imagesToDelete as $image) {
                Storage::disk('public')->delete($image->image_path);
                $image->delete();
            }
        }

        // Add new images if uploaded
        if ($request->hasFile('new_images')) {
            foreach ($request->file('new_images') as $index => $image) {
                $path = $image->store('property-images', 'public');

                PropertyImage::create([
                    'property_id' => $property->id,
                    'image_path' => $path,
                    'is_main' => false,
                ]);
            }
        }

        // Update main image if specified
        if (isset($validated['main_image'])) {
            // Reset all images to not main
            PropertyImage::where('property_id', $property->id)
                ->update(['is_main' => false]);

            // Set the selected image as main
            PropertyImage::where('id', $validated['main_image'])
                ->where('property_id', $property->id)
                ->update(['is_main' => true]);
        }

        return redirect()->route('properties.show', $property)
            ->with('success', 'Property updated successfully');
    }

    public function destroy(Property $property)
    {
        $this->authorize('delete', $property);

        // Delete all associated images from storage
        foreach ($property->images as $image) {
            Storage::disk('public')->delete($image->image_path);
        }

        $property->delete();

        return redirect()->route('properties.index')
            ->with('success', 'Property deleted successfully');
    }

    public function search(Request $request)
    {
        $validated = $request->validate([
            'location' => 'nullable|string|max:255',
            'check_in' => 'nullable|date',
            'check_out' => 'nullable|date|after:check_in',
            'guests' => 'nullable|integer|min:1',
            'min_price' => 'nullable|numeric|min:0',
            'max_price' => 'nullable|numeric|min:0',
            'bedrooms' => 'nullable|integer|min:1',
            'type' => 'nullable|in:apartment,house,villa,room',
        ]);

        $query = Property::where('is_approved', true)
            ->where('is_available', true);

        if ($request->filled('location')) {
            $query->where(function($q) use ($validated) {
                $q->where('city', 'like', '%' . $validated['location'] . '%')
                    ->orWhere('country', 'like', '%' . $validated['location'] . '%');
            });
        }

        if ($request->filled('guests')) {
            $query->where('capacity', '>=', $validated['guests']);
        }

        if ($request->filled('min_price')) {
            $query->where('price_per_night', '>=', $validated['min_price']);
        }

        if ($request->filled('max_price')) {
            $query->where('price_per_night', '<=', $validated['max_price']);
        }

        if ($request->filled('bedrooms')) {
            $query->where('bedrooms', '>=', $validated['bedrooms']);
        }

        if ($request->filled('type')) {
            $query->where('type', $validated['type']);
        }

        if ($request->filled('check_in') && $request->filled('check_out')) {
            $checkIn = $validated['check_in'];
            $checkOut = $validated['check_out'];

            // Exclude properties with bookings that overlap with the requested dates
            $query->whereDoesntHave('bookings', function($q) use ($checkIn, $checkOut) {
                $q->where(function($q) use ($checkIn, $checkOut) {
                    $q->where(function($q) use ($checkIn, $checkOut) {
                        $q->where('check_in', '<=', $checkIn)
                            ->where('check_out', '>=', $checkIn);
                    })->orWhere(function($q) use ($checkIn, $checkOut) {
                        $q->where('check_in', '<=', $checkOut)
                            ->where('check_out', '>=', $checkOut);
                    })->orWhere(function($q) use ($checkIn, $checkOut) {
                        $q->where('check_in', '>=', $checkIn)
                            ->where('check_out', '<=', $checkOut);
                    });
                })->whereIn('status', ['pending', 'confirmed']);
            });
        }

        $properties = $query->with(['images', 'amenities', 'user'])
            ->paginate(12)
            ->appends($request->all());

        return view('properties.index', compact('properties'));
    }

    public function approve(Property $property)
    {
        $this->authorize('approve', $property);

        $property->update(['is_approved' => true]);

        return back()->with('success', 'Property approved successfully');
    }

    // API Methods
    public function apiIndex()
    {
        $properties = Property::where('is_approved', true)
            ->where('is_available', true)
            ->with(['images', 'amenities', 'user'])
            ->paginate(12);

        return PropertyResource::collection($properties);
    }

    public function apiShow(Property $property)
    {
        if (!$property->is_approved && auth()->id() !== $property->user_id && !auth()->user()?->isAdmin()) {
            return response()->json(['message' => 'Property not found'], 404);
        }

        $property->load(['images', 'amenities', 'user', 'reviews.user']);

        return new PropertyResource($property);
    }
}
