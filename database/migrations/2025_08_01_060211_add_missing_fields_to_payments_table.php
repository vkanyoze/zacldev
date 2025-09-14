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
        Schema::table('payments', function (Blueprint $table) {
            if (!Schema::hasColumn('payments', 'payment_method')) {
                $table->string('payment_method')->nullable()->after('amount_spend');
            }
            if (!Schema::hasColumn('payments', 'name')) {
                $table->string('name')->nullable()->after('payment_method');
            }
            if (!Schema::hasColumn('payments', 'surname')) {
                $table->string('surname')->nullable()->after('name');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $columnsToDrop = [];
            
            if (Schema::hasColumn('payments', 'payment_method')) {
                $columnsToDrop[] = 'payment_method';
            }
            if (Schema::hasColumn('payments', 'name')) {
                $columnsToDrop[] = 'name';
            }
            if (Schema::hasColumn('payments', 'surname')) {
                $columnsToDrop[] = 'surname';
            }
            
            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }
};
