@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Modifier les résultats - {{ $team->EQU_NOM }}</h1>
        <a href="{{ route('race.results', [$idClub, $idRaid, $idRace]) }}" class="btn btn-secondary">
            ← Retour aux résultats
        </a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Résultats de l'équipe</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('race.result.update', [$idClub, $idRaid, $idRace, $team->EQU_ID]) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label"><strong>Équipe :</strong></label>
                    <p class="form-control-plaintext">{{ $team->EQU_NOM }}</p>
                </div>

                <div class="mb-3">
                    <label class="form-label"><strong>Dossard :</strong></label>
                    <p class="form-control-plaintext">{{ $team->EQU_DOSSARD ?? 'Non attribué' }}</p>
                </div>

                <div class="mb-3">
                    <label for="equ_temps" class="form-label"><strong>Temps de l'équipe *</strong></label>
                    <input type="text" 
                           class="form-control @error('equ_temps') is-invalid @enderror" 
                           id="equ_temps" 
                           name="equ_temps" 
                           value="{{ old('equ_temps', $team->EQU_TEMPS) }}"
                           placeholder="HH:MM:SS (ex: 02:35:42)"
                           required>
                    <small class="form-text text-muted">Format : HH:MM:SS (heures:minutes:secondes)</small>
                    @error('equ_temps')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="equ_points" class="form-label"><strong>Points</strong></label>
                    <input type="number" 
                           class="form-control @error('equ_points') is-invalid @enderror" 
                           id="equ_points" 
                           name="equ_points" 
                           value="{{ old('equ_points', $team->EQU_POINTS) }}"
                           min="0"
                           step="1">
                    <small class="form-text text-muted">Nombre entier de points (optionnel)</small>
                    @error('equ_points')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="alert alert-info">
                    <strong> Note :</strong> Le temps est unique pour toute l'équipe. Il sera utilisé pour calculer le classement automatiquement.
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                         Enregistrer les résultats
                    </button>
                    <a href="{{ route('race.results', [$idClub, $idRaid, $idRace]) }}" class="btn btn-secondary">
                        Annuler
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
