
@extends('layouts.app')

@section('content')

<div>
    <div class="p-4 border rounded-md shadow-sm bg-neutral-primary-soft m-4">
    <p class="text-center text-xl">Vous venez de valider le coureur {{$runner->com_nom}} {{$runner->com_prenom}}</p>
    <form class="flex justify-center" action="/viewParticipants/{{$runner->clu_id}} /{{$runner->rai_id}} /{{$runner->cou_id}} " method="get">
    <button class=""   type="submit">Voir les participants de la course</button>
    </form>
    </div>
</div>


@endsection