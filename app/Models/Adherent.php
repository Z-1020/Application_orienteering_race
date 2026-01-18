<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Adherent extends Model
{
    protected $table = 'VIK_ADHERENT';
    protected $primaryKey = 'COM_ID';
    public $timestamps = false;
    
    protected $fillable = [
        'COM_ID',
        'ADH_NUM_LICENCIE',
        'ADH_NUM_PUCE',
    ];


    public function com(){
        return $this->belongsTo(Account::class, 'COM_ID', 'COM_ID');
    }

    public function clubs(){
        return $this->belongsToMany(Club::class, 'adherer', 'ADH_ID', 'CLU_ID');
    }
}