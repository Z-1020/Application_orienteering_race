@extends('layouts.app')

@section('content')

    <h1>Création d'un Club</h1>
    
    @if(session('success'))
        <p class="text-green-600 font-semibold">{{ session('success') }}</p>
    @endif
    
    <form method="POST" action="{{ route('club.store') }}">
        @csrf

        <label for="CLU_NOM">Nom du Club:</label>
        <input type="text" id="CLU_NOM" name="CLU_NOM" required><br><br>

        <label for="CLU_ADRESSE">Adresse:</label>
        <input type="text" id="CLU_ADRESSE" name="CLU_ADRESSE"><br><br>

        <label for="CLU_CODE_POST">Code Postal:</label>
        <input type="number" id="CLU_CODE_POST" name="CLU_CODE_POST"><br><br>

        <label for="PHONE_NUMBER">Numéro de Téléphone :</label>
        <input type="number" id="PHONE_NUMBER" name="PHONE_NUMBER"><br><br>

        @if(!$adherentExists)
            <label for="ADH_NUM_LICENCIE">Numéro d'adhérent :</label>
            <input type="number" id="ADH_NUM_LICENCIE" name="ADH_NUM_LICENCIE">
        @endif

        <button type="submit" class="text-green-500 hover:underline">Créer le Club</button>
    </form>

@endsection
