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
        Schema::create('password_policy_settings', function (Blueprint $table) {
            $table->id();
            $table->boolean('require_uppercase')->default(false);
            $table->boolean('require_lowercase')->default(false);
            $table->boolean('require_numbers')->default(false);
            $table->boolean('require_special_characters')->default(false);
            $table->integer('min_length')->default(8);
            $table->boolean('enabled')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('password_policy_settings');
    }
};
