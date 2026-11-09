<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Services\ReservationService;
use App\Services\AuditTrailService;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    protected ReservationService $reservationService;

    public function __construct(ReservationService $reservationService)
    {
        $this->reservationService = $reservationService;
    }

    public function index()
    {
        $reservations = Reservation::with(['user', 'destination'])->latest('visit_date')->paginate(15);
        return view('admin.reservations.index', compact('reservations'));
    }

    public function validateCheckIn(Request $request)
    {
        $request->validate(['booking_code' => 'required|string']);

        try {
            $reservation = $this->reservationService->checkIn($request->booking_code);
            AuditTrailService::log('RESERVATION_CHECKIN', 'Reservation', $reservation->id, ['status' => 'CONFIRMED'], ['status' => 'ATTENDED']);
            return back()->with('success', "Check-in Berhasil untuk Kode Booking: {$reservation->booking_code} ({$reservation->visitor_count} Pengunjung)!");
        } catch (\Exception $e) {
            return back()->with('error', "Kode booking tidak valid atau tidak ditemukan.");
        }
    }
}
