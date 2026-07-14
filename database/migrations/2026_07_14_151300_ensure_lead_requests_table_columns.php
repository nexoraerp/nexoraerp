<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('lead_requests')) {
            Schema::create('lead_requests', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('company_name')->nullable();
                $table->string('phone');
                $table->string('email')->nullable();
                $table->text('message')->nullable();
                $table->string('status')->default('new');
                $table->text('admin_note')->nullable();
                $table->timestamp('contacted_at')->nullable();
                $table->timestamps();
            });

            return;
        }

        Schema::table('lead_requests', function (Blueprint $table) {
            if (! Schema::hasColumn('lead_requests', 'company_name')) {
                $table->string('company_name')->nullable()->after('name');
            }

            if (! Schema::hasColumn('lead_requests', 'phone')) {
                $table->string('phone')->nullable();
            }

            if (! Schema::hasColumn('lead_requests', 'email')) {
                $table->string('email')->nullable();
            }

            if (! Schema::hasColumn('lead_requests', 'message')) {
                $table->text('message')->nullable();
            }

            if (! Schema::hasColumn('lead_requests', 'status')) {
                $table->string('status')->default('new');
            }

            if (! Schema::hasColumn('lead_requests', 'admin_note')) {
                $table->text('admin_note')->nullable();
            }

            if (! Schema::hasColumn('lead_requests', 'contacted_at')) {
                $table->timestamp('contacted_at')->nullable();
            }

            if (! Schema::hasColumn('lead_requests', 'created_at')) {
                $table->timestamp('created_at')->nullable();
            }

            if (! Schema::hasColumn('lead_requests', 'updated_at')) {
                $table->timestamp('updated_at')->nullable();
            }
        });
    }

    public function down(): void
    {
        //
    }
};
