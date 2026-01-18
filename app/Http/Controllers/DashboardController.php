<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Account;
use Illuminate\Http\Request;

use App\Models\Club; 
use App\Models\Adherent;
use App\Models\Member;
use App\Models\Raid;
use App\Models\Race;

class DashboardController extends Controller
{
    public function index(){

        $account = Auth::user();

        $isClubManager = Club::where('COM_ID_RESPONSABLE', $account->COM_ID)
            ->exists();

        $isRaidManager = Raid::where('COM_ID_ORGANISATEUR_RAID', $account->COM_ID)
            ->exists();

        $isRaceManager = Race::where('COM_ID_ORGANISATEUR_COURSE', $account->COM_ID)
            ->exists();

        $isClubAdherent = Adherent::where('COM_ID', $account->COM_ID)
            ->exists();

        $isAdherent = Member::where('COM_ID',$account->COM_ID)
            ->exists();

        return view('user.dashboard', compact(
            'account',
            'isClubManager',
            'isRaidManager',
            'isRaceManager',
            'isClubAdherent',
            'isAdherent'
        ));
    }  
}