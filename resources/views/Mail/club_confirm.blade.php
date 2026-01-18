<h1>Bonjour {{ $club->CLU_NOM }}</h1>

<p>Merci d'avoir créé un club sur notre plateforme. Veuillez confirmer votre club en cliquant sur le lien ci-dessous :</p>

<a href="{{ route('adminClub.confirm', $club->CLU_ID) }}">Confirmer mon club</a>

<a href="{{ route('adminClub.destroy', $club->CLU_ID) }}">Refuser mon club</a>

<p>Si vous n'avez pas créé ce club, ignorez ce mail.</p>