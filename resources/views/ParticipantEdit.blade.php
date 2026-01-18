@extends('layouts.app')

@section('content')

                    

<div class="p-4 border rounded-md shadow-sm bg-neutral-primary-soft m-4">
    @foreach($runner as $detailRunner)
    <form action="{{ route('edit.participant', [$detailRunner->clu_id,$detailRunner->rai_id,$detailRunner->cou_id, $detailRunner->equ_id,$detailRunner->com_id]) }}" method="POST">
        @csrf
        <p><strong>Nom :</strong> {{$detailRunner->com_nom}}</p>
        <p><strong>Prenom : </strong>{{$detailRunner->com_prenom}}</p>
        <p><strong>Catégorie :</strong> {{ $detailRunner->cat_age_min }} ans - {{ $detailRunner->cat_age_max }} ans</p>
        <div class="flex flex-col gap-2">
        
    <div class="flex items-center gap-4">
        <p class="font-semibold whitespace-nowrap"><strong></strong>Numéro de licence ou numéro de PPS :</strong></p>
        <input name="cour_pps"
            class="border rounded px-3 py-2">
            
    </div>
    <div class="flex gap-2 items-center justify-center">
    
        <button button class="flex-1 m-4 sm:w-auto" type="submit">Modifier le coureur</button>
    </form>
    <form action="{{ route('view.participants', [$detailRunner->clu_id,$detailRunner->rai_id,$detailRunner->cou_id]) }}" method="GET">
        @csrf
        <button button class="flex-1 w-1/2 m-4 sm:w-auto" type="submit">Voir les participants</button>
    </form>
</div>
    @endforeach
</div>

@endsection
