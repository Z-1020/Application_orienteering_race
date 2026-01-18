@extends('layouts.app')

@section('content')
<div class="p-4">

    <div class="mb-4">
        <form method="GET" action="{{ route('club.index', ['id' => $club->CLU_ID]) }}">
            <input type="text" name="search" placeholder="Rechercher un membre..."
                   value="{{ $search ?? '' }}"
                   class="border px-2 py-1 rounded">
            <button type="submit" class="px-3 py-1 bg-blue-500 text-white rounded">Rechercher</button>
        </form>
    </div>

    <div class="mb-4">
        <form method="GET" action="{{ route('club.add',  ['clubId' => $club->CLU_ID]) }}">
            <button type="submit" class="px-3 py-1 bg-blue-500 text-white rounded">Ajouter un membre</button>
        </form>
    </div>

    <div class="mb-4">
        <form method="GET" action="{{ route('myRaids',  ['clubId' => $club->CLU_ID]) }}">
            <button type="submit" class="px-3 py-1 bg-blue-500 text-white rounded">Gerer les raids du club</button>
        </form>
    </div>

   
        @if($club->CLU_STATUS == 'VALIDE' || (auth()->check() && auth()->id() == $club->COM_ID_RESPONSABLE))
            <div class="mb-6 p-4 border rounded shadow">
                <h2 class="font-bold text-lg mb-2">{{ $club->CLU_NOM }} @if($club->CLU_STATUS != 'VALIDE') <span class="text-sm text-yellow-600">(En attente de validation)</span> @endif</h2>
                <p><strong>Responsable : </strong>{{ $club->responsable->compte->COM_NOM ?? 'Aucun Responsable' }}</p>
                <p><strong>Adresse : </strong>{{ $club->CLU_ADRESSE ?? '—' }}</p>
                <p><strong>Code Postal : </strong>{{ $club->CLU_CODE_POST ?? '—' }}</p>

                <table class="min-w-full mt-4 border">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2 text-left font-semibold">Nom du membre</th>
                            <th class="px-4 py-2 text-left font-semibold">Date de demande</th>
                            <th class="px-4 py-2 text-left font-semibold">Status</th>
                            <th class="px-4 py-2 text-left font-semibold">Date décision</th>
                            <th class="px-4 py-2 text-left font-semibold">Numero d'adhérent</th>
                            <th class="px-4 py-2 text-left font-semibold">Actions</th>
                            
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($members->where('CLU_ID', $club->CLU_ID) as $member)
                            @php $compte = $member->compte; @endphp
                            <tr class="border-t hover:bg-gray-50">
                                <td class="px-4 py-2">{{ $compte->COM_NOM ?? '—' }}</td>
                                <td class="px-4 py-2">{{ $member->ADHERER_DATE_DEMANDE ?? '—' }}</td>
                                <td class="px-4 py-2">{{ $member->ADHERER_STATUS ?? '—' }}</td>
                                <td class="px-4 py-2">{{ $member->ADHERER_DATE_DECISION ?? '—' }}</td>
                                <td class="px-4 py-2">{{ $member->adherer->ADH_NUM_LICENCIE ?? '—' }}</td>  
                                <td class="px-4 py-2 flex space-x-2">
                                @if ($member->ADHERER_STATUS == "EN_ATTENTE")
                                <form method="POST" action="{{ route('club.update', ['clubId' => $club->CLU_ID, 'comId' => $member->COM_ID]) }}">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="px-3 py-1 bg-blue-500 text-white rounded">
                                            Valider
                                        </button>
                                </form>
                                @endif
                                @if($member->COM_ID != $club->COM_ID_RESPONSABLE)
                                <form method="POST" action="{{ route('club.destroy', ['clubId' => $club->CLU_ID, 'comId' => $member->COM_ID]) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-1 bg-red-500 text-white rounded"
                                            onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce membre ?')">
                                        Supprimer
                                    </button>
                                </form>
                                @else
                                    <p>Responsable </p>
                                @endif
                                </td>
                            </tr>


                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif 

</div>
@endsection
