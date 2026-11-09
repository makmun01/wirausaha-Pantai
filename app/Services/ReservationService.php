<?php

namespace App\Services;

use App\Models\Reservation;
use App\Models\Destination;
use Illuminate\Support\Str;

class ReservationService
{
    public function createReservation(array $data, int $userId): Reservation
    {
        $destination = Destination::firstOrFail();
        $totalPrice = $destination->ticket_price_demo * $data['visitor_count'];

        return Reservation::create([
            'booking_code' => 'RSV-JMI-' . date('Ymd') . '-' . strtoupper(Str::random(4)),
            'user_id' => $userId,
            'destination_id' => $destination->id,
            'visit_date' => $data['visit_date'],
            'visitor_count' => $data['visitor_count'],
            'package_name' => $data['package_name'] ?? 'Tiket Masuk Standar',
            'total_price' => $totalPrice,
            'status' => 'CONFIRMED',
        ]);
    }

    public function checkIn(string $bookingCode): Reservation
    {
        $reservation = Reservation::where('booking_code', $bookingCode)->firstOrFail();
        $reservation->status = 'ATTENDED';
        $reservation->check_in_time = now();
        $reservation->save();

        return $reservation;
    }
}
