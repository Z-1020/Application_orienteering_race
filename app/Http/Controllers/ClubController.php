<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Club;
use App\Models\Account;
use App\Models\Adherent;
use App\Models\Member;
use Illuminate\Support\Facades\Auth;

class ClubController extends Controller
{
    // --- Display a club and its members ---
    public function index(Request $request, $id)
    {
        $club = Club::with('responsable.compte')->findOrFail($id);

        $members = Member::with('compte')
            ->where('CLU_ID', $id)
            ->get();

        $search = $request->get('search', '');
        if ($search !== '') {
            $searchLower = strtolower($search);
            $members = $members->filter(function ($member) use ($searchLower) {
                return str_contains(strtolower($member->compte->COM_NOM ?? ''), $searchLower);
            });
        }

        return view('Admin.club', compact('club', 'members', 'search'));
    }

    // --- Show form to create a new club ---
    public function create()
    {
        $adherentExists = Adherent::where('COM_ID', Auth::user()->COM_ID)->exists();

        return view('club.create', compact('adherentExists'));
    }

    // --- Store a newly created club ---
    public function store(Request $request)
    {
        $data = $request->validate([
            'CLU_NOM'       => 'required|string|max:255',
            'CLU_ADRESSE'   => 'nullable|string|max:255',
            'CLU_CODE_POST' => 'nullable|integer',
            'CLU_TELEPHONE' => 'nullable|string',
        ]);

        if (!Adherent::where('COM_ID', Auth::id())->exists()) {
            Adherent::create([
                'COM_ID' => Auth::id(),
                'ADH_NUM_LICENCIE' => $request->input('ADH_NUM_LICENCIE'),
            ]);
        }

        $club = Club::createClub(
            $data['CLU_NOM'],
            $data['CLU_ADRESSE'] ?? null,
            $data['CLU_CODE_POST'] ?? null,
            Auth::id(),
            now(),
            'ATTENTE',
            null,
            $data['CLU_TELEPHONE'] ?? null
        );

        $exists = Member::where('CLU_ID', $club->CLU_ID)
                        ->where('COM_ID', Auth::id())
                        ->exists();

        if (!$exists) {
            Member::create([
                'CLU_ID' => $club->CLU_ID,
                'COM_ID' => Auth::id(),
                'ADHERER_STATUS' => 'VALIDE',
                'ADHERER_DATE_DEMANDE' => now(),
                'ADHERER_DATE_DECISION' => now(),
            ]);
        }

        return redirect()->route('home.index');
    }

    // --- Validate a club member ---
    public function update(Request $request, $clubId, $comId)
    {
        $club = Club::findOrFail($clubId);

        $account = Auth::user();
        if ($account->COM_ID !== $club->COM_ID_RESPONSABLE) {
            abort(403);
        }

        $member = Member::where('CLU_ID', $clubId)
                        ->where('COM_ID', $comId)
                        ->firstOrFail();

        $member->update([
            'ADHERER_STATUS' => 'VALIDE',
            'ADHERER_DATE_DECISION' => now(),
        ]);

        return redirect()->back()->with('success', 'Membre validé avec succès !');
    }

    // --- Show form to add adherents to a club ---
    public function add($clubId)
    {
        $club = Club::findOrFail($clubId);

        $existingMembers = Member::where('CLU_ID', $clubId)
                                 ->pluck('COM_ID')
                                 ->toArray();

        $adherents  = Adherent::whereNotIn('COM_ID', $existingMembers)->get();

        return view('Admin.clubAdd', compact('club', 'adherents'));
    }

    // --- Add an adherent into a club ---
    public function addIntoClub(Request $request, $clubId)
    {
        $data = $request->validate([
            'COM_ID' => 'required|exists:VIK_ADHERENT,COM_ID',
        ]);

        $comId = $data['COM_ID'];

        $exists = Member::where('CLU_ID', $clubId)
                        ->where('COM_ID', $comId)
                        ->exists();

        if ($exists) {
            return redirect()->back()->with('error', 'Ce membre fait déjà partie du club.');
        }

        Member::create([
            'CLU_ID' => $clubId,
            'COM_ID' => $comId,
            'ADHERER_STATUS' => 'EN_ATTENTE',
            'ADHERER_DATE_DEMANDE' => now(),
            'ADHERER_DATE_DECISION' => null,
        ]);

        return redirect()->route('club.index', ['id' => $clubId])
                         ->with('success', 'Membre ajouté au club avec succès !');
    }

    // --- Remove a member from a club ---
    public function destroy($clubId, $comId)
    {
        $club = Club::findOrFail($clubId);

        $account = Auth::user();
        if ($account->COM_ID !== $club->COM_ID_RESPONSABLE) {
            abort(403);
        }

        $deleted = Member::where('CLU_ID', $clubId)
                         ->where('COM_ID', $comId)
                         ->delete();

        if ($deleted === 0) {
            return redirect()->back()->with('error', 'Membre introuvable dans ce club.');
        }

        return redirect()->route('club.index', ['id' => $clubId])
                         ->with('success', 'Membre supprimé du club avec succès !');
    }

    // --- Show join club form for the user ---
    public function joinForm()
    {
        $account = Auth::user();

        $adherentExists = Adherent::where('COM_ID', $account->COM_ID)->exists();

        $clubs = Club::where('CLU_STATUS', 'VALIDE')->get();

        return view('club.join', compact('clubs', 'adherentExists'));
    }

    // --- Handle join club submission ---
    public function joinSubmit(Request $request)
    {
        $account = Auth::user();

        $adherent = Adherent::find($account->COM_ID);

        $rules = [
            'club_id' => 'required|exists:VIK_CLUB,CLU_ID',
        ];

        if (!$adherent) {
            $rules['adh_num_licencie'] = 'required|integer';
        }

        $validated = $request->validate($rules);

        if (!$adherent) {
            $adherent = Adherent::create([
                'COM_ID' => $account->COM_ID,
                'ADH_NUM_LICENCIE' => $validated['adh_num_licencie'],
                'ADH_NUM_PUCE' => null,
            ]);
        }

        $exists = Member::where('CLU_ID', $validated['club_id'])
                        ->where('COM_ID', $account->COM_ID)
                        ->exists();

        if ($exists) {
            return redirect()->back()->with('error', 'Vous êtes déjà membre de ce club.');
        }

        Member::create([
            'CLU_ID' => $validated['club_id'],
            'COM_ID' => $account->COM_ID,
            'ADHERER_STATUS' => 'EN_ATTENTE',
            'ADHERER_DATE_DEMANDE' => now(),
            'ADHERER_DATE_DECISION' => null,
        ]);

        return redirect()->route('home.index')
                        ->with('success', 'Demande d’adhésion envoyée !');
    }

    // --- Manage clubs for the logged-in user ---
    public function manageClubs()
    {
        $user = Auth::user();

        $clubs = Club::where('COM_ID_RESPONSABLE', $user->COM_ID)->get();

        $clubIds = $clubs->pluck('CLU_ID')->toArray();
        $members = Member::with('compte')->whereIn('CLU_ID', $clubIds)->get();
        $membersByClub = $members->groupBy('CLU_ID');

        return view('club.manage', compact('clubs', 'membersByClub'));
    }
}
