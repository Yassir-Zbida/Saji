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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_id')->unique(); // payment gateway transaction ID
            $table->unsignedBigInteger('order_id')->nullable();
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('set null');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->string('payment_method'); // credit_card, paypal, bank_transfer, etc.
            $table->string('payment_gateway'); // stripe, paypal, etc.
            $table->decimal('amount', 10, 2);
            $table->string('currency')->default('USD');
            $table->string('status'); // pending, completed, failed, refunded
            $table->text('notes')->nullable();
            $table->json('response_data')->nullable(); // payment gateway response
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};