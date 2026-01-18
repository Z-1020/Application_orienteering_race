@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">

    <h2 class="text-2xl font-bold mb-4">Clubs en attente de validation</h2>

    <table class="min-w-full bg-white border border-gray-200 rounded-lg shadow-md">
        <thead class="bg-gray-100">
            <tr>
                <th class="px-4 py-2 text-left text-gray-700 font-semibold">Nom du club</th>
                <th class="px-4 py-2 text-left text-gray-700 font-semibold">Responsable</th>
                <th class="px-4 py-2 text-left text-gray-700 font-semibold">Numéro licencié</th>
                <th class="px-4 py-2 text-left text-gray-700 font-semibold">Date de demande</th>
                <th class="px-4 py-2 text-left text-gray-700 font-semibold">Actions</th>
            </tr>
        </thead>
        <tbody>
        @foreach ($clubs as $club)
            <tr class="border-t hover:bg-gray-50 transition-colors">
                <td class="px-4 py-2">{{ $club->CLU_NOM }}</td>
                <td class="px-4 py-2">{{ $club->COM_NOM }} {{ $club->COM_PRENOM }}</td>
                <td class="px-4 py-2">{{ $club->ADH_NUM_LICENCIE ?? '—' }}</td>
                <td class="px-4 py-2">{{ $club->CLU_DATE_DEMANDE }}</td>
                <td class="px-4 py-2 flex space-x-2">

                    @if ($club->CLU_STATUS == "ATTENTE")
                        <!-- Valider -->
                        <form method="POST" action="{{ route('adminClub.valider', $club->CLU_ID) }}">
                            @csrf
                            <button type="submit" class="px-3 py-1 bg-green-500 text-white rounded hover:bg-green-600">
                                Valider
                            </button>
                        </form>

                        <!-- Refuser -->
                        <form method="POST" action="{{ route('adminClub.refuser', $club->CLU_ID) }}">
                            @csrf
                            <button type="submit" class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600">
                                Refuser
                            </button>
                        </form>
                    @endif

                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
<div class="mb-4">
    <a href="{{ route('adminClub.index') }}"
       class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition-colors">
        Retourner aux clubs
    </a>
</div>
</div>
@endsection
