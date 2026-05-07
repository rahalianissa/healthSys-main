<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Fix patients table
        if (Schema::hasTable('patients')) {
            Schema::table('patients', function (Blueprint $table) {
                if (!Schema::hasColumn('patients', 'has_cnam')) {
                    $table->boolean('has_cnam')->default(false)->after('blood_type');
                }
                if (!Schema::hasColumn('patients', 'cnam_number')) {
                    $table->string('cnam_number')->nullable()->after('has_cnam');
                }
                if (!Schema::hasColumn('patients', 'cnam_expiry_date')) {
                    $table->date('cnam_expiry_date')->nullable()->after('cnam_number');
                }
                if (!Schema::hasColumn('patients', 'has_mutuelle')) {
                    $table->boolean('has_mutuelle')->default(false)->after('cnam_expiry_date');
                }
                if (!Schema::hasColumn('patients', 'mutuelle_number')) {
                    $table->string('mutuelle_number')->nullable()->after('has_mutuelle');
                }
                if (!Schema::hasColumn('patients', 'mutuelle_company')) {
                    $table->string('mutuelle_company')->nullable()->after('mutuelle_number');
                }
                if (!Schema::hasColumn('patients', 'mutuelle_rate')) {
                    $table->decimal('mutuelle_rate', 5, 2)->default(0)->after('mutuelle_company');
                }
                if (!Schema::hasColumn('patients', 'mutuelle_expiry_date')) {
                    $table->date('mutuelle_expiry_date')->nullable()->after('mutuelle_rate');
                }
            });
        }

        // Fix invoices table
        if (Schema::hasTable('invoices')) {
            Schema::table('invoices', function (Blueprint $table) {
                if (!Schema::hasColumn('invoices', 'cnam_amount')) {
                    $table->decimal('cnam_amount', 10, 2)->default(0)->after('amount');
                }
                if (!Schema::hasColumn('invoices', 'mutuelle_amount')) {
                    $table->decimal('mutuelle_amount', 10, 2)->default(0)->after('cnam_amount');
                }
                if (!Schema::hasColumn('invoices', 'patient_amount')) {
                    $table->decimal('patient_amount', 10, 2)->default(0)->after('mutuelle_amount');
                }
                if (!Schema::hasColumn('invoices', 'cnam_reference')) {
                    $table->string('cnam_reference')->nullable()->after('patient_amount');
                }
                if (!Schema::hasColumn('invoices', 'mutuelle_reference')) {
                    $table->string('mutuelle_reference')->nullable()->after('cnam_reference');
                }
                if (!Schema::hasColumn('invoices', 'cnam_paid')) {
                    $table->boolean('cnam_paid')->default(false)->after('mutuelle_reference');
                }
                if (!Schema::hasColumn('invoices', 'mutuelle_paid')) {
                    $table->boolean('mutuelle_paid')->default(false)->after('cnam_paid');
                }
                if (!Schema::hasColumn('invoices', 'patient_paid')) {
                    $table->boolean('patient_paid')->default(false)->after('mutuelle_paid');
                }
                if (!Schema::hasColumn('invoices', 'cnam_claim_date')) {
                    $table->date('cnam_claim_date')->nullable()->after('patient_paid');
                }
                if (!Schema::hasColumn('invoices', 'mutuelle_claim_date')) {
                    $table->date('mutuelle_claim_date')->nullable()->after('cnam_claim_date');
                }
            });
        }

        // Fix payments table
        if (Schema::hasTable('payments')) {
            Schema::table('payments', function (Blueprint $table) {
                if (!Schema::hasColumn('payments', 'invoice_id')) {
                    $table->foreignId('invoice_id')->nullable()->constrained()->onDelete('set null');
                }
                if (!Schema::hasColumn('payments', 'amount')) {
                    $table->decimal('amount', 10, 2)->default(0);
                }
                if (!Schema::hasColumn('payments', 'payment_method')) {
                    $table->string('payment_method')->nullable();
                }
                if (!Schema::hasColumn('payments', 'payment_date')) {
                    $table->timestamp('payment_date')->nullable();
                }
                if (!Schema::hasColumn('payments', 'transaction_id')) {
                    $table->string('transaction_id')->nullable();
                }
                if (!Schema::hasColumn('payments', 'status')) {
                    $table->string('status')->default('pending');
                }
                if (!Schema::hasColumn('payments', 'notes')) {
                    $table->text('notes')->nullable();
                }
            });
        }

        // Fix consultations table
        if (Schema::hasTable('consultations')) {
            Schema::table('consultations', function (Blueprint $table) {
                if (!Schema::hasColumn('consultations', 'appointment_id')) {
                    $table->foreignId('appointment_id')->nullable()->constrained()->onDelete('set null');
                }
            });
        }
    }

    public function down(): void
    {
        // Les colonnes seront supprimées automatiquement par les migrations originales
    }
};