@extends('layouts.app')

@section('content')
    <h1>Profil</h1>

    <h3>Nom d'utilisateur : {{ $user['COM_PSEUDO'] }}</h3>
    <p>Nom : {{ $user['COM_NOM'] }}</p>
    <p>Prenom : {{ $user['COM_PRENOM'] }}</p>
    <p>Date de naissance : {{ $user['COM_DATE_NAISSANCE'] }}</p>
    <p>Adresse : {{ $user['COM_ADRESSE'] }}</p>
    <p>Téléphone : {{ $user['COM_TELEPHONE'] }}</p>
    <p>Adresse mail : {{ $user['COM_MAIL'] }}</p>
    <p>Licence : {{ $user->adherent->ADH_NUM_LICENCIE ?? 'Aucune licence' }}</p>
    <p>Puce : {{ $user->adherent->ADH_NUM_PUCE ?? '—' }}</p>




    <div class="mt-6">
        <a href="{{ route('profile.edit') }}"
           class="inline-block px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring focus:ring-green-400">
           Modifier mon profil
        </a>
    </div>
    
@endsection