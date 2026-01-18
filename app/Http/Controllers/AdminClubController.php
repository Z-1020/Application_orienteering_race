<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\Club;
use App\Models\Member;
use App\Models\Adherent;
use Illuminate\Support\Facades\DB;

class AdminClubController extends Controller
{
    public function index(Request $request)
    {
        // Récupère tous les clubs et tous les membres
        $clubs = Club::with('responsable.compte')->get();
        $members = Member::with('compte')->get();

        // Recherche simple côté membre
        $search = $request->get('search', '');
        if ($search !== '') {
            $searchLower = strtolower($search);
            $members = $members->filter(fn($member) => str_contains(strtolower($member->compte->COM_NOM ?? ''), $searchLower));
        }

        return view('Admin.adminClub', compact('clubs', 'members', 'search'));
    }

    public function update(Request $request, Club $club)
    {
        $data = $request->validate([
            'CLU_NOM' => 'required|string|max:255',
            'COM_ID_NOUVEAU_RESPONSABLE' => 'nullable|exists:vik_compte,COM_ID',
            'CLU_ADRESSE' => 'required|string|max:255',
            'CLU_CODE_POST' => 'required|numeric',
        ]);

        if (!empty($data['COM_ID_NOUVEAU_RESPONSABLE'])) {
            $data['COM_ID_RESPONSABLE'] = $data['COM_ID_NOUVEAU_RESPONSABLE'];
        }

        unset($data['COM_ID_NOUVEAU_RESPONSABLE']);

        $club->update($data);

        return redirect()->back()->with('success', 'Club mis à jour');
    }

    public function destroyClub($id)
    {
        $club = Club::findOrFail($id);
        $club->delete();

        return redirect()->back()->with('success', 'Club supprimé');
    }

    // --- Validation clubs en attente ---
    public function enAttente()
    {
        $clubs = DB::table('VIK_CLUB')
            ->join('VIK_COMPTE', 'VIK_CLUB.COM_ID_RESPONSABLE', '=', 'VIK_COMPTE.COM_ID')
            ->leftJoin('VIK_ADHERENT', 'VIK_ADHERENT.COM_ID', '=', 'VIK_COMPTE.COM_ID')
            ->select(
                'VIK_CLUB.*',
                'VIK_COMPTE.COM_NOM',
                'VIK_COMPTE.COM_PRENOM',
                'VIK_ADHERENT.ADH_NUM_LICENCIE'
            )
            ->where('VIK_CLUB.CLU_STATUS', 'ATTENTE')
            ->get();

        return view('Admin.adminClubValidation', compact('clubs'));
    }

    public function valider($id)
    {
        $club = Club::findOrFail($id);
        $club->update([
            'CLU_STATUS' => 'VALIDE',
            'CLU_DATE_DECISION' => Carbon::now()
        ]);

        return redirect()->back()->with('success', 'Club validé');
    }

    public function refuser($id)
    {
        Club::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Club refusé');
    }
}
