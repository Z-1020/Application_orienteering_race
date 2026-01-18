@extends('layouts.app')

@section('content')

<section style="text-align: center; padding: 3rem 1rem;">
    <h1 style="font-size: 3rem; margin-bottom: 2rem;">Bienvenue sur Vik'azim</h1>
    <p style="font-size: 1.25rem; max-width: 600px; margin: 0 auto 2rem; line-height: 1.8;">
        Découvrez les raids sportifs en pleine nature. Relevez des défis, explorez des paysages 
        exceptionnels et vivez des aventures inoubliables au cœur de la forêt.
    </p>
    <a href="{{ url('raids') }}" class="btn btn-primary" style="font-size: 1rem; padding: 1rem 2rem;">
        🏃 Découvrir les RAIDS
    </a>
</section>

@if(Auth::check())
    <p>Bonjour, {{ Auth::user()->COM_PSEUDO }} !</p>
@else
    <p>Bonjour, invité !</p>
@endif

<section style="padding: 3rem 1rem; background-color: #f8f9fa;">
    <div style="max-width: 1200px; margin: 0 auto;">
        <h2 style="font-size: 2rem; margin-bottom: 2rem; text-align: center;">Prochains Raids</h2>
        
        @if($nextRaids->isEmpty())
            <p style="text-align: center; color: #666;">Aucun raid à venir pour le moment.</p>
        @else
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem;">
                @foreach($nextRaids as $raid)
                    <div style="background-color: white; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); overflow: hidden; transition: transform 0.3s, box-shadow 0.3s;">
                        @if($raid->RAI_ILLUSTRATION)
                            <img src="{{ asset('storage/' . $raid->RAI_ILLUSTRATION) }}" alt="{{ $raid->RAI_NOM }}" style="width: 100%; height: 200px; object-fit: cover;">
                        @else
                            <div style="width: 100%; height: 200px; background-color: #e9ecef; display: flex; align-items: center; justify-content: center; color: #999;">
                                Pas d'image
                            </div>
                        @endif
                        
                        <div style="padding: 1.5rem;">
                            <h3 style="margin-top: 0; margin-bottom: 0.5rem;">{{ $raid->RAI_NOM }}</h3>
                            
                            <p style="margin: 0.5rem 0; color: #666;">
                                 <strong>Lieu:</strong> {{ $raid->RAI_LIEU }}
                            </p>
                            
                            <p style="margin: 0.5rem 0; color: #666;">
                                 <strong>Date:</strong> {{ \Carbon\Carbon::parse($raid->RAI_DATE_DEBUT)->format('d/m/Y') }}
                            </p>
                            
                            @if($raid->club)
                                <p style="margin: 0.5rem 0; color: #666;">
                                     <strong>Club:</strong> {{ $raid->club->CLU_NOM }}
                                </p>
                            @endif
                            
                            <p style="margin: 1rem 0 0 0; color: #999; font-size: 0.9rem;">
                                @if($raid->RAI_INSCRIPTION_DATE_FIN)
                                    Inscriptions jusqu'au {{ \Carbon\Carbon::parse($raid->RAI_INSCRIPTION_DATE_FIN)->format('d/m/Y') }}
                                @endif
                            </p>
                            
                            <a href="{{ url('raids/' . $raid->RAI_ID) }}" class="btn btn-primary" style="margin-top: 1rem; padding: 0.75rem 1.5rem;">
                                Voir les détails
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>

@endsection