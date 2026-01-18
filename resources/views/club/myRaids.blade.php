@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Mes Raids pour le club: {{ $club->CLU_NOM ?? $club->CLU_ID }}</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if($raids->isEmpty())
        <div class="alert alert-info">Aucun raid pour votre club.</div>
    @else
        <table class="table-light">
            <thead>
                    <tr>
                        <th class="px-4 py-3 text-left">Id</th>
                        <th class="px-4 py-3 text-left">Nom</th>
                        <th class="px-4 py-3 text-left">Début</th>
                        <th class="px-4 py-3 text-left">Fin</th>
                        <th class="px-4 py-3 text-left">Statut</th>
                        <th class="px-4 py-3 text-left">Courses</th>
                        <th class="px-4 py-3 text-center">Actions</th>
                    </tr>
            </thead>
            <tbody>
                @foreach($raids as $raid)
                    <tr>
                            <td class="px-4 py-3">{{ $raid->RAI_ID }}</td>
                            <td class="px-4 py-3">{{ $raid->RAI_NOM }}</td>
                            <td class="px-4 py-3">{{ $raid->RAI_DATE_DEBUT ?? '-' }}</td>
                            <td class="px-4 py-3">{{ $raid->RAI_DATE_FIN ?? '-' }}</td>
                            <td class="px-4 py-3">{{ $raid->RAI_STATUS ?? '-' }}</td>
                            <td class="px-4 py-3">{{ $raid->races_count ?? 0 }}</td>
                        <td>
                            @if($raid->COM_ID_ORGANISATEUR_RAID == Auth::user()->COM_ID)
                            <a class="btn btn-sm btn-primary"
                                href="{{ route('raids.manage.show', [$club->CLU_ID, $raid->RAI_ID]) }}">
                                Voir
                                </a>



                            <form action="{{ route('raids.destroy', $raid->RAI_ID) }}" method="POST" style="display:inline-block" onsubmit="return confirm('Supprimer ce raid ?');">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger" type="submit">Supprimer</button>
                            </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
