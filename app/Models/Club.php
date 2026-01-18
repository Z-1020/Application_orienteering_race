<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Club extends Model
{
    protected $table = 'VIK_CLUB';

    protected $primaryKey = 'CLU_ID';

    public $incrementing = false;

    protected $keyType = 'int';

    protected $fillable = [
        'CLU_ID',
        'COM_ID_RESPONSABLE',
        'CLU_NOM',
        'CLU_ADRESSE',
        'CLU_CODE_POST',
        'CLU_DATE_DEMANDE',
        'CLU_STATUS',
        'CLU_DATE_DECISION',
        'CLU_TELEPHONE'
    ];

    public $timestamps = false;

    protected $dates = [
        'CLU_DATE_DEMANDE',
        'CLU_DATE_DECISION'
    ];

    public static function createClub( $CLU_NOM, $CLU_ADRESSE = null, $CLU_CODE_POST = null, $COM_ID_RESPONSABLE = 2, $CLU_DATE_DEMANDE = null, $CLU_STATUS = 'en attente',$CLU_DATE_DECISION=null, $CLU_TELEPHONE)
    {

        $maxCluId = Club::max('CLU_ID');
        $clu_id = $maxCluId ? $maxCluId + 1 : 1;

        return self::create([
            'CLU_ID' => $clu_id,
            'COM_ID_RESPONSABLE' => $COM_ID_RESPONSABLE,
            'CLU_NOM' => $CLU_NOM,
            'CLU_ADRESSE' => $CLU_ADRESSE,
            'CLU_CODE_POST' => $CLU_CODE_POST,
            'CLU_DATE_DEMANDE' => $CLU_DATE_DEMANDE  ?? now(),
            'CLU_STATUS' => $CLU_STATUS ?? null,
            'CLU_DATE_DECISION'=>$CLU_DATE_DECISION,
            'CLU_TELEPHONE'=>$CLU_TELEPHONE ?? null,
        ]);
    }

    public function responsable()
    {
        return $this->belongsTo(Member::class, 'COM_ID_RESPONSABLE', 'COM_ID');
    }
}
