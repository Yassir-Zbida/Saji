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
        // Table principale des zones de livraison
        Schema::create('shipping_zones', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('position')->default(0); // pour l'ordre d'affichage
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Table pour les régions incluses dans chaque zone
        Schema::create('shipping_zone_regions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('shipping_zone_id');
            $table->foreign('shipping_zone_id')->references('id')->on('shipping_zones')->onDelete('cascade');
            $table->string('region_type'); // country, state, postcode, continent
            $table->string('region_code'); // code du pays, état, code postal ou continent
            $table->timestamps();
        });

        // Table pour les méthodes de livraison disponibles dans chaque zone
        Schema::create('shipping_methods', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('shipping_zone_id');
            $table->foreign('shipping_zone_id')->references('id')->on('shipping_zones')->onDelete('cascade');
            $table->string('name');
            $table->string('method_type'); // flat_rate, free_shipping, local_pickup, etc.
            $table->decimal('cost', 10, 2)->default(0);
            $table->decimal('minimum_order_amount', 10, 2)->nullable();
            $table->decimal('maximum_order_amount', 10, 2)->nullable();
            $table->boolean('is_taxable')->default(false);
            $table->integer('position')->default(0);
            $table->boolean('is_active')->default(true);
            $table->json('settings')->nullable(); // paramètres spécifiques à la méthode
            $table->timestamps();
        });

        // Table pour les classes de livraison (pour des tarifs différents selon le type de produit)
        Schema::create('shipping_classes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Ajouter une colonne à la table des produits pour la classe de livraison
        Schema::table('products', function (Blueprint $table) {
            $table->unsignedBigInteger('shipping_class_id')->nullable();
            $table->foreign('shipping_class_id')->references('id')->on('shipping_classes')->onDelete('set null');
        });

        // Table pour les tarifs spécifiques aux classes de livraison
        Schema::create('shipping_class_rates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('shipping_method_id');
            $table->foreign('shipping_method_id')->references('id')->on('shipping_methods')->onDelete('cascade');
            $table->unsignedBigInteger('shipping_class_id');
            $table->foreign('shipping_class_id')->references('id')->on('shipping_classes')->onDelete('cascade');
            $table->decimal('cost', 10, 2);
            $table->string('cost_type')->default('fixed'); // fixed, percent, etc.
            $table->timestamps();
            
            // Une classe ne peut avoir qu'un tarif par méthode
            $table->unique(['shipping_method_id', 'shipping_class_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['shipping_class_id']);
            $table->dropColumn('shipping_class_id');
        });
        
        Schema::dropIfExists('shipping_class_rates');
        Schema::dropIfExists('shipping_classes');
        Schema::dropIfExists('shipping_methods');
        Schema::dropIfExists('shipping_zone_regions');
        Schema::dropIfExists('shipping_zones');
    }
};

