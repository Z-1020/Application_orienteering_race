<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teams extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'VIK_EQUIPE';

    public static function getAll(){
        return self::all();
    }

    public static function get($id){
        return self::find($id);
    }

    protected $fillable = [
        'clu_id',
        'rai_id',
        'cou_id',
        'equ_id',
        'COM_ID_CREATEUR',
        'equ_nom',
    ];

    /**
     * Creates a team
     */
    public static function createTeam($clu_id, $rai_id, $cou_id, $com_id, $equ_nom)
    {
        $lastEquId = self::where('clu_id', $clu_id)
                        ->where('rai_id', $rai_id)
                        ->where('cou_id', $cou_id)
                        ->max('equ_id');

        $equ_id = $lastEquId ? $lastEquId + 1 : 1;

        return self::create([
            'clu_id' => $clu_id,
            'rai_id' => $rai_id,
            'cou_id' => $cou_id,
            'equ_id' => $equ_id,
            'COM_ID_CREATEUR' => $com_id,
            'equ_nom' => $equ_nom,
            'coureur_date_demande' => now(),
        ]);
    }

    public function membres()
    {
        return $this->hasMany(Member::class, 'equ_id', 'equ_id');
    }
}