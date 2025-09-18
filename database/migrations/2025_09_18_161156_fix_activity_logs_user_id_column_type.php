<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Check if foreign key exists and drop it if it does
        $foreignKeys = DB::select("
            SELECT CONSTRAINT_NAME 
            FROM information_schema.KEY_COLUMN_USAGE 
            WHERE TABLE_SCHEMA = DATABASE() 
            AND TABLE_NAME = 'activity_logs' 
            AND COLUMN_NAME = 'user_id' 
            AND REFERENCED_TABLE_NAME IS NOT NULL
        ");
        
        if (!empty($foreignKeys)) {
            foreach ($foreignKeys as $fk) {
                DB::statement("ALTER TABLE activity_logs DROP FOREIGN KEY {$fk->CONSTRAINT_NAME}");
            }
        }
        
        // Change the column type from BIGINT UNSIGNED to UUID
        DB::statement("ALTER TABLE activity_logs MODIFY COLUMN user_id CHAR(36) NOT NULL");
        
        // Add the foreign key constraint
        DB::statement("ALTER TABLE activity_logs ADD CONSTRAINT activity_logs_user_id_foreign FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop the foreign key constraint
        DB::statement("ALTER TABLE activity_logs DROP FOREIGN KEY activity_logs_user_id_foreign");
        
        // Change back to BIGINT UNSIGNED
        DB::statement("ALTER TABLE activity_logs MODIFY COLUMN user_id BIGINT UNSIGNED NOT NULL");
        
        // Re-add the foreign key constraint (this will fail due to type mismatch, but that's expected)
        try {
            DB::statement("ALTER TABLE activity_logs ADD CONSTRAINT activity_logs_user_id_foreign FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE");
        } catch (\Exception $e) {
            // This will fail due to type mismatch, which is expected
        }
    }
};
