<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Vik'azim - Plateforme de gestion de raids sportifs en pleine nature">
    <title>Vik'azim - Raids Nature</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>

    
        @include('layouts.headers.user')

    <main class="nature-bg">
        @yield('content')
    </main>
    @yield('full-width-content')

    <footer>
        <p>🌿 © 2026 Vik'azim - L'aventure au cœur de la nature</p>
    </footer>
</body>
</html>
