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
        Schema::create('product_variations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->string('sku')->unique();
            $table->decimal('price', 10, 2)->nullable(); // if null, use parent product price
            $table->decimal('sale_price', 10, 2)->nullable();
            $table->integer('quantity')->default(0);
            $table->string('image')->nullable();
            $table->boolean('is_active')->default(true);
            $table->json('attribute_values'); // JSON array of attribute value IDs
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_variations');
    }
};