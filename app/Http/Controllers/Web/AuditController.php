<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Services\AuditTrailService;

class AuditController extends Controller
{
    protected AuditTrailService $auditService;

    public function __construct(AuditTrailService $auditService)
    {
        $this->auditService = $auditService;
    }

    public function index()
    {
        $logs = AuditLog::with('user')->latest('id')->paginate(25);
        return view('admin.audit.index', compact('logs'));
    }
}
