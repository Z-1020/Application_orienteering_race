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
        DB::unprepared("
            DROP TRIGGER IF EXISTS vik_insert_automatic_in_createur_trigger;
        ");
        
        DB::unprepared("
            CREATE TRIGGER vik_insert_automatic_in_createur_trigger
            AFTER INSERT ON VIK_COMPTE
            FOR EACH ROW
            BEGIN
                INSERT INTO VIK_CREATEUR_EQUIPE (COM_ID)
                VALUES (NEW.COM_ID);
            END
        ");
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared("
            DROP TRIGGER IF EXISTS vik_insert_automatic_in_createur_trigger;
        ");
    }
};
