@extends('layouts.app')

@section('content')
<div class="p-4">

        @if($club->CLU_STATUS == 'VALIDE')
            <div class="mb-6 p-4 border rounded shadow">
                <h2 class="font-bold text-lg mb-2">{{ $club->CLU_NOM }}</h2>
                <p><strong>Responsable : </strong>{{ $club->responsable->compte->COM_NOM ?? 'Aucun Responsable' }}</p>
                <p><strong>Adresse : </strong>{{ $club->CLU_ADRESSE ?? '—' }}</p>
                <p><strong>Code Postal : </strong>{{ $club->CLU_CODE_POST ?? '—' }}</p>
            </div>
            <form method="POST" action="{{ route('club.storeMember', $club->CLU_ID) }}">
                @csrf
                <select name="COM_ID" class="border rounded px-2 py-1 w-full mb-4">
                    <option value="">-- Choisir un adhérent --</option>
                    @foreach ($adherents as $adherent)
                        <option value="{{ $adherent->COM_ID }}">
                            {{ $adherent->com->COM_NOM ?? 'Nom inconnu' }}
                        </option>
                    @endforeach
                </select>
                <button type="submit" class="px-3 py-1 bg-blue-500 text-white rounded">
                    Ajouter
                </button>
            </form>
            

        @endif 

</div>
@endsection
