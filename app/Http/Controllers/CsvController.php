<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CsvController extends Controller
{
    public function import(Request $request, $cluId, $raiId, $couId)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt',
        ]);

        $file = $request->file('csv_file');
        $rows = array_filter(
            array_map(fn($r) => str_getcsv($r, ';'), file($file->getRealPath())),
            fn($r) => !empty(array_filter($r))
        );
        $header = array_shift($rows);

        foreach ($rows as $row) {
            $teamName = $row[2] ?? null;
            $temps = $row[4] ?? null;
            $pts   = $row[5] ?? null;

            if (empty($teamName)) continue;

            $teamExists = DB::table('VIK_EQUIPE')
                ->where('CLU_ID', $cluId)
                ->where('RAI_ID', $raiId)
                ->where('COU_ID', $couId)
                ->where('EQU_NOM', $teamName)
                ->exists();

            if ($teamExists) {
                DB::table('VIK_EQUIPE')
                    ->where('CLU_ID', $cluId)
                    ->where('RAI_ID', $raiId)
                    ->where('COU_ID', $couId)
                    ->where('EQU_NOM', $teamName)
                    ->update([
                        'EQU_POINTS' => $pts !== null ? $pts : null,
                        'EQU_TEMPS'  => $temps !== null ? $temps : null,
                    ]);
            }
        }

        return redirect()->back()->with('success', 'CSV importé avec succès !');
    }
}
