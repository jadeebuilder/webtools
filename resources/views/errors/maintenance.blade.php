<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('Maintenance') }} - {{ config('app.name') }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <style>
        body {
            font-family: 'Nunito', sans-serif;
            background-color: #f7fafc;
        }
        .maintenance-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .countdown {
            display: flex;
            justify-content: center;
            margin-top: 2rem;
        }
        .countdown-item {
            margin: 0 0.5rem;
            text-align: center;
            min-width: 60px;
        }
    </style>
</head>
<body>
    <div class="maintenance-container">
        <div class="max-w-2xl mx-auto px-4 py-12 sm:px-6 lg:px-8 text-center">
            <svg class="w-24 h-24 mx-auto text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
            </svg>
            
            <h1 class="mt-6 text-3xl font-extrabold text-gray-900 tracking-tight">
                {{ __('Site en maintenance') }}
            </h1>
            
            <div class="mt-4 text-lg text-gray-600">
                @if($message)
                    <p>{{ $message }}</p>
                @else
                    <p>{{ __('Notre site est actuellement en maintenance. Nous serons de retour très bientôt.') }}</p>
                @endif
            </div>
            
            @if(isset($end_date) && $end_date)
                <div class="mt-8">
                    <h2 class="text-xl font-semibold text-gray-700">{{ __('Retour prévu') }}</h2>
                    <div class="mt-2 text-gray-600" id="maintenance-end-date" data-end="{{ $end_date }}">
                        {{ \Carbon\Carbon::parse($end_date)->translatedFormat('d F Y à H:i') }}
                    </div>
                    
                    <div class="countdown mt-4" id="countdown">
                        <div class="countdown-item">
                            <div class="text-3xl font-bold text-indigo-600" id="days">00</div>
                            <div class="text-sm text-gray-500">{{ __('Jours') }}</div>
                        </div>
                        <div class="countdown-item">
                            <div class="text-3xl font-bold text-indigo-600" id="hours">00</div>
                            <div class="text-sm text-gray-500">{{ __('Heures') }}</div>
                        </div>
                        <div class="countdown-item">
                            <div class="text-3xl font-bold text-indigo-600" id="minutes">00</div>
                            <div class="text-sm text-gray-500">{{ __('Minutes') }}</div>
                        </div>
                        <div class="countdown-item">
                            <div class="text-3xl font-bold text-indigo-600" id="seconds">00</div>
                            <div class="text-sm text-gray-500">{{ __('Secondes') }}</div>
                        </div>
                    </div>
                </div>
                
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const endDate = document.getElementById('maintenance-end-date').dataset.end;
                        const countDownDate = new Date(endDate).getTime();
                        
                        const countdown = setInterval(function() {
                            const now = new Date().getTime();
                            const distance = countDownDate - now;
                            
                            if (distance < 0) {
                                clearInterval(countdown);
                                document.getElementById('countdown').innerHTML = "<p class='text-green-600 text-xl font-semibold'>{{ __('La maintenance devrait être terminée. Essayez de rafraîchir la page.') }}</p>";
                                return;
                            }
                            
                            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                            const seconds = Math.floor((distance % (1000 * 60)) / 1000);
                            
                            document.getElementById('days').innerHTML = String(days).padStart(2, '0');
                            document.getElementById('hours').innerHTML = String(hours).padStart(2, '0');
                            document.getElementById('minutes').innerHTML = String(minutes).padStart(2, '0');
                            document.getElementById('seconds').innerHTML = String(seconds).padStart(2, '0');
                        }, 1000);
                    });
                </script>
            @endif
            
            <div class="mt-10">
                <a href="{{ route('login') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    {{ __('Administration') }}
                </a>
            </div>
        </div>
    </div>
</body>
</html> 