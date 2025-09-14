<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('notifications')) {
            Schema::create('notifications', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->string('type');
                $table->morphs('notifiable');
                $table->text('data');
                $table->timestamp('read_at')->nullable();
                $table->timestamps();
                
                // Add indexes for better performance
                $table->index(['notifiable_type', 'notifiable_id']);
                $table->index('read_at');
            });
        } else {
            Schema::table('notifications', function (Blueprint $table) {
                // Add index for better performance on notifiable_type and notifiable_id if not exists
                $indexes = collect(DB::select("SHOW INDEXES FROM notifications"))->pluck('Key_name');
                
                if (!$indexes->contains('notifications_notifiable_type_notifiable_id_index')) {
                    $table->index(['notifiable_type', 'notifiable_id']);
                }
                
                // Add index for read_at if not exists
                if (!$indexes->contains('notifications_read_at_index')) {
                    $table->index('read_at');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // We won't drop the table in the down method
        // to prevent accidental data loss
    }
};
