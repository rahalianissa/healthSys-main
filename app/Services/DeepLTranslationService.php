<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            // إضافة الأعمدة المفقودة فقط
            if (!Schema::hasColumn('payments', 'invoice_id')) {
                $table->foreignId('invoice_id')->constrained()->onDelete('cascade');
            }
            if (!Schema::hasColumn('payments', 'amount')) {
                $table->decimal('amount', 10, 2);
            }
            if (!Schema::hasColumn('payments', 'payment_method')) {
                $table->string('payment_method');
            }
            if (!Schema::hasColumn('payments', 'payment_date')) {
                $table->date('payment_date');
            }
            if (!Schema::hasColumn('payments', 'transaction_id')) {
                $table->string('transaction_id')->nullable();
            }
            if (!Schema::hasColumn('payments', 'stripe_payment_intent')) {
                $table->string('stripe_payment_intent')->nullable();
            }
            if (!Schema::hasColumn('payments', 'status')) {
                $table->string('status')->default('pending');
            }
            if (!Schema::hasColumn('payments', 'notes')) {
                $table->text('notes')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn([
                'invoice_id', 'amount', 'payment_method', 'payment_date',
                'transaction_id', 'stripe_payment_intent', 'status', 'notes'
            ]);
        });
    }
};