@extends('layouts.app')

@section('content')
<div class="p-4">
    <div class="bg-white rounded-lg shadow-md p-6">
        <h1 class="text-3xl font-bold mb-4">Bienvenue sur le tableau de bord administrateur</h1>
        <p class="text-lg mb-6">Gérez les utilisateurs, les raids et les courses depuis cette interface.</p>
        <a href="{{ url('adminClub') }}" class="btn btn-primary mr-4">Gérer les CLUBS</a>
        <a href="{{ url('adminUsers') }}" class="btn btn-primary mr-4">Gérer les UTILISATEURS</a>
</div>
@endsection
