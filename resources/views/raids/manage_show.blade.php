@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Gérer le Raid: {{ $raid->RAI_NOM }}</h1>

    <p><strong>Club:</strong> {{ $raid->club->CLU_NOM ?? $raid->CLU_ID }}</p>
    <p><strong>Dates:</strong> {{ $raid->RAI_DATE_DEBUT ?? '-'}} — {{ $raid->RAI_DATE_FIN ?? '-' }}</p>
    <p><strong>Organisateur:</strong> {{ $raid->COM_ID_ORGANISATEUR_RAID ?? '-' }}</p>
    <p><strong>Status:</strong> {{ $raid->RAI_STATUS }}</p>

    <h2>Courses</h2>
    @if($races->isEmpty())
        <p>Aucune course pour ce raid.</p>
    @else
        <table class="table">
            <thead>
                <tr>
                    <th>COU_ID</th>
                    <th>Nom</th>
                    <th>Date début</th>
                    <th>Date fin</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($races as $race)
                    <tr>
                        <td>{{ $race->COU_ID }}</td>
                        <td>{{ $race->COU_NOM }}</td>
                        <td>{{ $race->COU_DATE_DEBUT ?? '-' }}</td>
                        <td>{{ $race->COU_DATE_FIN ?? '-' }}</td>
                        <td>
                            <a href="{{ route('races.show', ['clu_id' => $raid->CLU_ID, 'rai_id' => $raid->RAI_ID, 'COU_ID' => $race->COU_ID]) }}" class="btn btn-sm btn-secondary">Voir</a>

                            <form action="{{ route('races.destroy', $race->COU_ID) }}" method="POST" style="display:inline-block" onsubmit="return confirm('Supprimer cette course ?');">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="redirect" value="{{ route('raids.manage.show', ['clu_id' => $raid->CLU_ID, 'rai_id' => $raid->RAI_ID]) }}">
                                <button class="btn btn-sm btn-danger" type="submit">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <a href="{{ route('races.create', ['clu_id' => $raid->CLU_ID, 'rai_id' => $raid->RAI_ID]) }}" class="btn btn-primary">Créer une Course</a>
</div>
@endsection