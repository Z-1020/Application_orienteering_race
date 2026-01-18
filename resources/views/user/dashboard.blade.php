@extends('layouts.app')

@section('content')
<div class="profile-container">

    <p>Pseudo : {{ $account->COM_PSEUDO }}</p>
    <p>Nom : {{ $account->COM_PRENOM }} {{ $account->COM_NOM }}</p>
    <p>Email : {{ $account->COM_MAIL }}</p>

   
    <h2>Mes actions</h2>
    @if ($isClubManager)
              <a href="{{ route('club.manage') }}" class="btn btn-primary">
            ➕ Gerer mes clubs
    </a>
        @endif
        @if ($isRaidManager)
              <a href="{{ route('raids.manage') }}" class="btn btn-primary">
            ➕ Gerer mes raids
        </a>
        @endif
        @if ($isRaceManager)
              <a href="{{ route('races.manage') }}" class="btn btn-primary">
            ➕ Gerer mes courses
        </a>
        @endif

    @if ($isAdherent)
              <a href="{{ route('club.join.form') }}" class="btn btn-primary">
            ➕ Demande d'adhésion à un club
        </a>
        <a href="{{ route('races.my_past') }}" class="btn btn-primary">
             Mes Anciennes Courses
        </a>
    @endif

    @if(!$isClubAdherent)
        <h2>Adherer</h2>
        <a href="{{ route('club.join.form') }}" class="btn btn-primary">
            ➕ Adhérer à un club
        </a>
    @endif


</div>
@endsection