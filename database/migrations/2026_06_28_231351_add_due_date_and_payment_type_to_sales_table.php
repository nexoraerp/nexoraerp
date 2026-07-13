<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('sales', 'due_date')) {
            Schema::table('sales', function (Blueprint $table) {

                $table->date('due_date')
                    ->nullable()
                    ->after('sale_date');

            });
        }

        if (! Schema::hasColumn('sales', 'payment_type')) {
            Schema::table('sales', function (Blueprint $table) {

                $table->enum('payment_type', [
                    'Cash',
                    'Credit',
                    'Card',
                    'Bank',
                    'Mixed',
                ])->default('Credit')->after('due_date');

            });
        }
    }

    public function down(): void
    {
        //
    }
};
