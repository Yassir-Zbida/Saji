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
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('type'); // percentage, fixed_amount, free_shipping
            $table->decimal('value', 10, 2)->nullable(); // discount value
            $table->decimal('minimum_spend', 10, 2)->nullable();
            $table->decimal('maximum_discount', 10, 2)->nullable();
            $table->integer('usage_limit')->nullable(); // max number of times coupon can be used
            $table->integer('usage_count')->default(0); // number of times coupon has been used
            $table->boolean('individual_use')->default(false); // if true, cannot be used with other coupons
            $table->text('product_ids')->nullable(); // comma-separated list of product IDs
            $table->text('excluded_product_ids')->nullable(); // comma-separated list of excluded product IDs
            $table->text('category_ids')->nullable(); // comma-separated list of category IDs
            $table->text('excluded_category_ids')->nullable(); // comma-separated list of excluded category IDs
            $table->boolean('exclude_sale_items')->default(false);
            $table->text('email_restrictions')->nullable(); // comma-separated list of emails
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();
            $table->boolean('is_active')->default(true);
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};