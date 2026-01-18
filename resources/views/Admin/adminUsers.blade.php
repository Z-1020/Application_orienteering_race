@extends('layouts.app')
@section('content')
@if(session('error'))
<div class="mb-6 rounded-lg border border-red-200 bg-red-50 p-4">
  <div class="flex items-start gap-3">
    <div>
      <h2 class="text-sm font-semibold text-red-800">
        Une erreur est survenue
      </h2>
      <p class="mt-1 text-sm text-red-700">
        {{ session('error') }}
      </p>
    </div>
  </div>
</div>
@endif

@section('full-width-content')

<div class="pl-16 pr-16 w-full max-w-full table-full-width">
  <div class="titre mb-6">
  <h1 class="text-2xl font-bold text-gray-800">Gestion des utilisateurs</h1>
  <p class="text-gray-600">Liste de tous les utilisateurs inscrits sur la plateforme.</p>
</div>
  <div class="overflow-x-auto">
    <table class="min-w-full w-full divide-y divide-gray-200 table-auto">
      <thead class="bg-gray-50">
        <tr>
          <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Club(s)</th>
          <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom</th>
          <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prénom</th>
          <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pseudo</th>
          <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
          <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Téléphone</th>
          <th scope="col" class="hidden md:table-cell px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Adresse</th>
          <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Naissance</th>
          <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">N° Puce</th>
          <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">N° Licence</th>
          <th scope="col" class="px-4 py-3"></th>
        </tr>
      </thead>
      <tbody class="bg-white divide-y divide-gray-200 text-sm text-gray-800">
        @foreach($users as $u)
          <tr class="hover:bg-gray-50">
            <td class="px-4 py-3 align-top whitespace-normal">{{ $u->clubs ?? '' }}</td>
            <td class="px-4 py-3 align-top">{{ $u->COM_NOM }}</td>
            <td class="px-4 py-3 align-top">{{ $u->COM_PRENOM }}</td>
            <td class="px-4 py-3 align-top">{{ $u->COM_PSEUDO }}</td>
            <td class="px-4 py-3 align-top text-sm text-gray-600">{{ $u->COM_MAIL }}</td>
            <td class="px-4 py-3 align-top text-sm text-gray-600">{{ $u->COM_TELEPHONE }}</td>
            <td class="hidden md:table-cell px-4 py-3 align-top text-sm text-gray-600 truncate max-w-xs">{{ $u->COM_ADRESSE }}</td>
            <td class="px-4 py-3 align-top">{{ $u->COM_DATE_NAISSANCE ? \Carbon\Carbon::parse($u->COM_DATE_NAISSANCE)->format('d/m/Y') : '' }}</td>
            <td class="px-4 py-3 align-top">{{ $u->ADH_NUM_PUCE ?? '' }}</td>
            <td class="px-4 py-3 align-top">{{ $u->ADH_NUM_LICENCIE ?? '' }}</td>
            <td class="px-4 py-3 align-top text-right space-x-2">
              <a href="{{ route('adminUsers.user.edit', $u->COM_ID) }}" class="edit">Éditer</a>
              <form action="{{ route('admin.users.destroy', $u->COM_ID) }}" method="POST" class="inline-block" onsubmit="return confirm('Supprimer cet utilisateur ? Cette action est irréversible.')">
                @csrf
                @method('DELETE')
                <button type="submit" class="inline-block bg-red-600 hover:bg-red-700 text-white text-xs px-3 py-1 rounded">Supprimer</button>
              </form>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
@endsection
@endsection
