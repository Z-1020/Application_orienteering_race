@extends('layouts.app')

@section('content')

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Participants de la course</h1>
        <a href="{{ route('race.results', [$idClub, $idRaid, $idRace]) }}" 
           class="btn btn-primary">
             Voir les résultats
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Section des équipes en attente --}}
    @php
        $equipesAttente = collect($team)->where('EQU_STATUS', 'ATTENTE');
        $equipesValide = collect($team)->where('EQU_STATUS', 'VALIDE');
    @endphp

    @if($equipesAttente->isNotEmpty())
        <div class="mb-4">
            <h3 class="text-warning mb-3"> Équipes en attente de validation ({{ $equipesAttente->count() }})</h3>
        </div>
    @endif
    
    @foreach($runnerByTeam as $equId => $runners)

    @php
        $foundTeam = collect($team)->firstWhere('EQU_ID', $equId);
    @endphp

    @if($foundTeam && $foundTeam['EQU_STATUS'] == 'ATTENTE')
        <div class="p-4 border rounded shadow-sm m-4 border-warning" style="background-color: #fff3cd;">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="text-4xl mb-0">Équipe : {{ $foundTeam['EQU_NOM'] }}</h2>
                <span class="badge bg-warning text-dark fs-6">EN ATTENTE</span>
            </div>

            <p><strong>Dossard :</strong> {{ $foundTeam['EQU_DOSSARD'] ?? '-' }}</p>
            <p><strong>Responsable de l'équipe :</strong> {{ $foundTeam['COM_NOM'] ?? 'non renseigné' }} {{ $foundTeam['COM_PRENOM'] ?? 'non renseigné' }}</p>
            

           

            <div class="d-flex justify-content-center gap-2 mt-4">
                <form action="{{ route('validate.team', [$idClub, $idRaid, $idRace, $equId]) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-success">✓ Valider l'équipe</button>
                </form>
                <form action="{{ route('delete.team', [$idClub, $idRaid, $idRace, $equId]) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-danger">✗ Supprimer l'équipe</button>
                </form>
            </div>

        </div>
    @endif

@endforeach

    {{-- Section des équipes validées --}}
    @if($equipesValide->isNotEmpty())
        <div class="mt-5 mb-4">
            <h3 class="text-success mb-3">✓ Équipes validées ({{ $equipesValide->count() }})</h3>
        </div>
    @endif

    @foreach($runnerByTeam as $equId => $runners)

    @php
        $foundTeam = collect($team)->firstWhere('EQU_ID', $equId);
    @endphp

    @if($foundTeam && $foundTeam['EQU_STATUS'] == 'VALIDE')
        <div class="p-4 border rounded shadow-sm m-4 border-success" style="background-color: #d1e7dd;">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="text-4xl mb-0">Équipe : {{ $foundTeam['EQU_NOM'] }}</h2>
                <span class="badge bg-success fs-6">VALIDÉE</span>
            </div>

            <p><strong>Dossard :</strong> {{ $foundTeam['EQU_DOSSARD'] ?? '-' }}</p>
            <p><strong>Responsable de l'équipe :</strong> {{ $foundTeam['COM_NOM'] ?? 'non renseigné' }} {{ $foundTeam['COM_PRENOM'] ?? 'non renseigné' }}</p>
            

           

        </div>
    @endif

@endforeach

    
   

</div>

@endsection
