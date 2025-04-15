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
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('payment_type'); // credit_card, paypal, bank_transfer, etc.
            $table->string('provider')->nullable(); // visa, mastercard, amex, etc.
            $table->string('account_number')->nullable(); // last 4 digits for credit cards
            $table->string('expiry_date')->nullable();
            $table->string('holder_name')->nullable();
            $table->boolean('is_default')->default(false);
            $table->string('token')->nullable(); // payment gateway token
            $table->json('additional_details')->nullable(); // any additional details
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_methods');
    }
};