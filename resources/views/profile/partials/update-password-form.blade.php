<div class="w-full max-w-lg mx-auto my-auto bg-white p-8 rounded-xl shadow-lg">
    <h1 class="text-2xl font-bold text-center text-gray-800 mb-6">
        Modifier le mot de passe
    </h1>

    <form method="post" action="{{ route('password.update') }}" class="space-y-4">
        @csrf
        @method('put')

        @if ($errors->any())
            <div class="bg-red-100 text-red-700 p-3 rounded-lg mb-4">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Mot de passe actuel</label>
            <input type="password" name="current_password" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500" autocomplete="current-password">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nouveau mot de passe</label>
            <input type="password" name="password" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500" autocomplete="new-password">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Confirmer le nouveau mot de passe</label>
            <input type="password" name="password_confirmation" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500" autocomplete="new-password">
        </div>

        <button type="submit" class="w-full mt-4 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded-lg transition duration-200">
            Enregistrer
        </button>

        @if (session('status') === 'password-updated')
            <p class="text-green-600 mt-2 text-center">Mot de passe mis à jour.</p>
        @endif
    </form>

    <hr class="border-t border-gray-800 my-4">
    <a href="{{ route('profile.index') }}" class="w-full inline-flex items-center justify-center gap-2 px-6 py-3 text-sm font-semibold text-white rounded-lg shadow-md bg-gradient-to-br from-[#228B22] to-[#2E8B57] hover:from-[#2E8B57] hover:to-[#3CB371] hover:shadow-lg hover:-translate-y-1 active:translate-y-0 transition-all duration-300 cursor-pointer text-center">
        Retour au profil
    </a>
</div>