<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('company_name')->nullable()->after('name');
            $table->string('phone')->nullable()->after('email');
            $table->timestamp('trial_started_at')->nullable()->after('is_active');
            $table->timestamp('trial_ends_at')->nullable()->after('trial_started_at');
            $table->timestamp('license_started_at')->nullable()->after('trial_ends_at');
            $table->timestamp('license_ends_at')->nullable()->after('license_started_at');
        });

        $startsAt = now();
        $endsAt = $startsAt->copy()->addDays(14);

        DB::table('users')
            ->whereNull('trial_started_at')
            ->update([
                'trial_started_at' => $startsAt,
                'trial_ends_at' => $endsAt,
                'license_started_at' => $startsAt,
                'license_ends_at' => $endsAt,
            ]);
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'company_name',
                'phone',
                'trial_started_at',
                'trial_ends_at',
                'license_started_at',
                'license_ends_at',
            ]);
        });
    }
};
