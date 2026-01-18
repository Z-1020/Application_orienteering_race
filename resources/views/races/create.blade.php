@extends('layouts.app')

@section('content')
<div style="max-width: 600px; margin: auto;">

    <h1 style="text-align: center;">Créer une course</h1>

    <form method="POST" action="{{ route('races.store') }}">
        @csrf

        <h7><em>Les champs (*) sont obligatoires</em></h7>

        <div class="flex gap-4">
            <div class="flex-1">
                <label>Nom de la course*</label><br>
                <input type="text" name="COU_NOM" value="{{ old('COU_NOM') }}" required>
            </div>

            <div class="flex-1">
                <label>Difficulté*</label><br>
                <input type="text" name="COU_DIFFICULTE" value="{{ old('COU_DIFFICULTE') }}">
            </div>
        </div>

        <div class="flex gap-4">
            <div class="flex-1">
                <label>Type de course*</label><br>
                <select name="COU_TYP_ID" required>
                    @foreach($types as $type)
                    <option value="{{ $type->COU_TYP_ID }}" {{ old('COU_TYP_ID') == $type->COU_TYP_ID ? 'selected' : '' }}>
                        {{ $type->COU_TYPE_LIBELLE }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="flex-1">
                <label>Organisateur*</label><br>
                <select name="COM_ID_ORGANISATEUR_COURSE" required>
                    @foreach($adherents as $adherent)
                    <option value="{{ $adherent->COM_ID }}" {{ old('COM_ID_ORGANISATEUR_COURSE') == $adherent->COM_ID ? 'selected' : '' }}>
                        {{ $adherent->COM_NOM }} {{ $adherent->COM_PRENOM }}
                    </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="flex gap-4">
            <div class="flex-1">
                <label>Nombre minimum de participant*</label>
                <input type="number" name="COU_NB_PARTICIPANT_MIN" value="{{ old('COU_NB_PARTICIPANT_MIN') }}" required>
            </div>

            <div class="flex-1">
                <label>Nombre maximum de participant*</label>
                <input type="number" name="COU_NB_PARTICIPANT_MAX" value="{{ old('COU_NB_PARTICIPANT_MAX') }}" required>
            </div>
        </div>

        <div class="flex gap-4">
            <div class="flex-1">
                <label>Nombre minimum d'équipe*</label>
                <input type="number" name="COU_NB_EQUIPE_MIN" value="{{ old('COU_NB_EQUIPE_MIN') }}" required>
            </div>

            <div class="flex-1">
                <label>Nombre maximum d'équipe*</label>
                <input type="number" name="COU_NB_EQUIPE_MAX" value="{{ old('COU_NB_EQUIPE_MAX') }}" required>
            </div>
        </div>

        <div class="flex gap-4">
            <div class="flex-1">
                <label>Date début*</label><br>
                <input type="datetime-local" name="COU_DATE_DEBUT" value="{{ old('COU_DATE_DEBUT') }}" required>
            </div>

            <div class="flex-1">
                <label>Date fin*</label><br>
                <input type="datetime-local" name="COU_DATE_FIN" value="{{ old('COU_DATE_FIN') }}" required>
            </div>
        </div>

        <div class="flex gap-4">
            <div class="flex-1">
                <label>Nombre maximum coureurs/équipe*</label><br>
                <input type="number" name="COU_NB_MAX_PAR_EQUIPE" value="{{ old('COU_NB_MAX_PAR_EQUIPE') }}" required>
            </div>

            <div class="flex-1">
                <label>Prix du repas</label><br>
                <input type="number" name="COU_PRIX_REPAS" value="{{ old('COU_PRIX_REPAS') }}">
            </div>
        </div>

        <div class="flex gap-4">
            <div class="flex-1">
                <label>Réduction pour les licenciés (en €)</label><br>
                <input type="number" name="COU_REDUCTION_LICENCIE" value="{{ old('COU_REDUCTION_LICENCIE') }}">
            </div>

            <div style="margin-top: 1rem; flex: 1;">
                <label>Catégories d'âge*</label><br>
                <div style="display: flex; flex-wrap: wrap; gap: 1rem;">
                    @foreach($categories->chunk(2) as $chunk)
                    <div style="flex: 1 1 calc(50% - 1rem); display: flex; flex-direction: column; gap: 0.3rem;">
                        @foreach($chunk as $category)
                        <label>
                            <input type="checkbox" name="categorie_age_ids[]" value="{{ $category->CAT_AGE_ID }}"
                                {{ (is_array(old('categorie_age_ids')) && in_array($category->CAT_AGE_ID, old('categorie_age_ids'))) ? 'checked' : '' }}>
                            {{ $category->CAT_AGE_MIN }} - {{ $category->CAT_AGE_MAX }} ans
                        </label>
                        @endforeach
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div style="margin-top: 1rem;">
            <label>
                <input type="checkbox" name="COU_PUCE_OBLIGATOIRE" value="1" {{ old('COU_PUCE_OBLIGATOIRE') ? 'checked' : '' }}>
                Puce obligatoire
            </label>
        </div>

        {{-- Champs cachés pour clu_id et rai_id --}}
        <input type="hidden" name="CLU_ID" value="{{ $clu_id }}">
        <input type="hidden" name="RAI_ID" value="{{ $rai_id }}">

        <button type="submit" class="btn btn-primary" style="margin-top: 1rem; display: block; margin-left: auto; margin-right: auto;">
            Créer la course
        </button>

        <br>

        @if(session('success'))
        <p style="color: green; text-align: center;">{{ session('success') }}</p>
        @endif

        @if ($errors->any())
        <ul style="color: red; text-align: center;">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
        @endif
    </form>
</div>
@endsection
