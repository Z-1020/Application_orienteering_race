<div class="w-full max-w-lg mx-auto my-auto bg-white p-8 rounded-xl shadow-lg">
    <h1 class="text-2xl font-bold text-center text-gray-800 mb-6">
        Supprimer le compte
    </h1>

    <p class="text-sm text-gray-600 mb-6">
        Une fois votre compte supprimé, toutes vos données seront définitivement supprimées. Veuillez entrer votre mot de passe pour confirmer la suppression.
    </p>

    <form method="post" action="{{ route('profile.destroy') }}" class="space-y-4">
        @csrf
        @method('delete')

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Mot de passe</label>
            <input type="password" name="password" placeholder="Mot de passe" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
            @error('password')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="w-full mt-4 bg-red-600 hover:bg-red-700 text-white font-semibold py-2 rounded-lg transition duration-200">
            Supprimer le compte
        </button>
    </form>

    <hr class="border-t border-gray-800 my-4">
    <a href="{{ route('profile.index') }}" class="w-full inline-flex items-center justify-center gap-2 px-6 py-3 text-sm font-semibold text-white rounded-lg shadow-md bg-gradient-to-br from-[#228B22] to-[#2E8B57] hover:from-[#2E8B57] hover:to-[#3CB371] hover:shadow-lg hover:-translate-y-1 active:translate-y-0 transition-all duration-300 cursor-pointer text-center">
        Retour au profil
    </a>
</div>