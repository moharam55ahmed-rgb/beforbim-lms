<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('order_id')->constrained('orders')->restrictOnDelete();
            $table->string('payment_method', 40); // CREDIT_CARD, MADA, APPLE_PAY, TABBY, TAMARA, BANK_TRANSFER, FAWRY
            $table->string('gateway', 40); // stripe, paymob, tabby, tamara, manual_bank
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('SAR');
            $table->string('status', 40)->default('PENDING')->index(); // PENDING, SUCCESS, FAILED, REQUIRES_ADMIN_VERIFICATION, REFUNDED
            $table->string('receipt_attachment_url', 255)->nullable();
            $table->foreignId('verified_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('verified_at')->nullable();
            $table->text('verification_notes')->nullable();
            $table->json('gateway_payload')->nullable();
            $table->timestamps();

            $table->index(['order_id', 'status']);
        });

        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('payment_id')->constrained('payments')->restrictOnDelete();
            $table->string('transaction_type', 30); // CHARGE, REFUND, CHARGEBACK, PAYOUT
            $table->string('gateway_reference', 191)->nullable()->index();
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('SAR');
            $table->decimal('fee_amount', 10, 2)->default(0.00);
            $table->decimal('net_amount', 10, 2);
            $table->timestamp('settled_at');
            $table->timestamps();

            $table->index(['payment_id', 'transaction_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
        Schema::dropIfExists('payments');
    }
};
