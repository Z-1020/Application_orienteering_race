<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    protected $table = 'VIK_ADMINISTRATEUR';
    protected $primaryKey = 'COM_ID';
    public $timestamps = false;
    public $incrementing = false;
}
