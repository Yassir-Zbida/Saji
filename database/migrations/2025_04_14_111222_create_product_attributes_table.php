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
        Schema::create('product_attributes', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., "Size", "Color", "Material"
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('type')->default('select'); // select, radio, checkbox, text, etc.
            $table->boolean('is_required')->default(false);
            $table->boolean('is_global')->default(true); // if true, attribute applies to all products
            $table->boolean('is_filterable')->default(false); // if true, can be used for filtering products
            $table->boolean('is_visible_on_product_page')->default(true);
            $table->integer('position')->default(0); // for ordering attributes
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_attributes');
    }
};