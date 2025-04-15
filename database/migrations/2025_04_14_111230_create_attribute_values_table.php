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
        Schema::create('attribute_values', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('attribute_id');
            $table->foreign('attribute_id')->references('id')->on('product_attributes')->onDelete('cascade');
            $table->string('value'); // e.g., "Small", "Red", "Cotton"
            $table->string('slug')->unique();
            $table->string('color_code')->nullable(); // for color attributes
            $table->string('image')->nullable(); // for image swatch
            $table->integer('position')->default(0); // for ordering values
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attribute_values');
    }
};