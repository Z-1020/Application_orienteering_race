@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto mt-8 px-4">
    <h1 class="text-3xl font-bold mb-6 text-gray-800">
        Mon profil
    </h1>

        @include('profile.partials.update-profile-information-form')

        <h2 class="text-2xl font-semibold mb-4">Modifier le mot de passe</h2>
        @include('profile.partials.update-password-form')
        
        {{--
        <h2 class="text-2xl font-semibold mb-4 text-red-600">Supprimer le compte</h2>
        @include('profile.partials.delete-user-form')
        --}}

</div>
@endsection
