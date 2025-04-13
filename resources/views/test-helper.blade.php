<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Test Helper Setting</title>
</head>
<body>
    <h1>Test de la fonction helper setting()</h1>
    
    <h2>Utilisation de la fonction helper:</h2>
    <p>Nom du site: {{ setting('site_name', 'Valeur par défaut') }}</p>
    <p>Description du site: {{ setting('site_description', 'Description par défaut') }}</p>
    
    <h2>Comparaison avec l'appel complet:</h2>
    <p>Nom du site (méthode complète): {{ \App\Models\Setting::get('site_name', 'Valeur par défaut') }}</p>
    <p>Description du site (méthode complète): {{ \App\Models\Setting::get('site_description', 'Description par défaut') }}</p>
</body>
</html> 