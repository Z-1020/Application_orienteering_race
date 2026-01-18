@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Mes anciennes courses</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($past->isEmpty())
        <div class="alert alert-info">Vous n'avez aucune course passée enregistrée.</div>
    @else
        <table class="table">
            <thead>
                <tr>
                    <th>Course</th>
                    <th>Raid</th>
                    <th>Equipe</th>
                    <th>Date début</th>
                    <th>Date fin</th>
                    <th>Statut</th>
                    <th>PPS</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($past as $item)
                    <tr>
                        <td>{{ $item->COU_NOM }}</td>
                        <td>{{ $item->raid_nom }}</td>
                        <td>{{ $item->equ_nom ?? '-' }}</td>
                        <td>{{ $item->COU_DATE_DEBUT }}</td>
                        <td>{{ $item->COU_DATE_FIN }}</td>
                        <td>{{ $item->COUREUR_STATUS ?? '-' }}</td>
                        <td>{{ $item->COUR_PPS ?? '-' }}</td>
                        <td>
                            <a href="{{ route('race.results', [$item->CLU_ID, $item->RAI_ID, $item->COU_ID]) }}" 
                               class="btn btn-sm btn-primary">
                                📊 Voir résultats
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
