<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Admin;


class Account extends Authenticatable
{
    use Notifiable;

    protected $table = "VIK_COMPTE";     
    protected $primaryKey = 'COM_ID';     
    public $incrementing = true;  
    protected $keyType = 'int';
    public $timestamps = false; 

    protected $fillable = [
        'COM_NOM',
        'COM_PRENOM',
        'COM_DATE_NAISSANCE',
        'COM_ADRESSE',
        'COM_TELEPHONE',
        'COM_MAIL',
        'COM_PSEUDO',
        'COM_MDP',
    ];

    protected $hidden = [
        'COM_MDP',
    ];

    public function adherent()
    {
        return $this->hasOne(Adherent::class, 'COM_ID', 'COM_ID');
    }

        
    public function getAuthPassword()
    {
        return $this->COM_MDP;
    }

    public function administrateur()
    {
        return $this->hasOne(Admin::class, 'COM_ID', 'COM_ID');
    }

    public function isAdmin(): bool
    {
        return $this->administrateur()->exists();
    }

    

    public function getInfo(): array
    {
        return $this->toArray();
    }


}
