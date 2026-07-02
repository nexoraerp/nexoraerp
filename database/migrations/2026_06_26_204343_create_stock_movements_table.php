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
        Schema::create('stock_movements', function (Blueprint $table) {

            $table->id();

            // Ürün
            $table->foreignId('product_id')
                ->constrained()
                ->cascadeOnDelete();

            // Depo
            $table->foreignId('warehouse_id')
                ->constrained()
                ->cascadeOnDelete();

            // Hareket Tipi
            $table->enum('type', [
                'IN',
                'OUT',
                'TRANSFER',
                'ADJUSTMENT',
                'RETURN',
            ]);

            // Miktar
            $table->decimal('quantity', 15, 2);

            // Birim Maliyet
            $table->decimal('unit_cost', 15, 2)
                ->nullable();

            // Hareketin geldiği modül
            $table->string('reference_type')
                ->nullable();

            // İlgili kayıt ID'si
            $table->unsignedBigInteger('reference_id')
                ->nullable();

            // Açıklama
            $table->text('description')
                ->nullable();

            // İşlemi yapan kullanıcı
            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_movements');
    }
};