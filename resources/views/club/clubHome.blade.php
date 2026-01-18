@extends('layouts.app')

@section('content')
<div class="p-4">
    <div class="bg-white rounded-lg shadow-md p-6">
        <h1 class="text-3xl font-bold mb-4">Bienvenue sur le tableau de bord du club</h1>
        <p class="text-lg mb-6">Gérez les membres de votre club et les raids depuis cette interface.</p>
        <a href="{{ url('club/create') }}" class="btn btn-primary mr-4">Gérer les MEMBRES</a>
        <a href="{{ url('raids/create') }}" class="btn btn-primary mr-4">Créer un RAID</a>
</div>
@endsection
