@extends('layouts.app')

@section('title', 'Modifier la facture')
@section('page-title', 'Modifier la facture')

@section('content')
<div class="row">
    <div class="col-md-6 mx-auto">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-warning text-white">
                <h5 class="mb-0"><i class="fas fa-edit me-2"></i> Modifier la facture</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('invoices.update', $invoice) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Patient <span class="text-danger">*</span></label>
                        <select name="patient_id" class="form-select" required>
                            <option value="">Sélectionner un patient</option>
                            @foreach($patients as $patient)
                                <option value="{{ $patient->id }}" {{ $invoice->patient_id == $patient->id ? 'selected' : '' }}>{{ $patient->user->name }} - {{ $patient->user->phone }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Montant (DT) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" name="amount" class="form-control" value="{{ $invoice->amount }}" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Date d'émission <span class="text-danger">*</span></label>
                            <input type="date" name="issue_date" class="form-control" value="{{ $invoice->issue_date->format('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Date d'échéance <span class="text-danger">*</span></label>
                            <input type="date" name="due_date" class="form-control" value="{{ $invoice->due_date->format('Y-m-d') }}" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="2">{{ $invoice->description }}</textarea>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('invoices.index') }}" class="btn btn-secondary">Annuler</a>
                        <button type="submit" class="btn btn-primary">Mettre à jour</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection