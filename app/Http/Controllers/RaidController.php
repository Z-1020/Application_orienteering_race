<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use App\Models\Raid;
use App\Models\Race;
use App\Models\AgeCategory;
use App\Models\RaceType;
use App\Models\Club;
use App\Models\Contenir;
use App\Models\Teams;

class raidController extends Controller
{
    public function index()
    {
        $raids = Raid::where('RAI_STATUS', 'VALIDE')->get();
        $ageCategories = AgeCategory::orderBy('CAT_AGE_ID')->get();
        $raceTypes = RaceType::orderBy('COU_TYP_ID')->get();

        //$raids->each->attachCoordinates();

        return view('raids.index', [
            'raids' => $raids,
            'ageCategories' => $ageCategories,
            'raceTypes' => $raceTypes,
            'races' => collect(),
        ]);
    }

    public function show($id)
    {
        $raid = Raid::findOrFail($id);
        //$raid->attachCoordinates();
        $races = $raid->races()->with('ageCategories', 'raceType')->get();
        $ageCategories = AgeCategory::orderBy('CAT_AGE_ID')->get();
        $raceTypes = RaceType::orderBy('COU_TYP_ID')->get();

        return view('raids.index', [
            'raids' => collect([$raid]),
            'races' => $races,
            'ageCategories' => $ageCategories,
            'raceTypes' => $raceTypes,
        ]);
    }

    public function filter(Request $request)
    {
        $selectedIds = $request->input('tranche_age', []);
        $ageIds  = array_filter($request->input('age_categories', []));
        $typeIds = array_filter($request->input('race_types', []));

        $ageCategories = AgeCategory::orderBy('CAT_AGE_ID')->get();
        $raceTypes = RaceType::orderBy('COU_TYP_ID')->get();

        $raids = Raid::where('RAI_STATUS', 'VALIDE')->get();

        foreach ($raids as $raid) {
            //$raid->attachCoordinates();
            $raid->races = $raid->filteredRaces($ageIds, $typeIds);

            if ($selectedIds) {
                $raid->races = $raid->races->filter(fn($race) =>
                    $race->ageCategories->pluck('CAT_AGE_ID')->intersect($selectedIds)->count() > 0
                );
            }
        }

        $raids = $raids->filter(fn($raid) => $raid->races->count() > 0);

        return view('raids.index', compact('raids', 'ageCategories', 'raceTypes'));
    }

    public function create()
    {
        if (!Auth::check()) return redirect()->route('login');

        $user = Auth::user();
        $club = Club::where('COM_ID_RESPONSABLE', $user->COM_ID)->first();

        if (!$club) return redirect()->route('home.index')->with('error', "Access reserved for club managers.");

        $members = DB::table('VIK_ADHERER')
            ->join('VIK_COMPTE', 'VIK_ADHERER.COM_ID', '=', 'VIK_COMPTE.COM_ID')
            ->select('VIK_COMPTE.COM_ID', 'VIK_COMPTE.COM_NOM', 'VIK_COMPTE.COM_PRENOM')
            ->where('VIK_ADHERER.CLU_ID', $club->CLU_ID)
            ->where(fn($q) => $q->where('VIK_ADHERER.ADHERER_STATUS', 'VALIDE')->orWhereNull('VIK_ADHERER.ADHERER_STATUS'))
            ->get();

        return view('raids.create', [
            'CLU_ID' => $club->CLU_ID,
            'members' => $members,
        ]);
    }

    public function store(Request $request)
    {
        if (!Auth::check()) return redirect()->route('login');

        $request->validate([
            'RAI_NOM' => 'required|string',
            'RAI_INSCRIPTION_DATE_DEBUT' => 'nullable|date',
            'RAI_INSCRIPTION_DATE_FIN' => 'nullable|date|after:RAI_INSCRIPTION_DATE_DEBUT',
            'RAI_DATE_DEBUT' => 'nullable|date|after:RAI_INSCRIPTION_DATE_FIN',
            'RAI_DATE_FIN' => 'nullable|date|after:RAI_DATE_DEBUT',
            'RAI_MAIL' => 'nullable|email',
            'RAI_TELEPHONE' => 'nullable|digits_between:1,12',
            'RAI_LIEU' => 'nullable|string',
            'RAI_ILLUSTRATION' => 'nullable|string',
            'RAI_SITE_WEB' => 'nullable|url',
        ]);


        $user = Auth::user();
        $club = Club::where('COM_ID_RESPONSABLE', $user->COM_ID)->first();

        if (!$club) return redirect()->route('home.index')->with('error', "You are not authorized to create a raid.");

        $organisateurId = $request->input('COM_ID_ORGANISATEUR_RAID');

        $isMember = DB::table('VIK_ADHERER')
            ->where('CLU_ID', $club->CLU_ID)
            ->where('COM_ID', $organisateurId)
            ->where(fn($q) => $q->where('ADHERER_STATUS', 'VALIDE')->orWhereNull('ADHERER_STATUS'))
            ->exists();

        if (!$isMember) {
            return redirect()->back()->withErrors(['COM_ID_ORGANISATEUR_RAID' => 'La personne sélectionnée n\'est pas membre valide du club.']);
        }

        if (!DB::table('VIK_ORGANISATEUR_RAID')->where('COM_ID', $organisateurId)->exists()) {
            DB::table('VIK_ORGANISATEUR_RAID')->insert(['COM_ID' => $organisateurId]);
        }

        $data = $request->all();
        $data['CLU_ID'] = $club->CLU_ID;
        $data['RAI_ID'] = Raid::max('RAI_ID') + 1;
        $data['RAI_DATE_DEMANDE'] = now();
        $data['RAI_STATUS'] = 'VALIDE';
        $data['RAI_DATE_DECISION'] = null;

        if ($organisateurId) $data['COM_ID_ORGANISATEUR_RAID'] = $organisateurId;

        Raid::create($data);

        return redirect()->route('myRaids')->with('success', 'Raid créé avec succès.');
    }

    public function myClubRaids()
    {
        if (!Auth::check()) return redirect()->route('login');

        $user = Auth::user();
        $club = Club::where('COM_ID_RESPONSABLE', $user->COM_ID)->first();

        if (!$club) return redirect()->route('home.index')->with('error', 'Vous n\'êtes pas responsable d\'aucun club.');

        $raids = Raid::where('CLU_ID', $club->CLU_ID)->orderBy('RAI_DATE_DEMANDE', 'desc')->get();
        $raids->each(fn($r) => $r->races_count = $r->races()->count());

        return view('club.myRaids', compact('club', 'raids'));
    }

    // Liste des raids dont l'utilisateur est organisateur
    public function manageRaids()
    {
        if (!Auth::check()) return redirect()->route('login');

        $user = Auth::user();
        // Raids où l'utilisateur est organisateur
        $raids = Raid::where('COM_ID_ORGANISATEUR_RAID', $user->COM_ID)->orderBy('RAI_DATE_DEMANDE', 'desc')->get();

        return view('raids.manage', compact('raids'));
    }

    public function manageShow($clu_id, $rai_id)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        $raid = Raid::where('RAI_ID', $rai_id)
            ->where('CLU_ID', $clu_id)
            ->firstOrFail();

        if (
            $raid->COM_ID_ORGANISATEUR_RAID != $user->COM_ID &&
            $raid->club->COM_ID_RESPONSABLE != $user->COM_ID
        ) {
            abort(403);
        }

        $races = Race::where('CLU_ID', $clu_id)
            ->where('RAI_ID', $rai_id)
            ->get();

        return view('raids.manage_show', compact('raid', 'races'));
    }


    public function destroy($rai_id)
    {
        if (!Auth::check()) return redirect()->route('login');

        $user = Auth::user();
        $raid = Raid::find($rai_id);

        if (!$raid) return redirect()->back()->with('error', 'Raid introuvable.');

        $club = Club::where('COM_ID_RESPONSABLE', $user->COM_ID)->first();
        if (!$club || $club->CLU_ID != $raid->CLU_ID) {
            return redirect()->back()->with('error', 'Vous n\'êtes pas autorisé à supprimer ce raid.');
        }

        Contenir::where('CLU_ID', $raid->CLU_ID)->where('RAI_ID', $raid->RAI_ID)->delete();
        Teams::where('clu_id', $raid->CLU_ID)->where('rai_id', $raid->RAI_ID)->delete();
        Race::where('CLU_ID', $raid->CLU_ID)->where('RAI_ID', $raid->RAI_ID)->delete();
        $raid->delete();

        return redirect()->back()->with('success', 'Raid supprimé.');
    }
}
