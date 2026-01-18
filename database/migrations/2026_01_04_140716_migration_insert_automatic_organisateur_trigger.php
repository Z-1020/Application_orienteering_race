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
            DROP TRIGGER IF EXISTS insert_automatic_in_organisateur_trigger;
        ");

        DB::unprepared("
            CREATE TRIGGER vik_insert_automatic_in_organisateur_trigger
            AFTER INSERT ON VIK_ADHERENT
            FOR EACH ROW
            BEGIN
                INSERT INTO VIK_ORGANISATEUR_COURSE (COM_ID)
                VALUES (NEW.COM_ID);

                INSERT INTO VIK_ORGANISATEUR_RAID (COM_ID)
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
            DROP TRIGGER IF EXISTS insert_automatic_in_organisateur_trigger;;
        ");
    }
};






