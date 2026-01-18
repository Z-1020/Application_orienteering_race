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
        $sql = file_get_contents(database_path('sql/CREATE.sql'));
        DB::unprepared($sql);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared("
            DROP TABLE IF EXISTS vik_adherent;
            DROP TABLE IF EXISTS vik_adherer;
            DROP TABLE IF EXISTS vik_administrateur;
            DROP TABLE IF EXISTS vik_categorie_age;
            DROP TABLE IF EXISTS vik_club;
            DROP TABLE IF EXISTS vik_compte;
            DROP TABLE IF EXISTS vik_concerner;
            DROP TABLE IF EXISTS vik_contenir;
            DROP TABLE IF EXISTS vik_coureur;
            DROP TABLE IF EXISTS vik_course;
            DROP TABLE IF EXISTS vik_course_type;
            DROP TABLE IF EXISTS vik_createur_equipe;
            DROP TABLE IF EXISTS vik_equipe;
            DROP TABLE IF EXISTS vik_organisateur_course;
            DROP TABLE IF EXISTS vik_organisateur_raid;
            DROP TABLE IF EXISTS vik_raid;
        ");
    }

};
