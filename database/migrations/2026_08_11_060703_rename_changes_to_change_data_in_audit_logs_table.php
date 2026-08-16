<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('audit_logs', function (Blueprint $table) {
            $table->dropColumn('changes');
        });
        Schema::table('audit_logs', function (Blueprint $table) {
            $table->json('change_data')->nullable()->after('auditable_id');
        });
    }

    public function down(): void
    {
        Schema::table('audit_logs', function (Blueprint $table) {
            $table->dropColumn('change_data');
        });
        Schema::table('audit_logs', function (Blueprint $table) {
            $table->json('changes')->nullable()->after('auditable_id');
        });
    }
};
