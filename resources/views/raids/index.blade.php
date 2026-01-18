@extends('layouts.app')

@section('content')

<form method="POST" action="{{ route('raids.filter') }}" class="mb-6">
    @csrf
    <table class="table">
        <thead>
            <tr>
                <th>Sélection</th>
                <th>Tranche d'âge</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($ageCategories as $categorie)
                <tr>
                    <td>
                        <input type="checkbox"
                            name="age_categories[]"
                            value="{{ $categorie->CAT_AGE_ID }}"
                            class="form-check-input">
                    </td>
                    <td>
                        {{ $categorie->CAT_AGE_MIN }} - {{ $categorie->CAT_AGE_MAX }} ans
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
           
    <table class="table">
        <thead>
            <tr>
                <th>Sélection</th>
                <th>Type d'activité</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($raceTypes as $raceType)
                <tr>
                    <td>
                        <input type="checkbox"
                            name="race_types[]"
                            value="{{ $raceType->COU_TYP_ID }}"
                            class="form-check-input">
                    </td>
                    <td>
                        {{ $raceType->COU_TYPE_LIBELLE }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>


    <div class="mb-4">
        <label for="distance_max" class="form-label">Distance maximale (km)</label>
        <input type="number" step="0.1" name="distance_max" id="distance_max" class="form-control" placeholder="Ex : 50">
    </div>

    <button type="submit" class="btn btn-primary mt-4">Filtrer les courses</button>
</form>

<h1>Découvrez nos RAIDS</h1>
<p style="margin-bottom: 2rem;">Explorez notre sélection de raids sportifs en pleine nature.</p>

<div class="grid grid-cols-1 md:grid-cols-2" style="gap: 1.5rem;">
    @if ($raids->isEmpty())
        <p>Aucun résultat</p>
    @endif
    @foreach ($raids as $raid)
    
    <div class="card" data-lat="{{ $raid->latitude }}" data-lng="{{ $raid->longitude }}">
        <h2 style="color: var(--color-forest-700); margin-bottom: 0.5rem;">{{ $raid->RAI_NOM }}</h2>
        <p><strong>📍 Lieu :</strong> {{ $raid->RAI_LIEU ?? 'Non précisé' }}</p>
        <p><strong>📅 Dates :</strong> {{ $raid->RAI_DATE_DEBUT }} - {{ $raid->RAI_DATE_FIN }}</p>

        <p class="distance"><strong>Distance :</strong> -- km</p>

        
        @foreach($raid->races as $race)
            <p><strong>{{ $race->COU_NOM }}</strong></p>
            <p>Tranches d'âge :
                @foreach($race->ageCategories as $cat)
                    {{ $cat->CAT_AGE_MIN }} - {{ $cat->CAT_AGE_MAX }} ans
                    @if(!$loop->last), @endif
                @endforeach
            </p>
            <p>Type : {{ $race->raceType->COU_TYPE_LIBELLE }}</p>
        @endforeach

        <a href="{{ route('races.index', ['clu_id' => $raid->CLU_ID, 'rai_id' => $raid->RAI_ID]) }}">
    <button type="button" class="btn">
        Détails du raid
    </button>
</a>

    </div>
    @endforeach
</div>

@endsection
