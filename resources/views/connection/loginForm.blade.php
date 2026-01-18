@extends('layouts.app')

@section('content')
<div class="w-full max-w-md bg-white mx-auto my-auto p-8 rounded-xl shadow-lg">
        <h1 class="text-2xl font-bold text-center text-gray-800 mb-6">
            Connexion
        </h1>

        <form action="login" method="post" class="space-y-4">
            @csrf

            @if(session('error'))
                <div class="bg-red-100 text-red-700 text-sm p-3 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Nom d'utilisateur
                </label>
                <input type="text" required name="username" maxlength="64" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Mot de passe
                </label>
                <input type="password" required name="password" minlength="6" maxlength="64" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
            </div>

            <button type="submit" class="w-full mt-4 cursor-pointer text-white font-semibold py-2 rounded-lg transition duration-200">
                Se connecter
            </button>

        </form>
        <hr class="border-t border-gray-800 my-4">
        <label class="block text-center text-gray-700 mb-2">Pas encore inscrit ?</label>
        <a href="{{ route('signup') }}" 
        class="w-full inline-flex items-center justify-center gap-2 px-6 py-3 text-sm font-semibold text-white rounded-lg shadow-md bg-gradient-to-br from-[#228B22] to-[#2E8B57] hover:from-[#2E8B57] hover:to-[#3CB371] hover:shadow-lg hover:-translate-y-1 active:translate-y-0 transition-all duration-300 cursor-pointer">
                S'inscrire
        </a>

</div>
@endsection
