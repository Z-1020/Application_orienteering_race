@extends('layouts.app')

@section('content')
    <h1>Liste des équipes inscrites</h1>

    @foreach($teams as $nomEquipe => $lignes)
        @php $infos = $lignes->first(); @endphp

        <div class="card mb-4" style="border: 1px solid #ccc; padding: 15px;">
            <h3>Équipe : {{ $nomEquipe }}</h3>
            <p>
                <strong>Raid :</strong> {{ $infos->RAI_NOM }} <br>
                <strong>Club :</strong> {{ $infos->CLU_NOM }} <br>
                <strong>Course :</strong> {{ $infos->COU_NOM }}

            </p>

            <strong>Membres de l'équipe :</strong>
                <ul>
                    @foreach($lignes->unique('com_nom') as $membre)
                        <li>{{ $membre->COM_PRENOM }} {{ $membre->COM_NOM }}</li>
                    @endforeach
                </ul>
        </div>
    @endforeach
    <a href="{{ route('raids.index') }}" class="btn btn-primary mb-3">Retourner à la liste des raids</a>
@endsection