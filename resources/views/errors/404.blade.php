<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('Page non trouvée') }} - {{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f7fafc;
            margin: 0;
            padding: 0;
            color: #333;
        }
        .error-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .btn-primary {
            background-color: #660bab;
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 0.375rem;
            font-weight: 500;
            text-decoration: none;
            display: inline-block;
            transition: background-color 0.2s;
        }
        .btn-primary:hover {
            background-color: #4e0883;
        }
        .error-code {
            font-size: 6rem;
            font-weight: 700;
            color: #660bab;
            margin: 0;
            line-height: 1;
        }
        .error-divider {
            height: 4px;
            width: 60px;
            background-color: #660bab;
            margin: 1.5rem auto;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="max-w-3xl mx-auto px-4 py-12 sm:px-6 lg:px-8 text-center">
            <i class="fas fa-search text-purple-500 text-5xl mb-4"></i>
            
            <h1 class="error-code">404</h1>
            <div class="error-divider"></div>
            
            <h2 class="mt-6 text-2xl font-semibold text-gray-900 tracking-tight">
                {{ __('Page non trouvée') }}
            </h2>
            
            <div class="mt-4 text-lg text-gray-600">
                @if(isset($message) && $message)
                    <p>{{ $message }}</p>
                @else
                    <p>{{ __('La page que vous recherchez n\'existe pas ou a été déplacée.') }}</p>
                @endif
            </div>
            
            <div class="mt-8 space-y-4">
                <p class="text-gray-500">{{ __('Vous pouvez essayer les actions suivantes:') }}</p>
                <ul class="text-left inline-block list-disc pl-5 text-gray-500">
                    <li>{{ __('Rafraîchir la page') }}</li>
                    <li>{{ __('Vérifier l\'URL pour les erreurs de frappe') }}</li>
                    <li>{{ __('Revenir à la page précédente') }}</li>
                    <li>{{ __('Revenir à l\'accueil') }}</li>
                </ul>
            </div>
            
            <div class="mt-10 space-x-4">
                <a href="javascript:history.back()" class="btn-primary">
                    <i class="fas fa-arrow-left mr-2"></i>{{ __('Retour') }}
                </a>
                <a href="{{ route('home', ['locale' => app()->getLocale()]) }}" class="btn-primary">
                    <i class="fas fa-home mr-2"></i>{{ __('Accueil') }}
                </a>
            </div>
        </div>
    </div>
</body>
</html> 