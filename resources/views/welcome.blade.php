<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Bienvenue</title>

    <!-- Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Figtree', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-100 antialiased">

    <!-- Main Container -->
    <div class="relative flex flex-col items-center justify-center min-h-screen bg-gradient-to-br from-purple-700 via-blue-500 to-pink-500">

        <!-- Decorative Background Circles -->
        <div class="absolute top-0 left-0 w-48 h-48 bg-purple-500 rounded-full mix-blend-multiply filter blur-xl opacity-30 animate-blob"></div>
        <div class="absolute top-0 right-0 w-64 h-64 bg-pink-500 rounded-full mix-blend-multiply filter blur-xl opacity-30 animate-blob animation-delay-2000"></div>
        <div class="absolute -bottom-8 left-20 w-72 h-72 bg-blue-500 rounded-full mix-blend-multiply filter blur-xl opacity-30 animate-blob animation-delay-4000"></div>

        <!-- Company Logo -->
        <div class="relative z-10 mb-8">
            <img src="{{ asset('images/nexsecure.png') }}" alt="Company Logo" class="w-32 h-32 rounded-full shadow-xl border-4 border-white">
        </div>

        <!-- Title and Welcome Text -->
        <div class="relative z-10 text-center mb-8">
            <h1 class="text-6xl font-extrabold text-white drop-shadow-lg">Bienvenue sur NexSecureCRM</h1>
            <p class="mt-4 text-lg text-gray-200">La solution tout-en-un pour la gestion des utilisateurs, factures et matériels</p>
        </div>

        <!-- Authentication Buttons -->
        <div class="relative z-10 space-x-4">
            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/dashboard') }}" class="px-8 py-3 bg-green-500 text-white text-lg font-semibold rounded-full shadow-lg hover:bg-green-600 transition duration-300 ease-in-out transform hover:scale-105">
                        Accéder au Tableau de Bord
                    </a>
                @else
                    <a href="{{ route('login') }}" class="px-8 py-3 bg-blue-500 text-white text-lg font-semibold rounded-full shadow-lg hover:bg-blue-600 transition duration-300 ease-in-out transform hover:scale-105">
                        Connexion
                    </a>

                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="px-8 py-3 bg-purple-500 text-white text-lg font-semibold rounded-full shadow-lg hover:bg-purple-600 transition duration-300 ease-in-out transform hover:scale-105">
                            Créer un compte
                        </a>
                    @endif
                @endauth
            @endif
        </div>

        <!-- Footer -->
        <footer class="relative z-10 mt-16 text-center text-white text-sm">
            <p>©2024 NexSecure Tous droits réservés</p>
        </footer>
    </div>

    <!-- Animation for Decorative Blobs -->
    <style>
        @keyframes blob {
            0% {
                transform: translate(0px, 0px) scale(1);
            }
            33% {
                transform: translate(30px, -50px) scale(1.1);
            }
            66% {
                transform: translate(-20px, 20px) scale(0.9);
            }
            100% {
                transform: translate(0px, 0px) scale(1);
            }
        }

        .animate-blob {
            animation: blob 7s infinite;
        }

        .animation-delay-2000 {
            animation-delay: 2s;
        }

        .animation-delay-4000 {
            animation-delay: 4s;
        }
    </style>
</body>
</html>
