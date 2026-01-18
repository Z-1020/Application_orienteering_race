@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto p-6 bg-white rounded-md shadow-sm">
  <h1 class="text-2xl font-semibold mb-4">Éditer l'utilisateur #{{ $user->COM_ID }}</h1>

  @if(session('success'))
    <div class="mb-4 rounded-md bg-green-50 border border-green-100 p-3 text-green-800">{{ session('success') }}</div>
  @endif
  @if(session('error'))
    <div class="mb-4 rounded-md bg-red-50 border border-red-100 p-3 text-red-800">{{ session('error') }}</div>
  @endif

  @if($errors->any())
    <div class="mb-4 rounded-md bg-red-50 border border-red-100 p-3 text-red-800">
      <ul class="list-disc pl-5 text-sm">
        @foreach($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form action="{{ route('adminUsers.user.update', $user->COM_ID) }}" method="POST" class="space-y-6">
    @csrf
    @method('PUT')

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <div>
        <label class="block text-sm font-medium text-gray-700">Nom *</label>
        <input name="COM_NOM" value="{{ old('COM_NOM', $user->COM_NOM) }}" required class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500" />
        @error('COM_NOM')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700">Prénom</label>
        <input name="COM_PRENOM" value="{{ old('COM_PRENOM', $user->COM_PRENOM) }}" class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500" />
        @error('COM_PRENOM')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700">Pseudo</label>
        <input name="COM_PSEUDO" value="{{ old('COM_PSEUDO', $user->COM_PSEUDO) }}" class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500" />
        @error('COM_PSEUDO')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700">Email</label>
        <input name="COM_MAIL" value="{{ old('COM_MAIL', $user->COM_MAIL) }}" type="email" class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500" />
        @error('COM_MAIL')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700">Téléphone</label>
        <input name="COM_TELEPHONE" value="{{ old('COM_TELEPHONE', $user->COM_TELEPHONE) }}" class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500" />
        @error('COM_TELEPHONE')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700">Date de naissance</label>
        <input name="COM_DATE_NAISSANCE" value="{{ old('COM_DATE_NAISSANCE', $user->COM_DATE_NAISSANCE) }}" type="date" class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500" />
        @error('COM_DATE_NAISSANCE')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
      </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <div>
        <label class="block text-sm font-medium text-gray-700">Adresse</label>
        <input name="COM_ADRESSE" value="{{ old('COM_ADRESSE', $user->COM_ADRESSE) }}" class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500" />
        @error('COM_ADRESSE')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700">N° Puce</label>
        <input name="ADH_NUM_PUCE" value="{{ old('ADH_NUM_PUCE', $user->ADH_NUM_PUCE ?? '') }}" class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500" />
        @error('ADH_NUM_PUCE')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
      </div>
    </div>

    <div>
      <label class="block text-sm font-medium text-gray-700">N° Licence</label>
      <input name="ADH_NUM_LICENCIE" value="{{ old('ADH_NUM_LICENCIE', $user->ADH_NUM_LICENCIE ?? '') }}" class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500" />
      @error('ADH_NUM_LICENCIE')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
    </div>

    <div>
      <label class="block text-sm font-medium text-gray-700">Clubs (sélection multiple avec touche CTRL)</label>
      <select name="clubs[]" multiple class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 h-32">
        @foreach($clubs as $club)
          <option value="{{ $club->CLU_ID }}" {{ in_array($club->CLU_ID, $clubIds ?? []) ? 'selected' : '' }}>{{ $club->CLU_NOM }}</option>
        @endforeach
      </select>
      @error('clubs')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
    </div>

    <div>
      <label class="block text-sm font-medium text-gray-700">Changer le mot de passe <span class="text-gray-400 text-xs">(laisser vide pour conserver)</span></label>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-1">
        <input type="password" name="COM_MDP" class="block w-full rounded border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500" />
        <input type="password" name="COM_MDP_confirmation" class="block w-full rounded border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500" />
      </div>
      @error('COM_MDP')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
    </div>

    <div class="flex items-center justify-end space-x-3">
      <a href="{{ url('/adminUsers') }}" class="inline-block bg-gray-200 hover:bg-gray-300 text-gray-800 text-sm px-4 py-2 rounded">Annuler</a>
      <button type="submit" class="inline-block bg-blue-600 hover:bg-blue-700 text-white text-sm px-4 py-2 rounded">Enregistrer</button>
    </div>
  </form>
</div>
@endsection