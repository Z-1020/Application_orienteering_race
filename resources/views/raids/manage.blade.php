@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Mes Raids</h1>

    @if($raids->isEmpty())
        <p>Vous n'organisez actuellement aucun raid.</p>
    @else
        <ul>
            @foreach($raids as $raid)
                <li>
                    <strong>{{ $raid->RAI_NOM }}</strong> 
                    <a href="{{ route('raids.manage.show', ['clu_id' => $raid->CLU_ID, 'rai_id' => $raid->RAI_ID]) }}" class="btn btn-sm btn-primary">Gérer</a>
                </li>
            @endforeach
        </ul>
    @endif
</div>
@endsection
