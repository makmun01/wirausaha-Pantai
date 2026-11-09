<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\UmkmMerchant;
use App\Services\UmkmService;
use App\Services\AuditTrailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UmkmController extends Controller
{
    protected UmkmService $umkmService;

    public function __construct(UmkmService $umkmService)
    {
        $this->umkmService = $umkmService;
    }

    public function index()
    {
        $merchants = $this->umkmService->getAllMerchants();
        return view('admin.umkm.index', compact('merchants'));
    }

    public function verify(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:VERIFIED,REJECTED,SUSPENDED',
            'reason' => 'nullable|string',
        ]);

        $this->umkmService->updateVerificationStatus($id, $request->status, $request->reason);
        AuditTrailService::log('UMKM_VERIFICATION', 'UmkmMerchant', $id, null, ['status' => $request->status]);

        return back()->with('success', "Status verifikasi UMKM berhasil diperbarui menjadi {$request->status}!");
    }
}
