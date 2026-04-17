<?php
// app/Models/Invoice.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'invoice_number', 'patient_id', 'consultation_id', 'amount',
        'paid_amount', 'status', 'issue_date', 'due_date', 'description',
        // NEW FIELDS
        'cnam_amount', 'mutuelle_amount', 'patient_amount',
        'cnam_reference', 'mutuelle_reference',
        'cnam_paid', 'mutuelle_paid', 'patient_paid',
        'cnam_claim_date', 'mutuelle_claim_date'
    ];

    protected $casts = [
        'issue_date' => 'date',
        'due_date' => 'date',
        'cnam_claim_date' => 'date',
        'mutuelle_claim_date' => 'date',
        'cnam_paid' => 'boolean',
        'mutuelle_paid' => 'boolean',
        'patient_paid' => 'boolean',
    ];

    // ==================== RELATIONS ====================
    
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function consultation()
    {
        return $this->belongsTo(Consultation::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    // ==================== ACCESSORS (GETTERS) ====================
    
    /**
     * Get total paid amount including insurance payments
     */
    public function getTotalPaidAttribute(): float
    {
        $paid = 0;
        if ($this->cnam_paid) $paid += $this->cnam_amount;
        if ($this->mutuelle_paid) $paid += $this->mutuelle_amount;
        if ($this->patient_paid) $paid += $this->patient_amount;
        return round($paid, 2);
    }

    /**
     * Get remaining amount to collect from each entity
     */
    public function getRemainingBreakdownAttribute(): array
    {
        return [
            'cnam' => $this->cnam_paid ? 0 : $this->cnam_amount,
            'mutuelle' => $this->mutuelle_paid ? 0 : $this->mutuelle_amount,
            'patient' => $this->patient_paid ? 0 : $this->patient_amount,
            'total' => ($this->cnam_paid ? 0 : $this->cnam_amount) + 
                       ($this->mutuelle_paid ? 0 : $this->mutuelle_amount) + 
                       ($this->patient_paid ? 0 : $this->patient_amount)
        ];
    }

    /**
     * ✅ FIXED: Get remaining amount (total - total_paid)
     */
    public function getRemainingAttribute(): float
    {
        return round($this->amount - $this->total_paid, 2);
    }

    /**
     * Get payment status for each entity
     */
    public function getPaymentStatusAttribute(): array
    {
        return [
            'cnam' => $this->cnam_paid ? 'paid' : ($this->cnam_amount > 0 ? 'pending' : 'none'),
            'mutuelle' => $this->mutuelle_paid ? 'paid' : ($this->mutuelle_amount > 0 ? 'pending' : 'none'),
            'patient' => $this->patient_paid ? 'paid' : ($this->patient_amount > 0 ? 'pending' : 'none'),
        ];
    }

    /**
     * Get formatted insurance breakdown for display
     */
    public function getInsuranceBreakdownAttribute(): array
    {
        $total = $this->amount;
        
        return [
            'cnam' => [
                'amount' => $this->cnam_amount,
                'percentage' => $total > 0 ? round(($this->cnam_amount / $total) * 100, 1) : 0,
                'paid' => $this->cnam_paid,
                'reference' => $this->cnam_reference,
                'claim_date' => $this->cnam_claim_date,
            ],
            'mutuelle' => [
                'amount' => $this->mutuelle_amount,
                'percentage' => $total > 0 ? round(($this->mutuelle_amount / $total) * 100, 1) : 0,
                'paid' => $this->mutuelle_paid,
                'reference' => $this->mutuelle_reference,
                'claim_date' => $this->mutuelle_claim_date,
            ],
            'patient' => [
                'amount' => $this->patient_amount,
                'percentage' => $total > 0 ? round(($this->patient_amount / $total) * 100, 1) : 0,
                'paid' => $this->patient_paid,
            ],
        ];
    }

    /**
     * Check if invoice is fully paid by all parties
     */
    public function getIsFullyPaidAttribute(): bool
    {
        return $this->total_paid >= $this->amount;
    }

    /**
     * Get progress percentage for each insurance type
     */
    public function getPaymentProgressAttribute(): array
    {
        $total = $this->amount;
        
        if ($total <= 0) {
            return ['cnam' => 0, 'mutuelle' => 0, 'patient' => 0];
        }
        
        return [
            'cnam' => round(($this->cnam_paid ? $this->cnam_amount : 0) / $total * 100, 1),
            'mutuelle' => round(($this->mutuelle_paid ? $this->mutuelle_amount : 0) / $total * 100, 1),
            'patient' => round(($this->patient_paid ? $this->patient_amount : 0) / $total * 100, 1),
        ];
    }

    // ==================== MUTATORS (SETTERS) ====================
    
    /**
     * Synchronize paid_amount with total_paid
     */
    public function syncPaidAmount(): void
    {
        $this->paid_amount = $this->total_paid;
        $this->updateOverallStatus();
        $this->saveQuietly();
    }

    /**
     * Mark CNAM as paid
     */
    public function markCnamPaid(string $reference = null): void
    {
        $this->cnam_paid = true;
        if ($reference) {
            $this->cnam_reference = $reference;
        }
        $this->cnam_claim_date = now();
        
        // Update total paid amount
        $this->paid_amount = $this->total_paid;
        
        // Update overall status
        $this->updateOverallStatus();
        
        $this->save();
    }

    /**
     * Mark Mutuelle as paid
     */
    public function markMutuellePaid(string $reference = null): void
    {
        $this->mutuelle_paid = true;
        if ($reference) {
            $this->mutuelle_reference = $reference;
        }
        $this->mutuelle_claim_date = now();
        
        // Update total paid amount
        $this->paid_amount = $this->total_paid;
        
        // Update overall status
        $this->updateOverallStatus();
        
        $this->save();
    }

    /**
     * Mark Patient as paid
     */
    public function markPatientPaid(): void
    {
        $this->patient_paid = true;
        
        // Update total paid amount
        $this->paid_amount = $this->total_paid;
        
        // Update overall status
        $this->updateOverallStatus();
        
        $this->save();
    }

    /**
     * Mark all insurance as paid (for full payment)
     */
    public function markAllPaid(string $cnamReference = null, string $mutuelleReference = null): void
    {
        if ($this->cnam_amount > 0) {
            $this->markCnamPaid($cnamReference);
        }
        if ($this->mutuelle_amount > 0) {
            $this->markMutuellePaid($mutuelleReference);
        }
        if ($this->patient_amount > 0) {
            $this->markPatientPaid();
        }
    }

    /**
     * Reset insurance payment status
     */
    public function resetInsurancePayments(): void
    {
        $this->cnam_paid = false;
        $this->mutuelle_paid = false;
        $this->patient_paid = false;
        $this->cnam_reference = null;
        $this->mutuelle_reference = null;
        $this->cnam_claim_date = null;
        $this->mutuelle_claim_date = null;
        $this->paid_amount = 0;
        $this->status = 'pending';
        $this->save();
    }

    // ==================== PRIVATE METHODS ====================
    
    /**
     * Update overall invoice status based on payment status
     */
    protected function updateOverallStatus(): void
    {
        $totalRemaining = $this->cnam_amount + $this->mutuelle_amount + $this->patient_amount;
        $totalPaid = $this->total_paid;
        
        if ($totalPaid >= $totalRemaining && $totalRemaining > 0) {
            $this->status = 'paid';
        } elseif ($totalPaid > 0) {
            $this->status = 'partially_paid';
        } else {
            $this->status = 'pending';
        }
    }

    // ==================== SCOPE QUERIES ====================
    
    /**
     * Scope for pending CNAM claims
     */
    public function scopePendingCnamClaims($query)
    {
        return $query->where('cnam_amount', '>', 0)
                     ->where('cnam_paid', false);
    }

    /**
     * Scope for pending Mutuelle claims
     */
    public function scopePendingMutuelleClaims($query)
    {
        return $query->where('mutuelle_amount', '>', 0)
                     ->where('mutuelle_paid', false);
    }

    /**
     * Scope for pending patient payments
     */
    public function scopePendingPatientPayments($query)
    {
        return $query->where('patient_amount', '>', 0)
                     ->where('patient_paid', false);
    }

    /**
     * Scope for fully paid invoices
     */
    public function scopeFullyPaid($query)
    {
        return $query->where('status', 'paid');
    }

    /**
     * Scope for overdue invoices
     */
    public function scopeOverdue($query)
    {
        return $query->where('due_date', '<', now())
                     ->where('status', '!=', 'paid');
    }
}