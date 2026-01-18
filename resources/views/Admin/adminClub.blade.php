@extends('layouts.app')

@section('content')
<div class="mb-4">
    <form method="GET" action="{{ route('adminClub.index') }}">
        <input type="text" name="search" placeholder="Rechercher un membre..."
               value="{{ $search ?? '' }}"
               class="border px-2 py-1 rounded">
        <button type="submit" class="px-3 py-1 bg-blue-500 text-white rounded">Rechercher</button>
    </form>
</div>
<div class="mb-4">
    <a href="{{ route('adminClub.clubValidation') }}"
       class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition-colors">
        Valider les clubs
    </a>
</div>


<table class="min-w-full bg-white border border-gray-200 rounded-lg shadow-md">
    <thead class="bg-gray-100">
        <tr>
            <th class="px-4 py-2 text-left text-gray-700 font-semibold">Nom du club</th>
            <th class="px-4 py-2 text-left text-gray-700 font-semibold">Responsable</th>
            <th class="px-4 py-2 text-left text-gray-700 font-semibold">Adresse</th>
            <th class="px-4 py-2 text-left text-gray-700 font-semibold">Code Postal</th>
            <th class="px-4 py-2 text-left text-gray-700 font-semibold">Actions</th>
        </tr>
    </thead>
    <tbody>
    @foreach ($clubs as $club)
            <tr class="border-t hover:bg-gray-50 transition-colors">
                <form method="POST" action="{{ route('adminClub.update', $club->CLU_ID) }}">
                    @csrf
                    @method('PUT')

                    <td class="px-4 py-2">
                        <input type="text" name="CLU_NOM"
                               value="{{ $club->CLU_NOM }}"
                               class="border rounded px-2 py-1 w-full" />
                    </td>

                    <td class="px-4 py-2">
                        <select class="border rounded px-2 py-1 w-full mb-1 bg-gray-100 cursor-not-allowed" disabled>
                            <option value="{{ $club->COM_ID_RESPONSABLE }}">
                                {{ $club->responsable->compte->COM_NOM ?? 'Aucun Responsable' }}
                            </option>
                        </select>

                        <select name="COM_ID_NOUVEAU_RESPONSABLE"
                                class="border rounded px-2 py-1 w-full nouvelle-responsable-select"
                                onchange="checkNewResponsable(this)">
                            <option value="">-- Choisir un nouveau responsable --</option>
                            @foreach ($members->where('CLU_ID', $club->CLU_ID) as $member)
                                <option value="{{ $member->COM_ID }}">
                                    {{ $member->compte->COM_NOM ?? 'Aucun responsable de club' }}
                                </option>
                            @endforeach
                        </select>
                    </td>

                    <td class="px-4 py-2">
                        <input type="text" name="CLU_ADRESSE"
                               value="{{ $club->CLU_ADRESSE }}"
                               class="border rounded px-2 py-1 w-full" />
                    </td>

                    <td class="px-4 py-2">
                        <input type="number" name="CLU_CODE_POST"
                               value="{{ $club->CLU_CODE_POST }}"
                               class="border rounded px-2 py-1 w-full" />
                    </td>

                    <td class="px-4 py-2 flex space-x-2">
                        <button type="submit" class="text-blue-500 hover:underline">Sauvegarder</button>
                        
                        </form>
                        <form method="POST" action="{{ route('adminClub.destroy', $club->CLU_ID) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:underline"
                                    onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce club ?')">
                                Supprimer
                            </button>
                        </form>

                        
                        <form method="GET" action="{{ route('club.index', ['id' => $club->CLU_ID]) }}">
                            <button type="submit" class="px-3 py-1 bg-blue-500 text-white rounded">Gérer membre</button>
                        </form>
                    </td>
                
            </div>
            </tr>
    @endforeach
    </tbody>
</table>

    </div>
</div>
@endsection