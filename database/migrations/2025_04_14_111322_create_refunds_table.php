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
        Schema::create('refunds', function (Blueprint $table) {
            $table->id();
            $table->string('refund_number')->unique();
            $table->unsignedBigInteger('order_id');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('transaction_id')->nullable();
            $table->foreign('transaction_id')->references('id')->on('transactions')->onDelete('set null');
            $table->decimal('amount', 10, 2);
            $table->string('reason');
            $table->text('notes')->nullable();
            $table->string('status')->default('pending'); // pending, approved, rejected, completed
            $table->unsignedBigInteger('processed_by')->nullable(); // admin user ID
            $table->foreign('processed_by')->references('id')->on('users')->onDelete('set null');
            $table->timestamp('processed_at')->nullable();
            $table->json('items')->nullable(); // JSON array of refunded items
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('refunds');
    }
};