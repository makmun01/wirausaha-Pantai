<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Partnership;
use App\Services\PartnershipService;
use App\Services\AuditTrailService;
use Illuminate\Http\Request;

class PartnershipController extends Controller
{
    protected PartnershipService $partnershipService;

    public function __construct(PartnershipService $partnershipService)
    {
        $this->partnershipService = $partnershipService;
    }

    public function index()
    {
        $partnerships = $this->partnershipService->getAllPartnerships();
        return view('admin.partnerships.index', compact('partnerships'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'partner_name' => 'required|string|max:150',
            'partnership_type' => 'required|string',
            'contact_person' => 'required|string|max:100',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date',
        ]);

        $partnership = $this->partnershipService->createPartnership($request->all());
        AuditTrailService::log('PARTNERSHIP_CREATE', 'Partnership', $partnership->id, null, ['partner' => $partnership->partner_name]);

        return back()->with('success', 'Data kemitraan baru berhasil dicatat.');
    }
}
