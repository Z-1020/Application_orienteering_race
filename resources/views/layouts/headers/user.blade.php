<header>
    <nav>
        <a href="{{ url('/') }}">🌲 Vik'azim</a>
    </nav>
    <nav>
        <a href="{{ url('raids') }}">Découvrir les Raids</a>
    </nav>

    @if($isAuth)

        <nav>
            <a href="{{ url('dashboard') }}">Mon Dashboard</a>
        <nav>

        @if($isClubManager)
            <nav><a href="{{ route('raids.create') }}">Créer un Raid</a></nav>
        @endif

        @if($isRaidManager)
            <nav><a href="{{ route('raids.manage') }}">Gérer mes raids</a></nav>
        @endif

        @if($isAdmin)
            <nav><a href="{{ url('adminHome') }}">Portail Administrateur</a></nav>
        @endif
        
        @if(!$isAdherent)
            <nav><a href="{{ route('club.join.form') }}">Rejoindre un club</a></nav>
        @endif

        <nav>
            <a href="{{ url('createClub') }}">Ajouter un club</a>
        </nav>
        <nav>
            <a href="{{ url('profile') }}">Mon profil</a>
        </nav>
        <nav>
            <a href="{{ url('logout') }}">Se déconnecter</a>
        </nav>
    @else
        <nav>
            <a href="{{ url('login') }}">Se connecter</a>
        </nav>
        <nav>
            <a href="{{ url('signup') }}">S'inscrire</a>
        </nav>
    @endif
</header>
