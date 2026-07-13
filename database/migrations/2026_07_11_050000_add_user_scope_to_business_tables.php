<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('id')->constrained()->cascadeOnDelete();
            $table->index('user_id');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('id')->constrained()->cascadeOnDelete();
            $table->index('user_id');
        });

        Schema::table('warehouses', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('id')->constrained()->cascadeOnDelete();
            $table->index('user_id');
        });

        $ownerId = DB::table('users')->orderBy('id')->value('id');

        if ($ownerId !== null) {
            DB::table('customers')->whereNull('user_id')->update(['user_id' => $ownerId]);
            DB::table('products')->whereNull('user_id')->update(['user_id' => $ownerId]);
            DB::table('warehouses')->whereNull('user_id')->update(['user_id' => $ownerId]);
            DB::table('cash_accounts')->whereNull('user_id')->update(['user_id' => $ownerId]);
            DB::table('stock_movements')->whereNull('created_by')->update(['created_by' => $ownerId]);
        }

        Schema::table('customers', function (Blueprint $table) {
            $table->dropUnique(['code']);
            $table->unique(['user_id', 'code']);
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropUnique(['code']);
            $table->unique(['user_id', 'code']);
        });

        Schema::table('warehouses', function (Blueprint $table) {
            $table->dropUnique(['code']);
            $table->unique(['user_id', 'code']);
        });

        Schema::table('cash_accounts', function (Blueprint $table) {
            $table->dropUnique(['code']);
            $table->unique(['user_id', 'code']);
        });
    }

    public function down(): void
    {
        Schema::table('cash_accounts', function (Blueprint $table) {
            $table->dropUnique(['user_id', 'code']);
            $table->unique('code');
        });

        Schema::table('warehouses', function (Blueprint $table) {
            $table->dropUnique(['user_id', 'code']);
            $table->unique('code');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropUnique(['user_id', 'code']);
            $table->unique('code');
        });

        Schema::table('customers', function (Blueprint $table) {
            $table->dropUnique(['user_id', 'code']);
            $table->unique('code');
        });

        Schema::table('warehouses', function (Blueprint $table) {
            $table->dropConstrainedForeignId('user_id');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropConstrainedForeignId('user_id');
        });

        Schema::table('customers', function (Blueprint $table) {
            $table->dropConstrainedForeignId('user_id');
        });
    }
};
