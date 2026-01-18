<?php

namespace App\Http\Controllers;

use App\Models\RaceType;
use App\Models\Race;
use App\Models\Teams;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Raid;
use App\Models\AgeCategory;
use App\Models\Concerner;

class RaceController extends Controller
{
    public function index(Request $request)
    {
        $clu_id = $request->query('clu_id');
        $rai_id = $request->query('rai_id');

        if (!$clu_id || !$rai_id) {
            abort(404);
        }

        $raid = Raid::where('RAI_ID', $rai_id)
                    ->where('CLU_ID', $clu_id)
                    ->firstOrFail();

        $races = Race::where('CLU_ID', $clu_id)
                     ->where('RAI_ID', $rai_id)
                     ->get();

        $types = RaceType::all();

        $userId = Auth::id();
        $isOrganisateur = $userId ? Raid::isOrganisateur($clu_id, $rai_id, $userId) : false;

        return view('races.index', compact('raid', 'races', 'types', 'clu_id', 'rai_id', 'isOrganisateur'));
    }

    public function create(Request $request)
    {
        $clu_id = $request->query('clu_id');
        $rai_id = $request->query('rai_id');

        if (!$clu_id || !$rai_id) {
            abort(404);
        }

        $types = RaceType::all();
        $categories = AgeCategory::all();

        $adherents = DB::table('VIK_ADHERENT AS A')
            ->join('VIK_COMPTE AS C', 'A.COM_ID', '=', 'C.COM_ID')
            ->whereIn('A.COM_ID', function ($query) use ($clu_id) {
                $query->select('COM_ID')
                      ->from('VIK_ADHERER')
                      ->where('CLU_ID', $clu_id)
                      ->where('ADHERER_STATUS', 'VALIDE');
            })
            ->select('A.COM_ID', 'C.COM_NOM', 'C.COM_PRENOM')
            ->get();

        return view('races.create', compact('clu_id', 'rai_id', 'types', 'adherents', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'CLU_ID' => 'required|integer',
            'RAI_ID' => 'required|integer',
            'COU_TYP_ID' => 'required|integer',
            'COM_ID_ORGANISATEUR_COURSE' => 'required|integer',
            'COU_NOM' => 'required|string|max:64',
            'COU_DATE_DEBUT' => 'required|date|after_or_equal:today',
            'COU_DATE_FIN' => 'required|date|after_or_equal:COU_DATE_DEBUT',
            'COU_NB_PARTICIPANT_MIN' => 'nullable|integer|min:1',
            'COU_NB_PARTICIPANT_MAX' => 'nullable|integer|gte:COU_NB_PARTICIPANT_MIN',
            'COU_NB_EQUIPE_MIN' => 'nullable|integer|min:1',
            'COU_NB_EQUIPE_MAX' => 'nullable|integer|gte:COU_NB_EQUIPE_MIN',
            'COU_PRIX_REPAS' => 'nullable|integer|min:0',
            'COU_NB_MAX_PAR_EQUIPE' => 'nullable|integer|min:1',
            'COU_DIFFICULTE' => 'nullable|string|max:32',
            'COU_REDUCTION_LICENCIE' => 'nullable|integer|min:0',
            'COU_PUCE_OBLIGATOIRE' => 'nullable|boolean',
            'categorie_age_ids' => 'required|array|min:1',
            'categorie_age_ids.*' => 'integer|exists:VIK_CATEGORIE_AGE,CAT_AGE_ID',
        ], [
            'CLU_ID.integer' => 'Le club sélectionné est invalide.',
            'RAI_ID.integer' => 'Le raid sélectionné est invalide.',
            'COU_TYP_ID.integer' => 'Le type de course sélectionné est invalide.',
            'COM_ID_ORGANISATEUR_COURSE.integer' => "L'organisateur sélectionné est invalide.",
            'COU_NOM.string' => 'Le nom de la course doit être une chaîne de caractères.',
            'COU_NOM.max' => 'Le nom de la course ne peut pas dépasser 64 caractères.',
            'COU_DIFFICULTE.string' => 'La difficulté doit être une chaîne de caractères.',
            'COU_DIFFICULTE.max' => 'La difficulté ne peut pas dépasser 32 caractères.',
            'COU_DATE_DEBUT.date' => 'La date de début doit être une date valide.',
            'COU_DATE_DEBUT.after_or_equal' => 'La date de début ne peut pas être antérieure à aujourd’hui.',
            'COU_DATE_FIN.date' => 'La date de fin doit être une date valide.',
            'COU_DATE_FIN.after_or_equal' => 'La date de fin doit être postérieure ou égale à la date de début.',
            'COU_NB_PARTICIPANT_MIN.integer' => 'Le nombre minimum de participants doit être un nombre entier.',
            'COU_NB_PARTICIPANT_MIN.min' => 'Le nombre minimum de participants doit être au moins 1.',
            'COU_NB_PARTICIPANT_MAX.integer' => 'Le nombre maximum de participants doit être un nombre entier.',
            'COU_NB_PARTICIPANT_MAX.gte' => 'Le nombre maximum de participants doit être supérieur ou égal au minimum.',
            'COU_NB_EQUIPE_MIN.integer' => "Le nombre minimum d'équipes doit être un nombre entier.",
            'COU_NB_EQUIPE_MIN.min' => "Le nombre minimum d'équipes doit être au moins 1.",
            'COU_NB_EQUIPE_MAX.integer' => "Le nombre maximum d'équipes doit être un nombre entier.",
            'COU_NB_EQUIPE_MAX.gte' => "Le nombre maximum d'équipes doit être supérieur ou égal au minimum.",
            'COU_PRIX_REPAS.integer' => 'Le prix du repas doit être un nombre entier.',
            'COU_PRIX_REPAS.min' => 'Le prix du repas ne peut pas être négatif.',
            'COU_NB_MAX_PAR_EQUIPE.integer' => 'Le nombre maximum de coureurs par équipe doit être un nombre entier.',
            'COU_NB_MAX_PAR_EQUIPE.min' => 'Le nombre maximum de coureurs par équipe doit être au moins 1.',
            'COU_REDUCTION_LICENCIE.integer' => 'La réduction pour les licenciés doit être un nombre entier.',
            'COU_REDUCTION_LICENCIE.min' => 'La réduction ne peut pas être négative.',
            'COU_PUCE_OBLIGATOIRE.boolean' => 'Le champ "Puce obligatoire" doit être vrai ou faux.',
            'categorie_age_ids.required' => 'Au moins une catégorie d’âge doit être sélectionnée.',
            'categorie_age_ids.array' => 'Les catégories d’âge sélectionnées sont invalides.',
            'categorie_age_ids.min' => 'Vous devez sélectionner au moins une catégorie d’âge.',
            'categorie_age_ids.*.integer' => 'Une catégorie d’âge sélectionnée est invalide.',
            'categorie_age_ids.*.exists' => 'Une catégorie d’âge sélectionnée n’existe pas.',
        ]);

        $organisateurId = $request->COM_ID_ORGANISATEUR_COURSE;

        $exists = DB::table('VIK_ORGANISATEUR_COURSE')
                    ->where('COM_ID', $organisateurId)
                    ->exists();

        if (!$exists) {
            return back()->withErrors(['COM_ID_ORGANISATEUR_COURSE' => 'Organisateur invalide']);
        }

        $dateDebut = Carbon::parse($request->COU_DATE_DEBUT);
        $dateFin = Carbon::parse($request->COU_DATE_FIN);
        $dureeMinutes = ($dateFin->diffInMinutes($dateDebut)) * -1;

        $maxId = Race::max('COU_ID') ?? 0;
        $ID = $maxId + 1;

        $puceObligatoire = $request->has('COU_PUCE_OBLIGATOIRE') ? 1 : 0;

        Race::create([
            'COU_ID' => $ID,
            'CLU_ID' => $request->input('CLU_ID'),
            'RAI_ID' => $request->input('RAI_ID'),
            'COU_TYP_ID' => $request->input('COU_TYP_ID'),
            'COM_ID_ORGANISATEUR_COURSE' => $organisateurId,
            'COU_NOM' => $request->input('COU_NOM'),
            'COU_DUREE' => $dureeMinutes,
            'COU_DATE_DEBUT' => $request->input('COU_DATE_DEBUT'),
            'COU_DATE_FIN' => $request->input('COU_DATE_FIN'),
            'COU_NB_PARTICIPANT_MAX' => $request->input('COU_NB_PARTICIPANT_MAX'),
            'COU_NB_PARTICIPANT_MIN' => $request->input('COU_NB_PARTICIPANT_MIN'),
            'COU_NB_EQUIPE_MIN' => $request->input('COU_NB_EQUIPE_MIN'),
            'COU_NB_EQUIPE_MAX' => $request->input('COU_NB_EQUIPE_MAX'),
            'COU_PRIX_REPAS' => $request->input('COU_PRIX_REPAS'),
            'COU_NB_MAX_PAR_EQUIPE' => $request->input('COU_NB_MAX_PAR_EQUIPE'),
            'COU_DIFFICULTE' => $request->input('COU_DIFFICULTE'),
            'COU_REDUCTION_LICENCIE' => $request->input('COU_REDUCTION_LICENCIE'),
            'COU_DATE_DEMANDE' => now(),
            'COU_STATUS' => 'VALIDE',
            'COU_DATE_DECISION' => now(),
            'COU_PUCE_OBLIGATOIRE' => $puceObligatoire,
        ]);

        foreach ($request->input('categorie_age_ids') as $catAgeId) {
            Concerner::create([
                'CAT_AGE_ID' => $catAgeId,
                'CLU_ID' => $request->input('CLU_ID'),
                'RAI_ID' => $request->input('RAI_ID'),
                'COU_ID' => $ID,
            ]);
        }

        return redirect()->route('races.index', ['clu_id' => $request->CLU_ID, 'rai_id' => $request->RAI_ID])
                         ->with('success', 'Course créée avec succès');
    }

    public function destroy(Request $request, $COU_ID)
    {
        $race = Race::findOrFail($COU_ID);
        $clu_id = $race->CLU_ID;
        $rai_id = $race->RAI_ID;

        Concerner::where('COU_ID', $COU_ID)->delete();

        $race->delete();

        $redirect = $request->input('redirect');
        if ($redirect) {
            return redirect($redirect)->with('success', 'Course supprimée avec succès.');
        }

        return redirect()->route('races.index', ['clu_id' => $clu_id, 'rai_id' => $rai_id])
                         ->with('success', 'Course supprimée avec succès.');
    }

    public function showParticipants($idClub, $idRaid, $idRace)
    {
        $team = Teams::join('VIK_CREATEUR_EQUIPE', 'VIK_EQUIPE.COM_ID_CREATEUR', '=', 'VIK_CREATEUR_EQUIPE.COM_ID')
            ->join('VIK_COMPTE', 'VIK_COMPTE.COM_ID', '=', 'VIK_CREATEUR_EQUIPE.COM_ID')
            ->select(
                "VIK_EQUIPE.EQU_ID", 
                "VIK_EQUIPE.EQU_NOM",
                'VIK_EQUIPE.EQU_DOSSARD',
                'VIK_EQUIPE.EQU_STATUS',
                'VIK_COMPTE.COM_NOM',
                'VIK_COMPTE.COM_PRENOM'
            )
            ->where('VIK_EQUIPE.CLU_ID', $idClub)
            ->where('VIK_EQUIPE.COU_ID', $idRace)
            ->where('VIK_EQUIPE.RAI_ID', $idRaid)
            ->get();

        $runnerInRace = Race::join('VIK_EQUIPE', function($join) {
                $join->on('VIK_COURSE.RAI_ID', '=', 'VIK_EQUIPE.RAI_ID')
                     ->on('VIK_COURSE.CLU_ID', '=', 'VIK_EQUIPE.CLU_ID')
                     ->on('VIK_COURSE.COU_ID', '=', 'VIK_EQUIPE.COU_ID');
            })
            ->join('VIK_CONTENIR', function($join) {
                $join->on('VIK_COURSE.RAI_ID', '=', 'VIK_CONTENIR.RAI_ID')
                     ->on('VIK_COURSE.CLU_ID', '=', 'VIK_CONTENIR.CLU_ID')
                     ->on('VIK_COURSE.COU_ID', '=', 'VIK_CONTENIR.COU_ID')
                     ->on('VIK_EQUIPE.EQU_ID', '=', 'VIK_CONTENIR.EQU_ID');
            })
            ->join('VIK_COMPTE', 'VIK_COMPTE.COM_ID', '=', 'VIK_CONTENIR.COM_ID')
            ->join('VIK_COUREUR', 'VIK_COUREUR.COM_ID', '=', 'VIK_CONTENIR.COM_ID')
            ->join('VIK_CATEGORIE_AGE', 'VIK_CATEGORIE_AGE.CAT_AGE_ID', '=', 'VIK_COUREUR.CAT_AGE_ID')
            ->leftJoin('VIK_ADHERENT', 'VIK_ADHERENT.COM_ID', '=', 'VIK_CONTENIR.COM_ID')
            ->select(
                'VIK_COMPTE.COM_NOM AS COMPTE_COM_NOM', 
                'VIK_COMPTE.COM_PRENOM', 
                'VIK_EQUIPE.EQU_ID',
                'VIK_COMPTE.COM_DATE_NAISSANCE', 
                'VIK_COMPTE.COM_ADRESSE', 
                'VIK_EQUIPE.EQU_NOM', 
                'VIK_EQUIPE.EQU_DOSSARD', 
                'VIK_CATEGORIE_AGE.CAT_AGE_MIN', 
                'VIK_CATEGORIE_AGE.CAT_AGE_MAX', 
                'VIK_CATEGORIE_AGE.CAT_AGE_MONTANT', 
                'VIK_CONTENIR.COUR_PPS', 
                'VIK_CONTENIR.COUREUR_STATUS',
                'VIK_ADHERENT.ADH_NUM_LICENCIE'
            )
            ->where('VIK_COURSE.CLU_ID', $idClub)
            ->where('VIK_COURSE.COU_ID', $idRace)
            ->where('VIK_COURSE.RAI_ID', $idRaid)
            ->get();

        $runnerByTeam = collect($runnerInRace)->groupBy('EQU_ID');
        return view("ViewParticipants", compact("runnerByTeam", "team"));
    }

    public function myPastRaces()
    {
        $userId = Auth::id();
        if (!$userId) {
            return redirect()->route('login');
        }

        $past = DB::table('VIK_CONTENIR')
            ->join('VIK_COURSE', function ($join) {
                $join->on('VIK_CONTENIR.CLU_ID', '=', 'VIK_COURSE.CLU_ID')
                     ->on('VIK_CONTENIR.RAI_ID', '=', 'VIK_COURSE.RAI_ID')
                     ->on('VIK_CONTENIR.COU_ID', '=', 'VIK_COURSE.COU_ID');
            })
            ->join('VIK_RAID', function ($join) {
                $join->on('VIK_COURSE.CLU_ID', '=', 'VIK_RAID.CLU_ID')
                     ->on('VIK_COURSE.RAI_ID', '=', 'VIK_RAID.RAI_ID');
            })
            ->leftJoin('VIK_EQUIPE', function ($join) {
                $join->on('VIK_CONTENIR.CLU_ID', '=', 'VIK_EQUIPE.CLU_ID')
                     ->on('VIK_CONTENIR.RAI_ID', '=', 'VIK_EQUIPE.RAI_ID')
                     ->on('VIK_CONTENIR.COU_ID', '=', 'VIK_EQUIPE.COU_ID')
                     ->on('VIK_CONTENIR.EQU_ID', '=', 'VIK_EQUIPE.EQU_ID');
            })
            ->select(
                'VIK_COURSE.CLU_ID',
                'VIK_COURSE.RAI_ID',
                'VIK_COURSE.COU_ID',
                'VIK_COURSE.COU_NOM',
                'VIK_COURSE.COU_DATE_DEBUT',
                'VIK_COURSE.COU_DATE_FIN',
                'VIK_RAID.RAI_NOM as raid_nom',
                'VIK_CONTENIR.EQU_ID',
                'VIK_EQUIPE.EQU_NOM as equ_nom',
                'VIK_CONTENIR.COUR_PPS',
                'VIK_CONTENIR.COUREUR_STATUS'
            )
            ->where('VIK_CONTENIR.COM_ID', $userId)
            ->whereNotNull('VIK_COURSE.COU_DATE_FIN')
            ->where('VIK_COURSE.COU_DATE_FIN', '<', now())
            ->orderBy('VIK_COURSE.COU_DATE_FIN', 'desc')
            ->get();

        return view('races.myRaces', ['past' => $past]);
    }

    public function show($clu_id, $rai_id, $COU_ID)
    {
        $race = Race::with(['raceType', 'ageCategories', 'contenir'])
                    ->where('COU_ID', $COU_ID)
                    ->where('RAI_ID', $rai_id)
                    ->where('CLU_ID', $clu_id)
                    ->firstOrFail();

        $raid = $race->raid; 

        $teams = $race->contenir()
                    ->join('VIK_EQUIPE', 'VIK_CONTENIR.EQU_ID', '=', 'VIK_EQUIPE.EQU_ID')
                    ->select('VIK_EQUIPE.*', 'VIK_CONTENIR.COUR_PPS', 'VIK_CONTENIR.COUREUR_STATUS')
                    ->get()
                    ->groupBy('EQU_ID');

        return view('races.show', compact('race', 'raid', 'teams'));
    }
}
