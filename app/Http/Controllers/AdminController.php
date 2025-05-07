<?php

namespace App\Http\Controllers;
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Property;
use App\Models\Booking;
use App\Models\Review;
use App\Models\Amenity;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function dashboard()
    {
        $totalUsers = User::count();
        $totalProperties = Property::count();
        $totalBookings = Booking::count();
        $totalReviews = Review::count();
        $pendingProperties = Property::where('is_approved', false)->count();
        $pendingBookings = Booking::where('status', 'pending')->count();

        $latestUsers = User::latest()->take(5)->get();
        $latestProperties = Property::with('user')->latest()->take(5)->get();
        $latestBookings = Booking::with(['user', 'property'])->latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalUsers', 'totalProperties', 'totalBookings', 'totalReviews',
            'pendingProperties', 'pendingBookings', 'latestUsers', 'latestProperties', 'latestBookings'
        ));
    }

    public function users()
    {
        $users = User::paginate(20);
        return view('admin.users.index', compact('users'));
    }

    public function properties()
    {
        $properties = Property::with('user')->paginate(20);
        return view('admin.properties.index', compact('properties'));
    }

    public function bookings()
    {
        $bookings = Booking::with(['user', 'property'])->paginate(20);
        return view('admin.bookings.index', compact('bookings'));
    }

    public function reviews()
    {
        $reviews = Review::with(['user', 'property'])->paginate(20);
        return view('admin.reviews.index', compact('reviews'));
    }

    public function amenities()
    {
        $amenities = Amenity::paginate(20);
        return view('admin.amenities.index', compact('amenities'));
    }

    public function createAmenity()
    {
        return view('admin.amenities.create');
    }

    public function storeAmenity(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:amenities',
            'icon' => 'nullable|string|max:255',
        ]);

        Amenity::create($validated);

        return redirect()->route('admin.amenities')
            ->with('success', 'Amenity created successfully');
    }

    public function editAmenity(Amenity $amenity)
    {
        return view('admin.amenities.edit', compact('amenity'));
    }

    public function updateAmenity(Request $request, Amenity $amenity)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:amenities,name,' . $amenity->id,
            'icon' => 'nullable|string|max:255',
        ]);

        $amenity->update($validated);

        return redirect()->route('admin.amenities')
            ->with('success', 'Amenity updated successfully');
    }

    public function destroyAmenity(Amenity $amenity)
    {
        $amenity->delete();

        return redirect()->route('admin.amenities')
            ->with('success', 'Amenity deleted successfully');
    }
}
