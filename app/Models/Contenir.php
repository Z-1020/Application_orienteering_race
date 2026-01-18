<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contenir extends Model
{
    protected $table = 'VIK_CONTENIR';

    protected $primaryKey = null;
    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [
        'CLU_ID',
        'RAI_ID',
        'COU_ID',
        'EQU_ID',
        'COM_ID',
        'COUR_PPS'
    ];

    public function course()
    {
        return $this->belongsTo(Race::class, 'COU_ID', 'COU_ID')
                    ->where('VIK_COURSE.RAI_ID', $this->RAI_ID)
                    ->where('VIK_COURSE.CLU_ID', $this->CLU_ID);
    }

    public function team()
    {
        return $this->belongsTo(Teams::class, 'EQU_ID', 'EQU_ID');
    }

}
