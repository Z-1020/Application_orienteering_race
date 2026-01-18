<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categorie_Age extends Model
{
    protected $table = 'VIK_CATEGORIE_AGE';

    protected $primaryKey = 'CAT_AGE_ID';
   
    public $timestamps = false;

    protected $fillable = [
        'CAT_AGE_ID',
        'CAT_AGE_MAX',
        'CAT_AGE_MIN',
        'CAT_AGE_MONTANT'
    ];
}
