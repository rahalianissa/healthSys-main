<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            // Colonnes pour la confirmation
            if (!Schema::hasColumn('appointments', 'confirmed_by')) {
                $table->unsignedBigInteger('confirmed_by')->nullable()->after('status');
                $table->foreign('confirmed_by')->references('id')->on('users')->onDelete('set null');
            }
            
            if (!Schema::hasColumn('appointments', 'confirmed_at')) {
                $table->timestamp('confirmed_at')->nullable()->after('confirmed_by');
            }
            
            // Colonnes pour l'annulation
            if (!Schema::hasColumn('appointments', 'cancelled_by')) {
                $table->unsignedBigInteger('cancelled_by')->nullable()->after('confirmed_at');
                $table->foreign('cancelled_by')->references('id')->on('users')->onDelete('set null');
            }
            
            if (!Schema::hasColumn('appointments', 'cancelled_at')) {
                $table->timestamp('cancelled_at')->nullable()->after('cancelled_by');
            }
            
            if (!Schema::hasColumn('appointments', 'cancellation_reason')) {
                $table->text('cancellation_reason')->nullable()->after('cancelled_at');
            }
            
            // Colonne created_by (créateur du rendez-vous)
            if (!Schema::hasColumn('appointments', 'created_by')) {
                $table->unsignedBigInteger('created_by')->nullable()->after('id');
                $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            }
        });
    }

    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropForeign(['confirmed_by']);
            $table->dropForeign(['cancelled_by']);
            $table->dropForeign(['created_by']);
            
            $table->dropColumn([
                'confirmed_by', 'confirmed_at',
                'cancelled_by', 'cancelled_at', 'cancellation_reason',
                'created_by'
            ]);
        });
    }
};