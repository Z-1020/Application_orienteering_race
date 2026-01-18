<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Exception;

class AdminUsersController extends Controller
{

    public function indexUsers()
    {
        $users = DB::table('VIK_COMPTE as c')
            ->leftJoin('VIK_ADHERENT as a', 'c.COM_ID', '=', 'a.COM_ID')
            ->leftJoin('VIK_ADHERER as ar', 'c.COM_ID', '=', 'ar.COM_ID')
            ->leftJoin('VIK_CLUB as cl', 'ar.CLU_ID', '=', 'cl.CLU_ID')
            ->select(
                'c.COM_ID',
                'c.COM_NOM',
                'c.COM_PRENOM',
                'c.COM_PSEUDO',
                'c.COM_MAIL',
                'c.COM_TELEPHONE',
                'c.COM_ADRESSE',
                'c.COM_DATE_NAISSANCE',
                DB::raw('GROUP_CONCAT(DISTINCT cl.CLU_NOM SEPARATOR ", ") as clubs'),
                'a.ADH_NUM_PUCE',
                'a.ADH_NUM_LICENCIE'
            )
            ->groupBy('c.COM_ID','c.COM_NOM','c.COM_PRENOM','c.COM_PSEUDO','c.COM_MAIL','c.COM_TELEPHONE','c.COM_ADRESSE','c.COM_DATE_NAISSANCE','a.ADH_NUM_PUCE','a.ADH_NUM_LICENCIE')
            ->get();

        return view('Admin.adminUsers', ['users' => $users]);
    }

    public function destroy($id)
    {
        try {
            $isAdmin = DB::table('VIK_ADMINISTRATEUR')->where('COM_ID', $id)->exists();
            if ($isAdmin) {
                $adminCount = DB::table('VIK_ADMINISTRATEUR')->count();
                Log::debug('Admin count check', ['id' => $id, 'adminCount' => $adminCount]);
                if ($adminCount <= 1) {
                    return redirect()->back()->with('error', "Impossible de supprimer le dernier administrateur.");
                }
            }
            $hasActiveRaid = DB::table('VIK_RAID')->where('COM_ID_ORGANISATEUR_RAID', $id)->where(function($q) {
                $q->whereNull('RAI_DATE_FIN')->orWhere('RAI_DATE_FIN', '>', now());
            })->exists();
            if ($hasActiveRaid) {
                return redirect()->back()->with('error', 'Impossible de supprimer : l\'utilisateur organise au moins un raid non terminé.');
            }

            $hasActiveCourse = DB::table('VIK_COURSE')->where('COM_ID_ORGANISATEUR_COURSE', $id)->where(function($q) {
                $q->whereNull('COU_DATE_FIN')->orWhere('COU_DATE_FIN', '>', now());
            })->exists();
            if ($hasActiveCourse) {
                return redirect()->back()->with('error', 'Impossible de supprimer : l\'utilisateur organise au moins une course non terminée.');
            }
            DB::transaction(function() use ($id) {
                $clubsAsResponsable = DB::table('VIK_CLUB')->where('COM_ID_RESPONSABLE', $id)->get();
                if ($clubsAsResponsable->isNotEmpty()) {
                    throw new Exception("Impossible de supprimer ce compte car il est responsable de " . $clubsAsResponsable->count() . " club(s). Veuillez d'abord réassigner ou supprimer ces clubs.");
                }
                DB::table('VIK_CONTENIR')->where('COM_ID', $id)->delete();
                DB::table('VIK_COUREUR')->where('COM_ID', $id)->delete();
                DB::table('VIK_ADHERER')->where('COM_ID', $id)->delete();
                DB::table('VIK_ADHERENT')->where('COM_ID', $id)->delete();

                DB::table('VIK_CREATEUR_EQUIPE')->where('COM_ID', $id)->delete();
                DB::table('VIK_EQUIPE')->where('COM_ID_CREATEUR', $id)->delete();
                DB::table('VIK_ADMINISTRATEUR')->where('COM_ID', $id)->delete();

                DB::table('VIK_COMPTE')->where('COM_ID', $id)->delete();
            });

            Log::info('Admin delete succeeded', ['id' => $id]);
            return redirect()->route('admin.users.index')->with('success', 'Compte supprimé.');
        } catch (Exception $e) {
            Log::error('Admin delete failed', ['id' => $id, 'message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return redirect()->back()->with('error', "Impossible de supprimer le compte : ".$e->getMessage());
        }
    }

    public function edit($id){
        $user = DB::table('VIK_COMPTE as c')
            ->leftJoin('VIK_ADHERENT as a', 'c.COM_ID', '=', 'a.COM_ID')
            ->select(
                'c.COM_ID',
                'c.COM_NOM',
                'c.COM_PRENOM',
                'c.COM_PSEUDO',
                'c.COM_MAIL',
                'c.COM_TELEPHONE',
                'c.COM_ADRESSE',
                'c.COM_DATE_NAISSANCE',
                'a.ADH_NUM_PUCE',
                'a.ADH_NUM_LICENCIE'
            )
            ->where('c.COM_ID', $id)
            ->first();

        if (! $user) {
            return redirect('/adminUsers')->with('error', 'Utilisateur introuvable.');
        }
        $clubIds = DB::table('VIK_ADHERER')->where('COM_ID', $id)->pluck('CLU_ID')->toArray();
        $clubs = DB::table('VIK_CLUB')->select('CLU_ID','CLU_NOM')->get();
        return view('Admin.adminUserEdit', compact('user','clubIds','clubs'));
    }



    public function update(Request $request, $id){
        $data = $request->validate([
            'COM_NOM' => 'required|string|max:32',
            'COM_PRENOM' => 'nullable|string|max:32',
            'COM_PSEUDO' => 'nullable|string|max:64',
            'COM_MAIL' => 'nullable|email|max:255',
            'COM_TELEPHONE' => 'nullable|string|max:32',
            'COM_ADRESSE' => 'nullable|string|max:128',
            'COM_DATE_NAISSANCE' => 'nullable|date',
            'COM_MDP' => 'nullable|string|min:6|confirmed',
            'ADH_NUM_PUCE' => 'nullable|numeric',
            'ADH_NUM_LICENCIE' => 'nullable|numeric',
            'clubs' => 'nullable|array',
            'clubs.*' => 'integer',
        ]);

        DB::transaction(function() use ($id, $data) {
            $compteData = [
                'COM_NOM' => $data['COM_NOM'],
                'COM_PRENOM' => $data['COM_PRENOM'] ?? null,
                'COM_PSEUDO' => $data['COM_PSEUDO'] ?? null,
                'COM_MAIL' => $data['COM_MAIL'] ?? null,
                'COM_TELEPHONE' => $data['COM_TELEPHONE'] ?? null,
                'COM_ADRESSE' => $data['COM_ADRESSE'] ?? null,
                'COM_DATE_NAISSANCE' => $data['COM_DATE_NAISSANCE'] ?? null,
            ];
            if (!empty($data['COM_MDP'])) {
                $compteData['COM_MDP'] = bcrypt($data['COM_MDP']);
            }
            DB::table('VIK_COMPTE')->where('COM_ID', $id)->update($compteData);

            DB::table('VIK_ADHERENT')->updateOrInsert(
                ['COM_ID' => $id],
                [
                    'ADH_NUM_PUCE' => $data['ADH_NUM_PUCE'] ?? null,
                    'ADH_NUM_LICENCIE' => $data['ADH_NUM_LICENCIE'] ?? null,
                ]
            );

            if (array_key_exists('clubs', $data)) {
                DB::table('VIK_ADHERER')->where('COM_ID', $id)->delete();
                foreach ($data['clubs'] as $clu_id) {
                    DB::table('VIK_ADHERER')->insert([
                        'COM_ID' => $id,
                        'CLU_ID' => $clu_id,
                    ]);
                }
            }
        });

        return redirect('/adminUsers')->with('success', 'Utilisateur mis à jour.');
    }
}
