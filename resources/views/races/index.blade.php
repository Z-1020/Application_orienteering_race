@extends('layouts.app')

@section('content')
<h1>Courses du raid : {{ $raid->RAI_NOM }}</h1>



<div style="display: flex; flex-wrap: wrap; gap: 1.5rem;">
    @foreach ($races->where('COU_STATUS', 'VALIDE') as $race)
    @php
    $type = $types->firstWhere('COU_TYP_ID', $race->COU_TYP_ID);
    @endphp

    <div style="flex: 0 0 calc(33.333% - 1.5rem); background: white; border-radius: 16px; padding: 1.5rem; box-shadow: 0 4px 15px rgba(0,0,0,0.08);">
        <p><strong>Nom :</strong> {{ $race->COU_NOM }}</p>
        <p><strong>Type :</strong> {{ $type ? $type->COU_TYPE_LIBELLE : 'Inconnu' }}</p>
        <p><strong>Difficulté :</strong> {{ $race->COU_DIFFICULTE }}</p>
        <p><strong>Durée :</strong> {{ $race->COU_DUREE }} minutes</p>
        <p><strong>Categorie d'age :</strong>
            @foreach($race->ageCategories as $cat)
                    {{ $cat->CAT_AGE_MIN }} - {{ $cat->CAT_AGE_MAX }} ans
                    @if(!$loop->last), @endif
            @endforeach </p>
        <p><strong>Date & Heure :</strong> {{ \Carbon\Carbon::parse($race->COU_DATE_DEBUT)->format(' d/m/Y') }} de {{ \Carbon\Carbon::parse($race->COU_DATE_DEBUT)->format('H\hi') }} à {{ \Carbon\Carbon::parse($race->COU_DATE_FIN)->format('H\hi') }}</p>
        @if($race->COU_PUCE_OBLIGATOIRE==1)
        <p><strong>🔷 Puces obligatoires</strong></p>
        @endif

        @auth
        <a href="{{ route('teams.create', [
                'clu_id' => $race->CLU_ID, 
                'rai_id' => $race->RAI_ID, 
                'cou_id' => $race->COU_ID
            ]) }}">
            <button type="button"
                class="inline-flex items-center justify-center w-full text-sm px-4 py-2.5 font-medium text-body bg-neutral-secondary-medium border border-default-medium rounded-base shadow-xs hover:bg-neutral-tertiary-medium hover:text-heading focus:ring-4 focus:ring-neutral-tertiary focus:outline-none transition-colors">
                S'inscrire à cette course
            </button>
        </a>
        @endauth

        @if ($isOrganisateur)

        <form action="{{ route('races.destroy', $race->COU_ID) }}" method="POST"
            onsubmit="return confirm('Voulez-vous vraiment supprimer cette course ?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-secondary">
                Supprimer
            </button>
        </form>
        @endif

        @guest
        <div class="text-center p-4 bg-gray-100 rounded-base border border-dashed border-gray-400">
            <p class="text-sm text-gray-600">Vous devez être connecté pour inscrire une équipe.</p>
            <a href="{{ route('login') }}" class="text-blue-600 hover:underline font-medium">Se connecter</a>
        </div>
        @endguest
    </div>
    @endforeach
</div>

@if ($isOrganisateur)
<a href="{{ route('races.create', ['clu_id' => $clu_id, 'rai_id' => $rai_id]) }}" class="btn btn-primary mt-4 mb-4">
    Ajouter une course
</a>
@endif

<div style="text-align: center; margin-top: 2rem;">
    <button onclick="window.history.back();" class="btn btn-secondary" type="button">
        Revenir en arrière
    </button>
</div>

@endsection