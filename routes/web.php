<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

use App\Http\Middleware\LoadUserProperties;
use App\Http\Middleware\AdminMiddleware;

use App\Http\Controllers\AccountController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RaceController;
use App\Http\Controllers\RaceManagerController;
use App\Http\Controllers\RaidController;
use App\Http\Controllers\TeamsController;
use App\Http\Controllers\ClubController;
use App\Http\Controllers\AdminClubController;
use App\Http\Controllers\AdminUsersController;
use App\Http\Controllers\AdminCourseController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CsvController;
use App\Http\Controllers\HomeController;

/*
|--------------------------------------------------------------------------
| Routes accessibles à tous (avec LoadUserProperties)
|--------------------------------------------------------------------------
*/
Route::middleware([LoadUserProperties::class])->group(function () {

    /*
    |--------------------
    | Home
    |--------------------
    */
    Route::get('/', [HomeController::class, 'index'])->name('home.index');

    /*
    |--------------------
    | Logs
    |--------------------
    */
    Route::get('/logs/{file}', function (string $file) {

        if ($file === 'laravel') {
            $content = Storage::disk('laravelLog')->get('laravel.log');

            return view('log', [
                'file'   => 'laravel.log',
                'content'=> $content,
                'route'  => route('logs.delete', [
                    'disk' => 'laravelLog',
                    'file' => 'laravel.log'
                ])
            ]);
        }

        if (Storage::disk('log')->exists("$file.log")) {
            return view('log', [
                'file'   => "$file.log",
                'content'=> Storage::disk('log')->get("$file.log"),
                'route'  => null
            ]);
        }

        return "<h1>$file.log</h1><p style='color:red'>Not Found</p>";
    });

    Route::post('/logs/{disk}/{file}/delete', function (string $disk, string $file) {
        Storage::disk($disk)->delete($file);
        return Redirect::back();
    })->name('logs.delete');

    /*
    |--------------------
    | Auth (public)
    |--------------------
    */
    Route::middleware('guest')->group(function () {
        Route::get('/signup', fn () => view('connection.signupForm'))->name('signup');
        Route::post('/signup', [AccountController::class, 'CreateAccountFromPost']);

        Route::get('/login', fn () => view('connection.loginForm'))->name('login');
        Route::post('/login', [AccountController::class, 'LoginFromPost']);
    });
    

    /*
    |--------------------
    | Authenticated users
    |--------------------
    */
    Route::middleware('auth')->group(function () {

        // Session
        Route::get('/logout', [AccountController::class, 'logout'])->name('logout');

        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        /*
        | Profile
        */
        Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
        Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
        Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('password.update');

        /*
        | Club
        */
        Route::get('/club/join', [ClubController::class, 'joinForm'])->name('club.join.form');
        Route::post('/club/join', [ClubController::class, 'joinSubmit'])->name('club.join.submit');

        /*
        | Gestion courses / raids
        */
        Route::get('/raids/manage', [RaidController::class, 'manageRaids'])->name('raids.manage');
        Route::get('/raids/manage/{clu_id}/{rai_id}', [RaidController::class, 'manageShow'])->name('raids.manage.show');
        Route::get('/club/manage', [ClubController::class, 'manageClubs'])->name('club.manage');
        Route::get('/races/manageRace', [RaceManagerController::class, 'manageRace'])->name('races.manage');
    });

    /*
    |--------------------
    | Public métier
    |--------------------
    */
    Route::get('/raids', [RaidController::class, 'index'])->name('raids.index');
    Route::get('/clubs/create', [ClubController::class, 'create'])->name('clubs.create');

    Route::resource('races', RaceController::class)->except(['show']);
    Route::resource('raids', RaidController::class);
    Route::resource('teams', TeamsController::class);

    Route::get('/myRaids', [RaidController::class, 'myClubRaids'])->name('myRaids');

    Route::post('/raids/filter', [RaidController::class, 'filter'])->name('raids.filter');
    Route::get('/myRaces', [RaceController::class, 'myPastRaces'])->name('races.my_past');

    Route::get('/search-runners', [TeamsController::class, 'searchRunners'])->name('runners.search');


  

    /*
    |--------------------
    | Club management
    |--------------------
    */
    Route::get('/createClub', [ClubController::class, 'create'])
        ->name('club.create');
    Route::post('/club', [ClubController::class, 'store'])->name('club.store');
    Route::get('/club/{id}', [ClubController::class, 'index'])->name('club.index');
    Route::put('/club/{clubId}/{comId}', [ClubController::class, 'update'])->name('club.update');
    Route::post('/club/{clubId}/add', [ClubController::class, 'addIntoClub'])->name('club.storeMember');
    Route::delete('/club/{clubId}/{comId}', [ClubController::class, 'destroy'])->name('club.destroy');
    Route::get('/addClub/{clubId}', [ClubController::class, 'add'])->name('club.add');

    /*
    |--------------------
    | Admin Club
    |--------------------
    */

    Route::get('/adminClub', [AdminClubController::class, 'index'])->name('adminClub.index');

    Route::put('/adminClub/{club}', [AdminClubController::class, 'update'])->name('adminClub.update');
    Route::delete('/adminClub/{id}', [AdminClubController::class, 'destroy'])->name('adminClub.destroy');

    Route::get('/adminClub/confirm/{id}', [AdminClubController::class, 'confirm'])->name('adminClub.confirm');
    Route::get('adminClub/clubValidation', [AdminClubController::class, 'enAttente'])->name('adminClub.clubValidation');
    Route::post('adminClub/{id}/valider', [AdminClubController::class, 'valider'])->name('adminClub.valider');
    Route::post('adminClub/{id}/refuser', [AdminClubController::class, 'refuser'])->name('adminClub.refuser');


    /*
    |--------------------
    | Admin Course
    |--------------------
    */
    Route::get('/adminCourse/adminCourseValidation', [AdminCourseController::class, 'enAttente'])
        ->name('admin.courses.enattente');

    Route::post('/adminCourse/{id}/valider', [AdminCourseController::class, 'valider'])
        ->name('admin.courses.valider');

    Route::delete('/adminCourse/{id}/refuser', [AdminCourseController::class, 'refuser'])
        ->name('admin.courses.refuser');

    /*
    |--------------------
    | Homes
    |--------------------
    */
    Route::get('/adminHome', fn () => view('Admin.adminHome'))->name('adminHome.index');
    Route::get('/clubHome', fn () => view('club.clubHome'))->name('clubHome.index');

        // Raids (Club Manager)
    Route::get('/raids/create', [RaidController::class, 'create'])->name('raids.create');
    Route::post('/raids', [RaidController::class, 'store'])->name('raids.store');

    // Courses (Raid Manager)
    Route::get('/races/create', [RaceController::class, 'create'])->name('races.create');
    Route::post('/races', [RaceController::class, 'store'])->name('races.store');
    Route::get('/races/{clu_id}/{rai_id}/{COU_ID}', [RaceController::class, 'show'])->name('races.show');

  /*
    |--------------------
    | Participants / validation
    |--------------------
    */
    
Route::get(
    'viewParticipants/{club}/{raid}/{race}',
    [RaceManagerController::class, 'showParticipants']
)->name('view.participants');

Route::post(
    'validateParticipant/{club}/{raid}/{race}/{team}/{participant}',
    [RaceManagerController::class, 'validateParticipant']
)->name('validate.participant');

Route::get(
    'showEditParticipant/{club}/{raid}/{race}/{team}/{participant}',
    [RaceManagerController::class, 'showEditParticipant']
)->name('show.edit.participant');

Route::post(
    'editParticipant/{club}/{raid}/{race}/{team}/{participant}',
    [RaceManagerController::class, 'editParticipant']
)->name('edit.participant');

Route::post(
    'deleteParticipant/{club}/{raid}/{race}/{team}/{participant}',
    [RaceManagerController::class, 'deleteParticipant']
)->name('delete.participant');

Route::post(
    'validateTeam/{club}/{raid}/{race}/{team}',
    [RaceManagerController::class, 'validateTeam']
)->name('validate.team');

Route::post(
    'deleteTeam/{club}/{raid}/{race}/{team}',
    [RaceManagerController::class, 'deleteTeam']
)->name('delete.team');

/*
|--------------------
| Résultats (Race Manager)
|--------------------
*/
Route::get(
    'race/{club}/{raid}/{race}/results',
    [RaceManagerController::class, 'showResults']
)->name('race.results');

Route::get(
    'race/{club}/{raid}/{race}/result/{team}/edit',
    [RaceManagerController::class, 'editResult']
)->name('race.result.edit');

Route::put(
    'race/{club}/{raid}/{race}/result/{team}',
    [RaceManagerController::class, 'updateResult']
)->name('race.result.update');

});

/*
|--------------------------------------------------------------------------
| ADMIN ONLY
|--------------------------------------------------------------------------
*/
Route::middleware([LoadUserProperties::class, AdminMiddleware::class])->group(function () {

    Route::get('/adminUsers', [AdminUsersController::class, 'indexUsers'])
        ->name('adminUsers.index');

    Route::get('/adminUsers/user/{id}', [AdminUsersController::class, 'edit'])
        ->name('adminUsers.user.edit');

    Route::put('/adminUsers/user/{id}', [AdminUsersController::class, 'update'])
        ->name('adminUsers.user.update');

    Route::delete('/admin/users/{id}', [AdminUsersController::class, 'destroy'])
        ->name('admin.users.destroy');
});

/*
|--------------------------------------------------------------------------
| CSV IMPORTATION
|--------------------------------------------------------------------------
*/
Route::post('/csv/import/{cluId}/{raiId}/{couId}', [CsvController::class, 'import'])->name('csv.import');
