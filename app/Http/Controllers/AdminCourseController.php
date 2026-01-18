<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

use App\Models\Race;

class AdminCourseController extends Controller
{
    public function enAttente()
    {
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        $userId = Auth::user()->COM_ID;

        $courses = DB::table('VIK_COURSE')
            ->join('VIK_CLUB', 'VIK_COURSE.CLU_ID', '=', 'VIK_CLUB.CLU_ID')
            ->join('VIK_COMPTE', 'VIK_COURSE.COM_ID_ORGANISATEUR_COURSE', '=', 'VIK_COMPTE.COM_ID')
            ->leftJoin('VIK_RAID', function ($join) {
                $join->on('VIK_COURSE.RAI_ID', '=', 'VIK_RAID.RAI_ID')
                     ->on('VIK_COURSE.CLU_ID', '=', 'VIK_RAID.CLU_ID');
            })
            ->select(
                'VIK_COURSE.*',
                'VIK_CLUB.CLU_NOM as CLUB_NOM',
                'VIK_COMPTE.COM_NOM as ORGANISATEUR_NOM',
                'VIK_COMPTE.COM_PRENOM as ORGANISATEUR_PRENOM',
                'VIK_COMPTE.COM_MAIL as ORGANISATEUR_MAIL',
                'VIK_RAID.RAI_NOM as RAID_NOM',
                'VIK_RAID.COM_ID_ORGANISATEUR_RAID as RAID_ORGANISATEUR'
            )
            ->where('VIK_COURSE.COU_STATUS', 'ATTENTE')
            ->where('VIK_RAID.COM_ID_ORGANISATEUR_RAID', $userId)
            ->get();

        return view('Admin.adminCourseValidation', compact('courses'));
    }

    public function valider($id)
    {
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        $userId = Auth::user()->COM_ID;

        $course = Race::findOrFail($id);

        $raid = DB::table('VIK_RAID')
            ->where('CLU_ID', $course->CLU_ID)
            ->where('RAI_ID', $course->RAI_ID)
            ->first();

        if (! $raid || ($raid->COM_ID_ORGANISATEUR_RAID ?? null) != $userId) {
            return redirect()->route('home.index')->with('error', "Vous n'êtes pas autorisé à valider cette course.");
        }

        $course->update([
            'COU_STATUS' => 'VALIDE',
            'COU_DATE_DECISION' => Carbon::now()
        ]);

        return redirect()->back()->with('success', 'Course validée');
    }

    public function refuser($id)
    {
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        $userId = Auth::user()->COM_ID;

        $course = Race::findOrFail($id);

        $raid = DB::table('VIK_RAID')
            ->where('CLU_ID', $course->CLU_ID)
            ->where('RAI_ID', $course->RAI_ID)
            ->first();

        if (! $raid || ($raid->COM_ID_ORGANISATEUR_RAID ?? null) != $userId) {
            return redirect()->route('home.index')->with('error', "Vous n'êtes pas autorisé à refuser cette course.");
        }

        Race::findOrFail($id)->delete();

        return redirect()->back()->with('success', 'Course refusée');
    }
}
