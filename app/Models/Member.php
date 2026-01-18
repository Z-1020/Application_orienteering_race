<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    protected $table = 'VIK_ADHERER';
    protected $primaryKey = null;
    public $incrementing = false; 
    protected $keyType = 'int'; 
    public $timestamps = false;

    protected $fillable = [
        'CLU_ID',
        'COM_ID',
        'ADHERER_STATUS',
        'ADHERER_DATE_DEMANDE',
        'ADHERER_DATE_DECISION',
    ];

    public function compte()
    {
        return $this->belongsTo(Account::class, 'COM_ID', 'COM_ID');
    }
    public function club()
    {
        return $this->belongsTo(Club::class, 'CLU_ID', 'CLU_ID');
    }

    public function getMemberByClub($clubId, $comId)
    {
        $member = Member::with('compte', 'club')
            ->where('CLU_ID', $clubId)
            ->where('COM_ID', $comId)
            ->firstOrFail();

        return $member;
    }

    public function adherer()
    {
        return $this->belongsTo(Adherent::class, 'COM_ID', 'COM_ID');
    }
    
}