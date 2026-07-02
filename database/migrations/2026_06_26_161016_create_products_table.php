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
        Schema::create('products', function (Blueprint $table) {

            $table->id();

            $table->string('code')->unique();

            $table->string('barcode')->nullable();

            $table->string('name');

            $table->string('category')->nullable();

            $table->string('brand')->nullable();

            $table->string('model')->nullable();

            $table->decimal('purchase_price', 15, 2)->default(0);

            $table->decimal('sale_price', 15, 2)->default(0);

            $table->integer('vat')->default(20);

            $table->decimal('stock', 15, 2)->default(0);

            $table->decimal('min_stock', 15, 2)->default(0);

            $table->string('unit')->default('Adet');

            $table->boolean('is_active')->default(true);

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};