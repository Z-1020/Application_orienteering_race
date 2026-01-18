@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Adhérer à un club</h1>

    @if(session('error'))
        <p style="color:red">{{ session('error') }}</p>
    @endif
    @if(session('success'))
        <p style="color:green">{{ session('success') }}</p>
    @endif

    <form method="POST" action="{{ route('club.join.submit') }}">
        @csrf

        <div>
            <label>Choisir un club</label>
            <select name="club_id" required>
                <option value="">-- Sélectionner --</option>
                @foreach($clubs as $club)
                    <option value="{{ $club->CLU_ID }}">
                        {{ $club->CLU_NOM }}
                    </option>
                @endforeach
            </select>
        </div>

         @if(!$adherentExists)
        <div>
            <label>Numéro de licence</label>
            <input type="number" name="adh_num_licencie" required>
        </div>
        @endif

        <button type="submit">Envoyer la demande</button>
    </form>
</div>
@endsection
