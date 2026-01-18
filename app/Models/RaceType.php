<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RaceType extends Model{

    use HasFactory;

    protected $table = 'VIK_COURSE_TYPE';
    protected $primaryKey = 'COU_TYP_ID';
    public $incrementing = false;
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'COU_TYP_ID',
        'COU_TYPE_LIBELLE',
    ];

    public function races()
    {
        return $this->hasMany(Race::class, 'COU_TYP_ID', 'COU_TYP_ID');
    }
}
