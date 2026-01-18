@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Mes Clubs</h1>

    @if($clubs->isEmpty())
        <p>Vous n’êtes responsable d’aucun club.</p>
    @else
        <ul>
            @foreach($clubs as $club)
                <div>
                    {{ $club->CLU_NOM ?? 'Nom non défini' }}
                    <a href="{{ route('club.index', ['id' => $club->CLU_ID]) }}" class="btn btn-primary">
                        Gérer le club
                    </a>
                </div>
            @endforeach
        </ul>
    @endif
</div>
@endsection
