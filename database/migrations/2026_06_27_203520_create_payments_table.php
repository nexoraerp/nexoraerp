<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Tahsilat Bilgileri
            |--------------------------------------------------------------------------
            */

            $table->string('payment_no')->unique();

            $table->foreignId('customer_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->date('payment_date');

            $table->decimal('amount', 15, 2);

            /*
            |--------------------------------------------------------------------------
            | Ödeme Tipi
            |--------------------------------------------------------------------------
            |
            | Cash
            | Bank
            | Card
            | Mixed
            |
            */

            $table->enum('payment_method', [
                'Cash',
                'Bank',
                'Card',
                'Mixed',
            ])->default('Cash');

            /*
            |--------------------------------------------------------------------------
            | Referans
            |--------------------------------------------------------------------------
            */

            $table->string('reference_no')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Açıklama
            |--------------------------------------------------------------------------
            */

            $table->text('notes')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Oluşturan Kullanıcı
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
        Schema::dropIfExists('payments');
    }
};