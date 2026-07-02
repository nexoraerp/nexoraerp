<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sales', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Satış Bilgileri
            |--------------------------------------------------------------------------
            */

            $table->string('sale_no')->unique();

            $table->foreignId('customer_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->date('sale_date');

            /*
            |--------------------------------------------------------------------------
            | Vade
            |--------------------------------------------------------------------------
            */

            $table->date('due_date')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Ödeme Tipi
            |--------------------------------------------------------------------------
            */

            $table->enum('payment_type', [
                'Cash',
                'Credit',
                'Card',
                'Bank',
                'Mixed',
            ])->default('Credit');

            /*
            |--------------------------------------------------------------------------
            | Tutarlar
            |--------------------------------------------------------------------------
            */

            $table->decimal('subtotal', 15, 2)->default(0);

            $table->decimal('discount', 15, 2)->default(0);

            $table->decimal('vat', 15, 2)->default(0);

            $table->decimal('grand_total', 15, 2)->default(0);

            /*
            |--------------------------------------------------------------------------
            | Tahsilat Durumu
            |--------------------------------------------------------------------------
            */

            $table->enum('payment_status', [
                'Unpaid',
                'Partial',
                'Paid',
                'Refunded',
            ])->default('Unpaid');

            $table->decimal('paid_total', 15, 2)
                ->default(0);

            $table->decimal('remaining_total', 15, 2)
                ->default(0);

            /*
            |--------------------------------------------------------------------------
            | ERP Durumu
            |--------------------------------------------------------------------------
            */

            $table->enum('status', [
                'Draft',
                'Completed',
                'Cancelled',
            ])->default('Draft');

            /*
            |--------------------------------------------------------------------------
            | Tamamlama Bilgileri
            |--------------------------------------------------------------------------
            */

            $table->timestamp('completed_at')
                ->nullable();

            $table->foreignId('completed_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            /*
            |--------------------------------------------------------------------------
            | İptal Bilgileri
            |--------------------------------------------------------------------------
            */

            $table->timestamp('cancelled_at')
                ->nullable();

            $table->foreignId('cancelled_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->text('cancel_reason')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Notlar
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

            /*
            |--------------------------------------------------------------------------
            | Tarihler
            |--------------------------------------------------------------------------
            */

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};