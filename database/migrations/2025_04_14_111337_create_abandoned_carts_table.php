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
        Schema::create('abandoned_carts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable(); // can be null for guest users
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->string('session_id')->nullable(); // for guest users
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->json('cart_items'); // JSON array of cart items
            $table->decimal('total_amount', 10, 2);
            $table->string('status')->default('abandoned'); // abandoned, recovered, expired
            $table->timestamp('last_activity')->nullable();
            $table->timestamp('reminder_sent_at')->nullable(); // when reminder email was sent
            $table->unsignedBigInteger('converted_to_order_id')->nullable(); // if cart was recovered
            $table->foreign('converted_to_order_id')->references('id')->on('orders')->onDelete('set null');
            $table->string('coupon_code')->nullable();
            $table->json('additional_data')->nullable(); // any additional data
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('abandoned_carts');
    }
};