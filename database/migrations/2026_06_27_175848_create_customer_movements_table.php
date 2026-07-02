<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customer_movements', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Cari
            |--------------------------------------------------------------------------
            */

            $table->foreignId('customer_id')
                ->constrained()
                ->cascadeOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Hareket Bilgisi
            |--------------------------------------------------------------------------
            */

            $table->date('movement_date');

            $table->enum('type', [
                'SALE',
                'PAYMENT',
                'RETURN',
                'OPENING',
                'DISCOUNT',
                'ADJUSTMENT',
            ]);

            /*
            |--------------------------------------------------------------------------
            | Referans
            |--------------------------------------------------------------------------
            */

            $table->nullableMorphs('reference');

            /*
            |--------------------------------------------------------------------------
            | Tutarlar
            |--------------------------------------------------------------------------
            */

            $table->decimal('debit', 15, 2)->default(0);

            $table->decimal('credit', 15, 2)->default(0);

            /*
            |--------------------------------------------------------------------------
            | Vade
            |--------------------------------------------------------------------------
            */

            $table->date('due_date')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Açıklama
            |--------------------------------------------------------------------------
            */

            $table->text('description')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Oluşturan
            |--------------------------------------------------------------------------
            */

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_movements');
    }
};