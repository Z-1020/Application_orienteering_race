<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Teams;
use Illuminate\Support\Facades\Auth;

class TeamsController extends Controller
{
    protected $course;

    // Display all teams grouped by team name
    public function index() {
        $teamsGrouped = DB::table('VIK_COURSE')
            ->join('VIK_EQUIPE', function ($join) {
                $join->on('VIK_COURSE.RAI_ID', '=', 'VIK_EQUIPE.RAI_ID')
                     ->on('VIK_EQUIPE.COU_ID', '=', 'VIK_COURSE.COU_ID');
            })
            ->join('VIK_CONTENIR', function ($join) {
                $join->on('VIK_CONTENIR.EQU_ID', '=', 'VIK_EQUIPE.EQU_ID')
                     ->on('VIK_CONTENIR.CLU_ID', '=', 'VIK_EQUIPE.CLU_ID')
                     ->on('VIK_CONTENIR.RAI_ID', '=', 'VIK_EQUIPE.RAI_ID')
                     ->on('VIK_CONTENIR.COU_ID', '=', 'VIK_EQUIPE.COU_ID');
            })
            ->join('VIK_COMPTE', 'VIK_COMPTE.COM_ID', '=', 'VIK_CONTENIR.COM_ID')
            ->join('VIK_RAID', 'VIK_RAID.RAI_ID', '=', 'VIK_COURSE.RAI_ID')
            ->join('VIK_CLUB', 'VIK_CLUB.CLU_ID', '=', 'VIK_COURSE.CLU_ID')
            ->select(
                'VIK_EQUIPE.EQU_NOM',
                'VIK_RAID.RAI_NOM',
                'VIK_CLUB.CLU_NOM',
                'VIK_COURSE.COU_NOM',
                'VIK_COMPTE.COM_NOM',
                'VIK_COMPTE.COM_PRENOM'
            )
            ->get()
            ->groupBy('EQU_NOM'); 

        return view('teams.index', ['teams' => $teamsGrouped]);
    }

    // Show form to create a new team
    public function create(Request $request)
    {
        $clu_id = $request->query('clu_id');
        $rai_id = $request->query('rai_id');
        $cou_id = $request->query('cou_id');

        $club = \App\Models\Club::where('CLU_ID', $clu_id)->first();
        $raid = \App\Models\Raid::where('CLU_ID', $clu_id)->where('RAI_ID', $rai_id)->first();
        $course = \App\Models\Race::where('CLU_ID', $clu_id)
            ->where('RAI_ID', $rai_id)
            ->where('COU_ID', $cou_id)
            ->first();

        $ageLimits = DB::table('VIK_CONCERNER')
            ->join('VIK_CATEGORIE_AGE', 'VIK_CONCERNER.CAT_AGE_ID', '=', 'VIK_CATEGORIE_AGE.CAT_AGE_ID')
            ->where('VIK_CONCERNER.CLU_ID', $clu_id)
            ->where('VIK_CONCERNER.RAI_ID', $rai_id)
            ->where('VIK_CONCERNER.COU_ID', $cou_id)
            ->select(
                DB::raw('MIN(CAT_AGE_MIN) as AGE_MIN'),
                DB::raw('MAX(CAT_AGE_MAX) as AGE_MAX')
            )
            ->first();

        $maxParticipants = $course->COU_NB_MAX_PAR_EQUIPE;

        return view('teams.create', compact(
            'clu_id', 'rai_id', 'cou_id', 'raid', 'course', 'club', 
            'maxParticipants', 'ageLimits'
        ));
    }

    // Search for runners by name or first name (autocomplete)
    public function searchRunners(Request $request)
    {
        $term = $request->query('term');
        
        $runners = DB::table('VIK_COMPTE')
            ->where('COM_NOM', 'LIKE', '%' . $term . '%')
            ->orWhere('COM_PRENOM', 'LIKE', '%' . $term . '%')
            ->select('COM_ID', 'COM_NOM', 'COM_PRENOM')
            ->limit(10)
            ->get();

        return response()->json($runners);
    }

    // Store a new team
    public function store(Request $request)
    {
        $request->validate([
            'equ_nom' => 'required|string|max:255',
            'clu_id'  => 'required|integer',
            'rai_id'  => 'required|integer',
            'cou_id'  => 'required|integer',
            'members' => 'required|array|min:1',
        ]);

        $loggedUserId = Auth::id();

        $nouvelleCourse = DB::table('VIK_COURSE')
            ->where('CLU_ID', $request->clu_id)
            ->where('RAI_ID', $request->rai_id)
            ->where('COU_ID', $request->cou_id)
            ->first();

        $membreEnConflit = DB::table('VIK_CONTENIR')
            ->join('VIK_COURSE', function ($join) {
                $join->on('VIK_CONTENIR.CLU_ID', '=', 'VIK_COURSE.CLU_ID')
                    ->on('VIK_CONTENIR.RAI_ID', '=', 'VIK_COURSE.RAI_ID')
                    ->on('VIK_CONTENIR.COU_ID', '=', 'VIK_COURSE.COU_ID');
            })
            ->whereIn('VIK_CONTENIR.COM_ID', $request->members)
            ->where('VIK_CONTENIR.COUREUR_STATUS', '!=', 'REFUSE')
            ->where(function ($query) use ($nouvelleCourse) {
                $query->where('VIK_COURSE.COU_DATE_DEBUT', '<=', $nouvelleCourse->COU_DATE_FIN)
                    ->where('VIK_COURSE.COU_DATE_FIN', '>=', $nouvelleCourse->COU_DATE_DEBUT);
            })
            ->select('VIK_CONTENIR.COM_ID')
            ->first();

        if ($membreEnConflit) {
            $nomMembre = DB::table('VIK_COMPTE')->where('COM_ID', $membreEnConflit->COM_ID)->value('COM_NOM');
            return redirect()->back()
                ->withInput()
                ->withErrors(['equ_nom' => "Le participant $nomMembre est déjà inscrit à une course sur cette période."]);
        }

        $team = Teams::createTeam(
            $request->clu_id, 
            $request->rai_id, 
            $request->cou_id, 
            $loggedUserId, 
            $request->equ_nom
        );

        DB::table('VIK_EQUIPE')
            ->where('CLU_ID', $request->clu_id)
            ->where('RAI_ID', $request->rai_id)
            ->where('COU_ID', $request->cou_id)
            ->where('EQU_ID', $team->equ_id)
            ->update([
                'COM_ID_CREATEUR' => $loggedUserId,
                'EQU_NOM' => $team->equ_nom,   
                'EQU_DATE_DEMANDE' => now(),
                'EQU_STATUS' => 'ATTENTE',
                'EQU_POINTS' => 0,
                'EQU_TEMPS' => '00:00:00'
            ]);

        $categoriesAutorisees = DB::table('VIK_CONCERNER')
            ->where('CLU_ID', $request->clu_id)
            ->where('RAI_ID', $request->rai_id)
            ->where('COU_ID', $request->cou_id)
            ->pluck('CAT_AGE_ID')
            ->toArray();

        $currentYear = 2026;

        foreach ($request->members as $memberId) {
            $numeroPPS = $request->pps[$memberId] ?? null;

            $compte = DB::table('VIK_COMPTE')->where('COM_ID', $memberId)->first();
            $catAgeId = 7; 

            if ($compte && !empty($compte->COM_DATE_NAISSANCE)) {
                $birthYear = date('Y', strtotime($compte->COM_DATE_NAISSANCE));
                $age = $currentYear - $birthYear;

                $category = DB::table('VIK_CATEGORIE_AGE')
                    ->where('CAT_AGE_MIN', '<=', $age)
                    ->where('CAT_AGE_MAX', '>=', $age)
                    ->first();
                if ($category) { $catAgeId = $category->CAT_AGE_ID; }

                if (!$category || !in_array($category->CAT_AGE_ID, $categoriesAutorisees)) {
                    $nom = $compte->COM_NOM . ' ' . $compte->COM_PRENOM;
                    $catNom = $category ? $category->CAT_AGE_ID : "Inconnue";
                    
                    return redirect()->back()
                        ->withInput()
                        ->withErrors(['members' => "Le participant $nom (Cat: $catNom) n'a pas l'âge requis pour cette course."]);
                }
            }

            DB::table('VIK_COUREUR')->updateOrInsert(
                ['COM_ID' => $memberId],
                ['CAT_AGE_ID' => $catAgeId] 
            );

            DB::table('VIK_CONTENIR')->insert([
                'CLU_ID' => $request->clu_id,
                'RAI_ID' => $request->rai_id,
                'COU_ID' => $request->cou_id,
                'EQU_ID' => $team->equ_id,
                'COM_ID' => $memberId,
                'COUR_PPS' => $numeroPPS,
                'COUREUR_DATE_DEMANDE' => now(),
                'COUREUR_STATUS' => 'ATTENTE',
            ]);
        }

        return redirect()->route('teams.index')->with('success', 'Équipe créée avec succès !');
    }
    
}
