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
        Schema::table('audits', function (Blueprint $table) {
            $table->dropColumn('auditable_id');
            $table->dropColumn('user_id');
        });

        Schema::table('audits', function (Blueprint $table) {
            $table->char('auditable_id', 36)->after('event');
            $table->char('user_id', 36)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('audits', function (Blueprint $table) {
            $table->dropColumn('auditable_id');
        });

        Schema::table('audits', function (Blueprint $table) {
            $table->bigInteger('auditable_id')->unsigned()->after('event');
        });
    }
};
