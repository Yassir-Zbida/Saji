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
        Schema::create('sync_logs', function (Blueprint $table) {
            $table->id();
            $table->enum('entity_type', ['product', 'order', 'inventory', 'ticket']);
            $table->unsignedBigInteger('entity_id');
            $table->enum('sync_type', ['online_to_physical', 'physical_to_online']);
            $table->enum('status', ['success', 'failed', 'pending']);
            $table->text('error_message')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sync_logs');
    }
};
