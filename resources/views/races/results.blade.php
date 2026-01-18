@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Résultats - {{ $race->COU_NOM }}</h1>
        <a href="{{ route('view.participants', [$idClub, $idRaid, $idRace]) }}" class="btn btn-secondary">
            ← Retour aux participants
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title">Informations de la course</h5>
            <p><strong>Date de début :</strong> {{ \Carbon\Carbon::parse($race->COU_DATE_DEBUT)->format('d/m/Y H:i') }}</p>
            <p><strong>Date de fin :</strong> {{ \Carbon\Carbon::parse($race->COU_DATE_FIN)->format('d/m/Y H:i') }}</p>
        </div>
    </div>

    @if(empty($results))
        <div class="alert alert-info">Aucune équipe validée pour cette course.</div>
    @else
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Classement des équipes</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped table-hover mb-0">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 80px;">Rang</th>
                                <th style="width: 120px;">Dossard</th>
                                <th>Nom de l'équipe</th>
                                <th class="text-center" style="width: 150px;">Temps</th>
                                <th class="text-center" style="width: 100px;">Points</th>
                                <th class="text-center" style="width: 150px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($results as $result)
                                <tr>
                                    <td class="text-center">
                                        @if($result['rank'])
                                            <span class="badge bg-success fs-6">{{ $result['rank'] }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>{{ $result['team']->EQU_DOSSARD ?? '-' }}</td>
                                    <td><strong>{{ $result['team']->EQU_NOM }}</strong></td>
                                    <td class="text-center">
                                        @if($result['team']->EQU_TEMPS)
                                            <span class="badge bg-info">{{ $result['team']->EQU_TEMPS }}</span>
                                        @else
                                            <span class="text-muted">Non renseigné</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($result['team']->EQU_POINTS !== null)
                                            <strong>{{ $result['team']->EQU_POINTS }}</strong>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('race.result.edit', [$idClub, $idRaid, $idRace, $result['team']->EQU_ID]) }}" 
                                           class="btn btn-sm btn-warning">
                                             Modifier
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="alert alert-info mt-4">
            <strong> Note :</strong> Le temps est unique pour chaque équipe. Les équipes sans temps ne sont pas classées.
        </div>
    @endif
</div>
@endsection
