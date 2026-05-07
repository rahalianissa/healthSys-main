<?php

namespace App\Http\Controllers\Api;

use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;

class SecretaryController extends ApiController
{
    public function stats(): JsonResponse
    {
        $stats = [
            'today_appointments' => Appointment::whereDate('date_time', Carbon::today())->count(),
            'pending_appointments' => Appointment::where('status', 'pending')->count(),
            'total_patients' => Patient::count(),
            'unpaid_invoices' => Invoice::where('status', 'unpaid')->count(),
        ];

        return $this->success($stats);
    }
}
