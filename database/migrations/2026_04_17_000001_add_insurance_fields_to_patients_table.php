<?php
// database/migrations/2026_04_17_000001_add_insurance_fields_to_patients_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            // CNAM (National Health Insurance)
            if (!Schema::hasColumn('patients', 'has_cnam')) {
                $table->boolean('has_cnam')->default(false)->after('blood_type');
            }
            if (!Schema::hasColumn('patients', 'cnam_number')) {
                $table->string('cnam_number')->nullable()->after('has_cnam');
            }
            if (!Schema::hasColumn('patients', 'cnam_expiry_date')) {
                $table->date('cnam_expiry_date')->nullable()->after('cnam_number');
            }
            
            // Mutuelle (Complementary Insurance)
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

    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->dropColumn([
                'has_cnam', 'cnam_number', 'cnam_expiry_date',
                'has_mutuelle', 'mutuelle_number', 'mutuelle_company',
                'mutuelle_rate', 'mutuelle_expiry_date'
            ]);
        });
    }
};