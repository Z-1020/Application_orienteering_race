<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Race;
use App\Models\Teams;
use App\Models\Contenir;
use App\Models\Account;
use Illuminate\Support\Facades\Auth;

class RaceManagerController extends Controller
{
    public function showParticipants($idClub, $idRaid, $idRace){
        $team = Teams::join('VIK_CREATEUR_EQUIPE', 'VIK_EQUIPE.COM_ID_CREATEUR', '=', 'VIK_CREATEUR_EQUIPE.COM_ID')
            ->join('VIK_COMPTE', 'VIK_COMPTE.COM_ID', '=', 'VIK_CREATEUR_EQUIPE.COM_ID')
            ->select(
                'VIK_EQUIPE.EQU_ID', 
                'VIK_EQUIPE.EQU_NOM',
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
                'VIK_COMPTE.COM_NOM as COMPTE_COM_NOM',
                'VIK_COMPTE.COM_ID as COMPTE_COM_ID',
                'VIK_COMPTE.COM_PRENOM', 
                'VIK_EQUIPE.EQU_ID',
                'VIK_COMPTE.COM_DATE_NAISSANCE', 
                'VIK_COMPTE.COM_ADRESSE',
                'VIK_COURSE.CLU_ID', 
                'VIK_COURSE.RAI_ID', 
                'VIK_COURSE.COU_ID',
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

        return view("ViewParticipants", compact("runnerByTeam", "team", "idClub", "idRaid", "idRace"));
    }

    public function validateParticipant($idClub, $idRaid, $idRace, $idTeam, $idRunner){
        Contenir::where('COM_ID', $idRunner)
            ->where('RAI_ID', $idRaid)
            ->where('CLU_ID', $idClub)
            ->where('COU_ID', $idRace)
            ->where('EQU_ID', $idTeam)
            ->update(['COUREUR_STATUS' => 'VALIDE']);

        $runner = Account::join('VIK_CONTENIR', 'VIK_CONTENIR.COM_ID', '=', 'VIK_COMPTE.COM_ID')
            ->where("VIK_CONTENIR.COM_ID", $idRunner)
            ->where('RAI_ID', $idRaid)
            ->where('CLU_ID', $idClub)
            ->where('COU_ID', $idRace)
            ->select('COM_NOM', 'COM_PRENOM', 'RAI_ID', 'CLU_ID', 'COU_ID', 'EQU_ID')
            ->first();

        return view('ParticipantValidate', compact("runner"));
    }

    public function showEditParticipant($idClub, $idRaid, $idRace, $idTeam, $idRunner){
        $runner = Account::join('VIK_COUREUR', 'VIK_COUREUR.COM_ID', '=', 'VIK_COMPTE.COM_ID')
            ->join('VIK_CONTENIR', 'VIK_CONTENIR.COM_ID', '=', 'VIK_COMPTE.COM_ID')
            ->join('VIK_CATEGORIE_AGE', 'VIK_COUREUR.CAT_AGE_ID', '=', 'VIK_CATEGORIE_AGE.CAT_AGE_ID')
            ->select(
                'VIK_COMPTE.COM_ID', 
                'VIK_CONTENIR.COUR_PPS', 
                'VIK_COMPTE.COM_NOM', 
                'VIK_COMPTE.COM_PRENOM', 
                'VIK_CATEGORIE_AGE.CAT_AGE_MIN', 
                'VIK_CATEGORIE_AGE.CAT_AGE_MAX',
                'VIK_CONTENIR.CLU_ID', 
                'VIK_CONTENIR.RAI_ID', 
                'VIK_CONTENIR.COU_ID', 
                'VIK_CONTENIR.EQU_ID'
            )
            ->where('VIK_CONTENIR.CLU_ID', $idClub)
            ->where('VIK_CONTENIR.RAI_ID', $idRaid)
            ->where('VIK_CONTENIR.COU_ID', $idRace)
            ->where('VIK_CONTENIR.EQU_ID', $idTeam)
            ->where('VIK_CONTENIR.COM_ID', $idRunner)
            ->get();

        return view('ParticipantEdit', compact('runner'));
    }

    public function editParticipant(Request $request, $club, $raid, $race, $team, $participant){
        $pps = $request->input('cour_pps');
        $request->validate(['cour_pps' => 'required|regex:/^([0-9]{10}|[A-Z0-9\-]{5,20})$/']);
        $request = htmlspecialchars($request);

        Contenir::where('COM_ID', $participant)
            ->where('RAI_ID', $raid)
            ->where('CLU_ID', $club)
            ->where('COU_ID', $race)
            ->where('EQU_ID', $team)
            ->update(['COUR_PPS' => $pps]);

        return redirect()->route('view.participants', [$club, $raid, $race])
                 ->with('success', 'Coureur modifié avec succès');
    }

    public function deleteParticipant($club, $raid, $race, $team, $participant){
        Contenir::where('CLU_ID', $club)
            ->where('RAI_ID', $raid)
            ->where('COU_ID', $race)
            ->where('EQU_ID', $team)
            ->where('COM_ID', $participant)
            ->delete();

        return redirect()->route('view.participants', [$club, $raid, $race])
                 ->with('success', 'Coureur supprimé avec succès');
    }

    public function validateTeam($club, $raid, $race, $team){
        Contenir::where('CLU_ID', $club)
            ->where('RAI_ID', $raid)
            ->where('COU_ID', $race)
            ->where('EQU_ID', $team)
            ->update(['COUREUR_STATUS' => 'VALIDE']);

        Teams::where('CLU_ID', $club)
            ->where('RAI_ID', $raid)
            ->where('COU_ID', $race)
            ->where('EQU_ID', $team)
            ->update(['EQU_STATUS' => 'VALIDE']);

        Teams::join('VIK_COURSE', function($join){
                $join->on('VIK_COURSE.COU_ID', '=', 'VIK_EQUIPE.COU_ID')
                     ->on('VIK_COURSE.RAI_ID', '=', 'VIK_EQUIPE.RAI_ID')
                     ->on('VIK_COURSE.CLU_ID', '=', 'VIK_EQUIPE.CLU_ID');
            })
            ->where('VIK_COURSE.RAI_ID', $raid)
            ->where('VIK_COURSE.COU_ID', $race)
            ->where('VIK_EQUIPE.EQU_ID', $team)
            ->update(['EQU_DOSSARD' => Teams::raw("CONCAT(VIK_COURSE.COU_NOM, ' - ', VIK_EQUIPE.EQU_NOM)")]);

        return redirect()->route('view.participants', [$club, $raid, $race])
                 ->with('success', 'Équipe validée avec succès');
    }

    public function deleteTeam($club, $raid, $race, $team){
        Contenir::where('CLU_ID', $club)
            ->where('RAI_ID', $raid)
            ->where('COU_ID', $race)
            ->where('EQU_ID', $team)
            ->delete();

        Teams::where('CLU_ID', $club)
            ->where('RAI_ID', $raid)
            ->where('COU_ID', $race)
            ->where('EQU_ID', $team)
            ->delete();

        return redirect()->route('view.participants', [$club, $raid, $race])
                 ->with('success', 'Équipe supprimée avec succès');
    }

    public function manageRace(){
        $account = Auth::user();
        $races = Race::where('COM_ID_ORGANISATEUR_COURSE', $account->COM_ID)->get();
        return view('races.manageRace', compact('races'));
    }

    public function showResults($idClub, $idRaid, $idRace)
    {
        $race = Race::where('CLU_ID', $idClub)
            ->where('RAI_ID', $idRaid)
            ->where('COU_ID', $idRace)
            ->firstOrFail();

        if (Auth::id() != $race->COM_ID_ORGANISATEUR_COURSE) {
            abort(403, 'Vous n\'êtes pas autorisé à voir ces résultats.');
        }

        $teams = Teams::where('CLU_ID', $idClub)
            ->where('RAI_ID', $idRaid)
            ->where('COU_ID', $idRace)
            ->where('EQU_STATUS', 'VALIDE')
            ->orderByRaw('EQU_TEMPS IS NULL, EQU_TEMPS ASC')
            ->get();

        $results = [];
        $rank = 1;
        foreach ($teams as $team) {
            $teamData = [
                'team' => $team,
                'rank' => null,
            ];

            if ($team->EQU_TEMPS) {
                $teamData['rank'] = $rank;
                $rank++;
            }

            $results[] = $teamData;
        }

        return view('races.results', compact('race', 'results', 'idClub', 'idRaid', 'idRace'));
    }

    public function editResult($idClub, $idRaid, $idRace, $idTeam)
    {
        $race = Race::where('CLU_ID', $idClub)
            ->where('RAI_ID', $idRaid)
            ->where('COU_ID', $idRace)
            ->firstOrFail();

        if (Auth::id() != $race->COM_ID_ORGANISATEUR_COURSE) {
            abort(403, 'Vous n\'êtes pas autorisé à modifier ces résultats.');
        }

        $team = Teams::where('CLU_ID', $idClub)
            ->where('RAI_ID', $idRaid)
            ->where('COU_ID', $idRace)
            ->where('EQU_ID', $idTeam)
            ->firstOrFail();

        return view('races.editResult', compact('team', 'idClub', 'idRaid', 'idRace'));
    }

    public function updateResult(Request $request, $idClub, $idRaid, $idRace, $idTeam)
    {
        $race = Race::where('CLU_ID', $idClub)
            ->where('RAI_ID', $idRaid)
            ->where('COU_ID', $idRace)
            ->firstOrFail();

        if (Auth::id() != $race->COM_ID_ORGANISATEUR_COURSE) {
            abort(403, 'Vous n\'êtes pas autorisé à modifier ces résultats.');
        }

        $request->validate([
            'equ_temps' => 'required|regex:/^([0-9]{2}):([0-5][0-9]):([0-5][0-9])$/',
            'equ_points' => 'nullable|integer|min:0',
        ], [
            'equ_temps.required' => 'Le temps est obligatoire.',
            'equ_temps.regex' => 'Le temps doit être au format HH:MM:SS (ex: 02:35:42).',
            'equ_points.integer' => 'Les points doivent être un nombre entier.',
            'equ_points.min' => 'Les points ne peuvent pas être négatifs.',
        ]);

        Teams::where('CLU_ID', $idClub)
            ->where('RAI_ID', $idRaid)
            ->where('COU_ID', $idRace)
            ->where('EQU_ID', $idTeam)
            ->update([
                'EQU_TEMPS' => $request->equ_temps,
                'EQU_POINTS' => $request->equ_points,
            ]);

        return redirect()->route('race.results', [$idClub, $idRaid, $idRace])
            ->with('success', 'Résultats mis à jour avec succès !');
    }
}
