<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Builder;

class Race extends Model
{
    use HasFactory;

    protected $table = 'VIK_COURSE';
    protected $primaryKey = 'COU_ID';
    public $incrementing = false;
    protected $keyType = 'int';
    public $timestamps = false;



    protected $fillable = [
        'COU_ID',
        'CLU_ID',
        'RAI_ID',
        'COU_TYP_ID',
        'COM_ID_ORGANISATEUR_COURSE',
        'COU_NOM',
        'COU_DUREE',
        'COU_DATE_DEBUT',
        'COU_DATE_FIN',
        'COU_NB_PARTICIPANT_MAX',
        'COU_NB_PARTICIPANT_MIN',
        'COU_NB_EQUIPE_MIN',
        'COU_NB_EQUIPE_MAX',
        'COU_PRIX_REPAS',
        'COU_NB_MAX_PAR_EQUIPE',
        'COU_DIFFICULTE',
        'COU_REDUCTION_LICENCIE',
        'COU_DATE_DEMANDE',
        'COU_STATUS',
        'COU_PUCE_OBLIGATOIRE',
    ];

    public function raid(){
        return $this->belongsTo(Raid::class, 'RAI_ID', 'RAI_ID')->where('CLU_ID', $this->CLU_ID);
    }


    public function ageCategories()
    {
        return $this->hasManyThrough(
            AgeCategory::class,
            Concerner::class,
            'COU_ID',
            'CAT_AGE_ID',
            'COU_ID',
            'CAT_AGE_ID'
        );
    }

    public function raceType()
    {
        return $this->belongsTo(RaceType::class, 'COU_TYP_ID', 'COU_TYP_ID');
    }

    public function club()
    {
        return $this->raid->club();
    }


    public function ageMin()
    {
        return $this->categoriesAge()->min('CAT_AGE_MIN');
    }

    public function ageMax()
    {
        return $this->categoriesAge()->max('CAT_AGE_MAX');
    }

    public function contenir()
    {
        return $this->hasMany(Contenir::class, 'COU_ID', 'COU_ID')
                    ->where('VIK_CONTENIR.RAI_ID', $this->RAI_ID)
                    ->where('VIK_CONTENIR.CLU_ID', $this->CLU_ID);
    }


    public function getNbTeams()
    {
        $nb = $this->contenir()
            ->select( Teams::raw('COUNT(*) as nb_teams'))
            ->value('nb_teams');
        return $nb;
    }
    
    public function scopeFilterByAgeCategories(Builder $query, array $ageIds)
    {
        if (empty($ageIds)) {
            return $query;
        }

        return $query->whereIn('COU_ID', function ($sub) use ($ageIds) {
            $sub->select('COU_ID')
                ->from('VIK_CONCERNER')
                ->whereIn('CAT_AGE_ID', $ageIds)
                ->groupBy('COU_ID')
                ->havingRaw('COUNT(DISTINCT CAT_AGE_ID) = ?', [count($ageIds)]);
        });
    }

    public function getNbRacerTotal()
    {
        return $this->contenir()
                    ->count();
    }


    public function scopeWithAllAgeCategories(Builder $query, array $ageIds)
    {
        if (empty($ageIds)) {
            return $query;
        }

        return $query->whereIn('COU_ID', function ($sub) use ($ageIds) {
            $sub->select('COU_ID')
                ->from('VIK_CONCERNER')
                ->whereIn('CAT_AGE_ID', $ageIds)
                ->groupBy('COU_ID')
                ->havingRaw(
                    'COUNT(DISTINCT CAT_AGE_ID) = ?',
                    [count($ageIds)]
                );
        });
    }

    public function scopeFilterByTypes(Builder $query, array $typeIds)
    {
        if (empty($typeIds)) {
            return $query;
        }

        return $query->whereIn('COU_TYP_ID', $typeIds);
    }

}