
<div class="w-full max-w-lg mx-auto my-auto bg-white p-8 rounded-xl shadow-lg">
    <h1 class="text-2xl font-bold text-center text-gray-800 mb-6">
        Modifier les informations du profil
    </h1>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-4">
        @csrf
        @method('patch')

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
            <label class="block text-sm font-medium text-gray-700 mb-1">Nom d'utilisateur</label>
            <input type="text" name="COM_PSEUDO" value="{{ old('COM_PSEUDO', $user->COM_PSEUDO) }}" required maxlength="64" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Prénom</label>
            <input type="text" name="COM_PRENOM" value="{{ old('COM_PRENOM', $user->COM_PRENOM) }}" required maxlength="32" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nom</label>
            <input type="text" name="COM_NOM" value="{{ old('COM_NOM', $user->COM_NOM) }}" required maxlength="32" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Adresse</label>
            <input type="text" name="COM_ADRESSE" value="{{ old('COM_ADRESSE', $user->COM_ADRESSE) }}" maxlength="128" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Date de naissance</label>
            <input type="date" name="COM_DATE_NAISSANCE" value="{{ old('COM_DATE_NAISSANCE', $user->COM_DATE_NAISSANCE) }}" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Téléphone</label>
            <input type="tel" name="COM_TELEPHONE" value="{{ old('COM_TELEPHONE', $user->COM_TELEPHONE) }}" maxlength="12" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <input type="email" name="COM_MAIL" value="{{ old('COM_MAIL', $user->COM_MAIL) }}" maxlength="255" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Numéro de licence
            </label>
            <input
                type="text"
                name="ADH_NUM_LICENCIE"
                value="{{ old('ADH_NUM_LICENCIE', $user->adherent->ADH_NUM_LICENCIE ?? '') }}"
                maxlength="50"
                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
            >
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Numéro de puce
            </label>
            <input
                type="text"
                name="ADH_NUM_PUCE"
                value="{{ old('ADH_NUM_PUCE', $user->adherent->ADH_NUM_PUCE ?? '') }}"
                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
            >
        </div>



        <button type="submit" class="w-full mt-4 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded-lg transition duration-200">
            Enregistrer
        </button>

        @if (session('status') === 'profile-updated')
            <p class="text-green-600 mt-2 text-center">Saved.</p>
        @endif
    </form>

    <hr class="border-t border-gray-800 my-4">
    <a href="{{ route('profile.index') }}" class="w-full inline-flex items-center justify-center gap-2 px-6 py-3 text-sm font-semibold text-white rounded-lg shadow-md bg-gradient-to-br from-[#228B22] to-[#2E8B57] hover:from-[#2E8B57] hover:to-[#3CB371] hover:shadow-lg hover:-translate-y-1 active:translate-y-0 transition-all duration-300 cursor-pointer text-center">
        Retour au profil
    </a>
</div>