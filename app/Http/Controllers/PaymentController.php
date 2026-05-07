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
        $routeName = auth()->user()->role === 'patient' ? 'patient.invoices.pay' : 'invoices.pay';
        return redirect()->route($routeName, $invoice);
    }

    public function success(Invoice $invoice, Request $request)
    {
        $routeName = auth()->user()->role === 'patient' ? 'patient.invoices.show' : 'invoices.show';
        return redirect()->route($routeName, $invoice)
            ->with('success', '✅ Paiement effectué avec succès');
    }

    public function cancel(Invoice $invoice)
    {
        $routeName = auth()->user()->role === 'patient' ? 'patient.invoices.show' : 'invoices.show';
        return redirect()->route($routeName, $invoice)
            ->with('error', '❌ Paiement annulé');
    }
}