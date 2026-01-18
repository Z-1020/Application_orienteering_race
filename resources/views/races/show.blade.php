@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Détails de la course : {{ $race->COU_NOM }}</h1>

    <p><strong>Raid :</strong> {{ $raid->RAI_NOM }}</p>
    <p><strong>Club :</strong> {{ $raid->club->CLU_NOM ?? $raid->CLU_ID }}</p>
    <p><strong>Type :</strong> {{ $race->raceType->COU_TYP_NOM ?? '-' }}</p>
    <p><strong>Organisateur :</strong> {{ $race->COM_ID_ORGANISATEUR_COURSE }}</p>
    <p><strong>Date de début :</strong> {{ $race->COU_DATE_DEBUT }}</p>
    <p><strong>Date de fin :</strong> {{ $race->COU_DATE_FIN }}</p>
    <p><strong>Durée :</strong> {{ $race->COU_DUREE }} minutes</p>
    <p><strong>Participants min/max :</strong> {{ $race->COU_NB_PARTICIPANT_MIN ?? '-' }} / {{ $race->COU_NB_PARTICIPANT_MAX ?? '-' }}</p>
    <p><strong>Équipes min/max :</strong> {{ $race->COU_NB_EQUIPE_MIN ?? '-' }} / {{ $race->COU_NB_EQUIPE_MAX ?? '-' }}</p>
    <p><strong>Difficulté :</strong> {{ $race->COU_DIFFICULTE ?? '-' }}</p>
    <p><strong>Prix repas :</strong> {{ $race->COU_PRIX_REPAS ?? '-' }}</p>
    <p><strong>Puce obligatoire :</strong> {{ $race->COU_PUCE_OBLIGATOIRE ? 'Oui' : 'Non' }}</p>

    <h2>Catégories d'âge</h2>
    @if($race->ageCategories->isEmpty())
        <p>Aucune catégorie.</p>
    @else
        <ul>
            @foreach($race->ageCategories as $cat)
                <li>{{ $cat->CAT_AGE_MIN }} à {{ $cat->CAT_AGE_MAX }} ans</li>
            @endforeach
        </ul>
    @endif

    <a href="{{ url()->previous() }}" class="btn btn-secondary">Retour aux courses</a>
</div>
@endsection
