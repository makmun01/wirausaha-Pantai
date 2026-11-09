<?php

namespace App\Services;

use App\Models\Partnership;

class PartnershipService
{
    public function getAllPartnerships()
    {
        return Partnership::latest()->get();
    }

    public function createPartnership(array $data): Partnership
    {
        return Partnership::create([
            'partner_name' => $data['partner_name'],
            'partnership_type' => $data['partnership_type'],
            'contact_person' => $data['contact_person'],
            'phone_number' => $data['phone_number'] ?? null,
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'] ?? null,
            'status' => 'ACTIVE',
            'notes' => $data['notes'] ?? null,
        ]);
    }
}
