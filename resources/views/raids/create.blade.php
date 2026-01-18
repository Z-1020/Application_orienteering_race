@extends('layouts.app')

@section('content')

    <h1>Création d'un Raid</h1>
    
    <form action="{{ route('raids.store') }}" method="POST">
        @csrf
        
        {{-- CLU_ID is determined server-side from the club responsable; no need to show it --}}
        <input type="hidden" name="CLU_ID" value="{{ $CLU_ID }}">

        <label for="COM_ID_ORGANISATEUR_RAID">Responsable du raid (choisir parmi les membres du club):</label>
        @if(isset($members) && $members->count() > 0)
            <select id="COM_ID_ORGANISATEUR_RAID" name="COM_ID_ORGANISATEUR_RAID" required>
                <option value="">-- choisir un membre --</option>
                @foreach($members as $m)
                    <option value="{{ $m->COM_ID }}">{{ $m->COM_NOM }} {{ $m->COM_PRENOM }} ({{ $m->COM_ID }})</option>
                @endforeach
            </select>
            @error('COM_ID_ORGANISATEUR_RAID') <div style="color:red">{{ $message }}</div> @enderror
        @else
            <div class="alert alert-warning">Aucun membre disponible dans ce club pour être responsable.</div>
        @endif
        <br><br>

        <label for="RAI_NOM">RAI_NOM:</label>
        <input type="text" id="RAI_NOM" name="RAI_NOM" required><br><br>

        <label for="RAI_INSCRIPTION_DATE_DEBUT">Date de début de l'inscription:</label>
        <input type="date" id="RAI_INSCRIPTION_DATE_DEBUT" name="RAI_INSCRIPTION_DATE_DEBUT"><br><br>

        <label for="RAI_INSCRIPTION_DATE_FIN">Date de fin de l'inscription:</label>
        <input type="date" id="RAI_INSCRIPTION_DATE_FIN" name="RAI_INSCRIPTION_DATE_FIN"><br><br>

        <label for="RAI_DATE_DEBUT">Date de début:</label>
        <input type="date" id="RAI_DATE_DEBUT" name="RAI_DATE_DEBUT"><br><br>

        <label for="RAI_DATE_FIN">Date de fin:</label>
        <input type="date" id="RAI_DATE_FIN" name="RAI_DATE_FIN"><br><br>

        <label for="RAI_MAIL">E-mail:</label>
        <input type="email" id="RAI_MAIL" name="RAI_MAIL"><br><br>

        <label for="RAI_TELEPHONE">Numéro de téléphone:</label>
        <input type="tel" id="RAI_TELEPHONE" name="RAI_TELEPHONE"><br><br>

        <label for="RAI_LIEU">Lieu:</label>
        <input type="text" id="RAI_LIEU" name="RAI_LIEU"><br><br>

        <label for="RAI_ILLUSTRATION">Illustration (URL):</label>
        <input type="text" id="RAI_ILLUSTRATION" name="RAI_ILLUSTRATION"><br><br>

        <label for="RAI_SITE_WEB">Site Web:</label>
        <input type="url" id="RAI_SITE_WEB" name="RAI_SITE_WEB"><br><br>

        <button type="submit" >Soumettre</button>
    </form>
    @if ($errors->any())
        <ul style="color: red">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

@endsection