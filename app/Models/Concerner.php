<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Concerner extends Model
{
    protected $table = 'VIK_CONCERNER';

    protected $primaryKey = null;
    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [
        'CAT_AGE_ID',
        'CLU_ID',
        'RAI_ID',
        'COU_ID',
    ];

    public function categorieAge()
    {
        return $this->belongsTo(Categorie_Age::class, 'CAT_AGE_ID', 'CAT_AGE_ID');
    }

}