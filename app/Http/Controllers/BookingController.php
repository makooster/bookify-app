<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use App\Notifications\BookingConfirmed;
use App\Notifications\BookingCreated;
use App\Http\Resources\BookingResource;

class BookingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = auth()->user();

        if ($user->isOwner() || $user->isAdmin()) {
            $bookings = Booking::whereHas('property', function($query) use ($user) {
                if (!$user->isAdmin()) {
                    $query->where('user_id', $user->id);
                }
            })->with(['property.images', 'user'])->latest()->paginate(10);
        } else {
            $bookings = $user->bookings()->with(['property.images'])->latest()->paginate(10);
        }

        return view('bookings.index', compact('bookings'));
    }

    public function create(Property $property)
    {
        return view('bookings.create', compact('property'));
    }

    public function store(Request $request, Property $property)
    {
        $this->authorize('book', $property);

        $validated = $request->validate([
            'check_in' => 'required|date|after_or_equal:today',
            'check_out' => 'required|date|after:check_in',
            'guests' => 'required|integer|min:1|max:' . $property->capacity,
            'special_requests' => 'nullable|string',
        ]);

        // Check if property is available for the selected dates
        $checkIn = $validated['check_in'];
        $checkOut = $validated['check_out'];

        $conflictingBookings = $property->bookings()
            ->where(function($query) use ($checkIn, $checkOut) {
                $query->where(function($q) use ($checkIn, $checkOut) {
                    $q->where('check_in', '<=', $checkIn)
                        ->where('check_out', '>=', $checkIn);
                })->orWhere(function($q) use ($checkIn, $checkOut) {
                    $q->where('check_in', '<=', $checkOut)
                        ->where('check_out', '>=', $checkOut);
                })->orWhere(function($q) use ($checkIn, $checkOut) {
                    $q->where('check_in', '>=', $checkIn)
                        ->where('check_out', '<=', $checkOut);
                });
            })
            ->whereIn('status', ['pending', 'confirmed'])
            ->exists();

        if ($conflictingBookings) {
            return back()->withErrors([
                'check_in' => 'The property is not available for the selected dates.'
            ])->withInput();
        }

        // Calculate total price
        $days = (new \DateTime($checkIn))->diff(new \DateTime($checkOut))->days;
        $totalPrice = $property->price_per_night * $days;

        $booking = Booking::create([
            'user_id' => auth()->id(),
            'property_id' => $property->id,
            'check_in' => $checkIn,
            'check_out' => $checkOut,
            'guests' => $validated['guests'],
            'total_price' => $totalPrice,
            'status' => 'pending',
            'special_requests' => $validated['special_requests'],
        ]);

        // Notify property owner about new booking
        Notification::send($property->user, new BookingCreated($booking));

        return redirect()->route('bookings.show', $booking)
            ->with('success', 'Booking created successfully. Waiting for confirmation.');
    }

    public function show(Booking $booking)
    {
        $this->authorize('view', $booking);

        $booking->load(['property.images', 'user', 'property.user']);

        return view('bookings.show', compact('booking'));
    }

    public function update(Request $request, Booking $booking)
    {
        $this->authorize('update', $booking);

        $validated = $request->validate([
            'status' => 'required|in:confirmed,cancelled',
        ]);

        $booking->update([
            'status' => $validated['status']
        ]);

        if ($validated['status'] === 'confirmed') {
            // Send confirmation notification to the guest
            $booking->user->notify(new BookingConfirmed($booking));
        }

        return redirect()->route('bookings.show', $booking)
            ->with('success', 'Booking status updated successfully');
    }

    public function destroy(Booking $booking)
    {
        $this->authorize('delete', $booking);

        $booking->delete();

        return redirect()->route('bookings.index')
            ->with('success', 'Booking deleted successfully');
    }

    // API methods
    public function apiIndex()
    {
        $user = auth()->user();

        if ($user->isOwner() || $user->isAdmin()) {
            $bookings = Booking::whereHas('property', function($query) use ($user) {
                if (!$user->isAdmin()) {
                    $query->where('user_id', $user->id);
                }
            })->with(['property.images', 'user'])->latest()->paginate(10);
        } else {
            $bookings = $user->bookings()->with(['property.images'])->latest()->paginate(10);
        }

        return BookingResource::collection($bookings);
    }

    public function apiShow(Booking $booking)
    {
        $this->authorize('view', $booking);

        $booking->load(['property.images', 'user', 'property.user']);

        return new BookingResource($booking);
    }
}
