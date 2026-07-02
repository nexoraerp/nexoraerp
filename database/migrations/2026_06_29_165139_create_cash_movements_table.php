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
        Schema::create('cash_movements', function (Blueprint $table) {

            $table->id();

            $table->foreignId('cash_account_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->date('movement_date');

            $table->string('type');

            $table->nullableMorphs('reference');

            $table->decimal('debit', 15, 2)->default(0);

            $table->decimal('credit', 15, 2)->default(0);

            $table->text('description')->nullable();

            $table->foreignId('user_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cash_movements');
    }
};