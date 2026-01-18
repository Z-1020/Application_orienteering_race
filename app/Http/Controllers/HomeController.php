<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Raid;

class HomeController extends Controller
{
    public function index()
    {
        $nextRaids = Raid::where('RAI_DATE_DEBUT', '>=', now())
                          ->orderBy('RAI_DATE_DEBUT', 'asc')
                          ->take(3)
                          ->get();
        
        return view('home', [
            'nextRaids' => $nextRaids
        ]);
    }

}
