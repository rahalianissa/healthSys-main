<?php

namespace App\Http\Controllers\Api;

use App\Models\Invoice;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class InvoiceController extends ApiController
{
    public function index(): JsonResponse
    {
        $invoices = Invoice::with(['patient.user'])->get();
        return $this->success($invoices);
    }

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'patient_id' => 'required|exists:patients,id',
            'amount' => 'required|numeric',
            'due_date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return $this->error('Validation Error', 422, $validator->errors());
        }

        $invoice = Invoice::create([
            'patient_id' => $request->patient_id,
            'amount' => $request->amount,
            'due_date' => $request->due_date,
            'status' => 'unpaid',
            'invoice_number' => 'INV-' . time(),
        ]);

        return $this->success($invoice, 'Facture créée avec succès', 201);
    }

    public function show(int $id): JsonResponse
    {
        $invoice = Invoice::with(['patient.user', 'payments'])->find($id);
        if (!$invoice) {
            return $this->error('Facture non trouvée', 404);
        }
        return $this->success($invoice);
    }
}
