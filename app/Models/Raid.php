<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Raid extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'VIK_RAID';
    protected $primaryKey = 'RAI_ID';
    public $incrementing = false;
    protected $keyType = 'int';

    protected $fillable = [
        'CLU_ID',
        'RAI_ID',
        'COM_ID_ORGANISATEUR_RAID',
        'RAI_NOM',
        'RAI_INSCRIPTION_DATE_DEBUT',
        'RAI_INSCRIPTION_DATE_FIN',
        'RAI_DATE_DEBUT',
        'RAI_DATE_FIN',
        'RAI_MAIL',
        'RAI_TELEPHONE',
        'RAI_LIEU',
        'RAI_ILLUSTRATION',
        'RAI_SITE_WEB',
        'RAI_DATE_DEMANDE',
        'RAI_STATUS',
        'RAI_DATE_DECISION',
    ];

    // RELATIONS
    public function club()
    {
        return $this->belongsTo(Club::class, 'CLU_ID', 'CLU_ID');
    }

    public function races()
    {
        return $this->hasMany(Race::class, 'RAI_ID', 'RAI_ID')
                    ->where('COU_STATUS', 'VALIDE');
    }

    // METHODES UTILITAIRES
    public function coordinates(): array
    {
        if (empty($this->RAI_LIEU)) return ['lat' => null, 'lng' => null];

        $encoded = urlencode($this->RAI_LIEU);
        $url = "https://nominatim.openstreetmap.org/search?q={$encoded}&format=json&limit=1";
        $context = stream_context_create([
            "http" => ["header" => "User-Agent: RaidApp/1.0"]
        ]);

        $result = @file_get_contents($url, false, $context);
        $data = json_decode($result, true);

        return !empty($data) ? ['lat' => $data[0]['lat'], 'lng' => $data[0]['lon']] : ['lat' => null, 'lng' => null];
    }

    public function attachCoordinates()
    {
        $coords = $this->coordinates();
        $this->latitude = $coords['lat'];
        $this->longitude = $coords['lng'];
    }

    public function filteredRaces(array $ageIds = [], array $typeIds = [])
    {
        return $this->races()
            ->when($ageIds, function($q) use ($ageIds) {
                $q->whereHas('ageCategories', fn($q2) => $q2->whereIn('VIK_CONCERNER.CAT_AGE_ID', $ageIds));
            })
            ->when($typeIds, fn($q) => $q->whereIn('COU_TYP_ID', $typeIds))
            ->get()
            ->filter(fn($race) => $race->CLU_ID == $this->CLU_ID);
    }


    public static function isOrganisateur(int $clu_id, int $rai_id, int $com_id): bool
    {
        return self::where('RAI_ID', $rai_id)
            ->where('CLU_ID', $clu_id)
            ->where('COM_ID_ORGANISATEUR_RAID', $com_id)
            ->exists();
    }
}
