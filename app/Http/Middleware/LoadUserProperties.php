<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Models\Account;
use App\Models\Club; 
use App\Models\Race;
use App\Models\Raid;
use App\Models\Adherent;
use App\Models\Member;

class LoadUserProperties
{
    public function handle($request, Closure $next)
    {
        $isAuth = Auth::check();
        $isAdmin = false;
        $isClubManager = false;
        $isRaidManager = false;
        $isClubAdherent = false;
        $isAdherent = false;

        if($isAuth){
            $user = Auth::user();
            $isAdmin = $user->isAdmin();

            $isClubManager = Club::where('COM_ID_RESPONSABLE', $user->COM_ID)->exists();
            // détecter si l'utilisateur organise des raids
            $isRaidManager = Raid::where('COM_ID_ORGANISATEUR_RAID', $user->COM_ID)->exists();

            $isClubAdherent = Adherent::where('COM_ID', $user->COM_ID)->exists();
            $isAdherent = Member::where('COM_ID', $user->COM_ID)->exists();
        }

        view()->share(compact('isAuth', 'isAdmin', 'isClubManager', 'isRaidManager', 'isClubAdherent', 'isAdherent'));

        return $next($request);
    }
}
