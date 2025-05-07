<?php

namespace App\Notifications;
use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class BookingConfirmed extends Notification implements ShouldQueue
{
    use Queueable;

    protected $booking;

    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Booking Confirmed')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Your booking for ' . $this->booking->property->title . ' has been confirmed.')
            ->line('Check-in: ' . $this->booking->check_in->format('Y-m-d'))
            ->line('Check-out: ' . $this->booking->check_out->format('Y-m-d'))
            ->line('Guests: ' . $this->booking->guests)
            ->line('Total: $' . number_format($this->booking->total_price, 2))
            ->action('View Booking Details', url('/bookings/' . $this->booking->id))
            ->line('Thank you for choosing StayFinder!');
    }

    public function toArray($notifiable)
    {
        return [
            'booking_id' => $this->booking->id,
            'message' => 'Your booking for ' . $this->booking->property->title . ' has been confirmed',
        ];
    }
}
