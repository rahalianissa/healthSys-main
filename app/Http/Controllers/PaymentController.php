<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Rediriger vers la page de paiement manuel
    public function checkout(Invoice $invoice)
    {
        return redirect()->route('invoices.pay', $invoice);
    }

    public function success(Invoice $invoice, Request $request)
    {
        return redirect()->route('invoices.show', $invoice)
            ->with('success', '✅ Paiement effectué avec succès');
    }

    public function cancel(Invoice $invoice)
    {
        return redirect()->route('invoices.show', $invoice)
            ->with('error', '❌ Paiement annulé');
    }
}