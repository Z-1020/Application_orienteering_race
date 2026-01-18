@extends('layouts.app')

@section('content')
<div class="w-full max-w-lg mx-auto my-auto bg-white p-8 rounded-xl shadow-lg">
    <h1 class="text-2xl font-bold text-center text-gray-800 mb-6">
        Inscription
    </h1>

    <form action="signup" method="post" class="space-y-4">
        @csrf
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
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Nom d'utilisateur
            </label>
            <input type="text" required name="username" maxlength="64" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500" value="{{ old('username') }}">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Mot de passe
            </label>
            <input type="password" required name="password" minlength="6" maxlength="64" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500" placeholder="Minimum 6 caractères">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Confirmer le mot de passe
            </label>
            <input type="password" required name="password_confirmation" maxlength="64" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Prénom
            </label>
            <input type="text" required name="surname" maxlength="32" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500" value="{{ old('surname') }}">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Nom
            </label>
            <input type="text" required name="name" maxlength="32" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500" value="{{ old('name') }}">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Adresse
            </label>
            <input type="text" required name="address" maxlength="128" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500" value="{{ old('address') }}">
        </div>

        <div>
            <label class=" block text-sm font-medium text-gray-700 mb-1">
                Date de naissance
            </label>
            <input type="date" required name="birthdate" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"  value="{{ old('birthdate') }}">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Numéro de téléphone
            </label>
            <input type="tel" required name="phone" maxlength="12" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500" placeholder="0123456789" value="{{ old('phone') }}">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Adresse email
            </label>
            <input type="email" required name="email" maxlength="255" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500" value="{{ old('email') }}">
        </div>

        <div class="flex items-center space-x-2">
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Je suis licencié
            </label>
            <input type="checkbox" id="isAdherentCheckbox" name="adherentCheck">
        </div>
        <div id="license_field_div" class="hidden">
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Numéro de licence
            </label>
            <input id="license_field" type="text" name="license_number" maxlength="255" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500" value="{{ old('license_number') }}">
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Numéro de puce
            </label>
            <input id="chip_field" type="number" name="chip_code" maxlength="255" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500" value="{{ old('chip_code') }}">
        </div>

        <button type="submit" required class="w-full mt-4 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded-lg transition duration-200">
            S'inscrire
        </button>
    </form>
    <hr class="border-t border-gray-800 my-4">
    <label class="block text-center text-gray-700 mb-2">Déjà inscrit ?</label>
    <a href="{{ route('login') }}" 
    class="w-full inline-flex items-center justify-center gap-2 px-6 py-3 text-sm font-semibold text-white rounded-lg shadow-md bg-gradient-to-br from-[#228B22] to-[#2E8B57] hover:from-[#2E8B57] hover:to-[#3CB371] hover:shadow-lg hover:-translate-y-1 active:translate-y-0 transition-all duration-300 cursor-pointer">
            Se connecter
    </a>
    <script>
        const checkbox = document.getElementById("isAdherentCheckbox");
        const licenseFieldDiv = document.getElementById("license_field_div");
        const licenseField = document.getElementById("license_field");
        checkbox.addEventListener('change', function(){
            if(this.checked){
                licenseFieldDiv.classList.remove('hidden');
            } else {
                licenseFieldDiv.classList.add('hidden');
                licenseField.value = '';
            }
        });
    </script>


</div>
@endsection
