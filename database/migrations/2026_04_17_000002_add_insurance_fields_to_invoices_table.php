<?php
// database/migrations/2026_04_17_000002_add_insurance_fields_to_invoices_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            // Insurance breakdown amounts
            if (!Schema::hasColumn('invoices', 'cnam_amount')) {
                $table->decimal('cnam_amount', 10, 2)->default(0)->after('amount');
            }
            if (!Schema::hasColumn('invoices', 'mutuelle_amount')) {
                $table->decimal('mutuelle_amount', 10, 2)->default(0)->after('cnam_amount');
            }
            if (!Schema::hasColumn('invoices', 'patient_amount')) {
                $table->decimal('patient_amount', 10, 2)->default(0)->after('mutuelle_amount');
            }
            
            // Reference numbers
            if (!Schema::hasColumn('invoices', 'cnam_reference')) {
                $table->string('cnam_reference')->nullable()->after('patient_amount');
            }
            if (!Schema::hasColumn('invoices', 'mutuelle_reference')) {
                $table->string('mutuelle_reference')->nullable()->after('cnam_reference');
            }
            
            // Payment status per entity
            if (!Schema::hasColumn('invoices', 'cnam_paid')) {
                $table->boolean('cnam_paid')->default(false)->after('mutuelle_reference');
            }
            if (!Schema::hasColumn('invoices', 'mutuelle_paid')) {
                $table->boolean('mutuelle_paid')->default(false)->after('cnam_paid');
            }
            if (!Schema::hasColumn('invoices', 'patient_paid')) {
                $table->boolean('patient_paid')->default(false)->after('mutuelle_paid');
            }
            
            // Claim submission dates
            if (!Schema::hasColumn('invoices', 'cnam_claim_date')) {
                $table->date('cnam_claim_date')->nullable()->after('patient_paid');
            }
            if (!Schema::hasColumn('invoices', 'mutuelle_claim_date')) {
                $table->date('mutuelle_claim_date')->nullable()->after('cnam_claim_date');
            }
        });
    }

    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn([
                'cnam_amount', 'mutuelle_amount', 'patient_amount',
                'cnam_reference', 'mutuelle_reference',
                'cnam_paid', 'mutuelle_paid', 'patient_paid',
                'cnam_claim_date', 'mutuelle_claim_date'
            ]);
        });
    }
};