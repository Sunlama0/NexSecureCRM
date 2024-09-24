<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'NexSecureCRM') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="icon" href="{{ url('public/favicon.ico') }}">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-gray-100">
    <div class="min-h-screen flex flex-col md:flex-row">
        <!-- Sidebar -->
        <div :class="{ 'hidden': !openSidebar }"
            class="w-full md:w-56 bg-gray-900 text-white flex flex-col min-h-screen md:flex md:block"
            x-data="{ openSidebar: false }">
            <!-- Logo et description -->
            <div class="p-4">
                <img src="{{ asset('images/nexsecure-logo.png') }}" alt="NexSecure Logo"
                    class="h-12 w-auto mx-auto hidden md:block">
                <p class="mt-2 text-center text-sm text-gray-400 hidden md:block">Protégez, développez et gérez votre IT
                    en toute sérénité.</p>
            </div>

            <!-- Navigation principale -->
            <nav class="mt-4 flex-1 space-y-1">
                <a href="{{ route('dashboard') }}"
                    class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 hover:text-white transition duration-200 {{ request()->routeIs('dashboard') ? 'bg-gray-700 text-white' : '' }}">
                    <i class="fas fa-home w-6"></i> <!-- Icône avec une largeur fixe pour aligner -->
                    <span class="ml-2">Tableau de bord</span> <!-- Ajout de `ml-2` pour espacement -->
                </a>
                <a href="{{ route('stocks.index') }}"
                    class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 hover:text-white transition duration-200 {{ request()->routeIs('stocks.index') ? 'bg-gray-700 text-white' : '' }}">
                    <i class="fas fa-clipboard w-6"></i>
                    <span class="ml-2">Stock</span>
                </a>
                <a href="{{ route('materials.index') }}"
                    class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 hover:text-white transition duration-200 {{ request()->routeIs('materials.index') ? 'bg-gray-700 text-white' : '' }}">
                    <i class="fas fa-desktop w-6"></i>
                    <span class="ml-2">Gestion du Matériel</span>
                </a>
                <a href="{{ route('calendar.index') }}"
                    class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 hover:text-white transition duration-200">
                    <i class="fas fa-calendar-alt w-6"></i>
                    <span class="ml-2">Calendrier</span>
                </a>

                <!-- Dropdown Facturation -->
                <div x-data="{ open: false }">
                    <button @click="open = !open"
                        class="flex items-center px-4 py-2 w-full text-gray-300 hover:bg-gray-700 hover:text-white transition duration-200">
                        <i class="	fas fa-credit-card w-6"></i>
                        <span class="ml-2">Facturation</span>
                        <i class="fas fa-chevron-down ml-auto transition-transform duration-300"
                            :class="{ 'rotate-180': open }"></i>
                    </button>
                    <div x-show="open" x-cloak class="ml-4 space-y-1 bg-gray-800 rounded py-2 mt-2">
                        <a href="{{ route('clients.index') }}"
                            class="block px-4 py-2 text-gray-300 hover:bg-gray-700 hover:text-white transition duration-200">Clients</a>
                        <a href="{{ route('quotes.index') }}"
                            class="block px-4 py-2 text-gray-300 hover:bg-gray-700 hover:text-white transition duration-200">Devis</a>
                        <a href="{{ route('invoices.index') }}"
                            class="block px-4 py-2 text-gray-300 hover:bg-gray-700 hover:text-white transition duration-200">Factures</a>
                        <a href="{{ route('payments.index') }}"
                            class="block px-4 py-2 text-gray-300 hover:bg-gray-700 hover:text-white transition duration-200">Paiements</a>
                    </div>
                </div>

                <a href="#"
                class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 hover:text-white transition duration-200">
                <i class="fas fa-chart-line w-6"></i>
                <span class="ml-2">Reporting & Compta</span>
            </a>

                <!-- Dropdown Paramétrage -->
                <div x-data="{ open: false }">
                    <button @click="open = !open"
                        class="flex items-center px-4 py-2 w-full text-gray-300 hover:bg-gray-700 hover:text-white transition duration-200">
                        <i class="fas fa-cogs w-6"></i> <!-- Icône avec largeur fixe pour alignement -->
                        <span class="ml-2">Paramétrage</span>
                        <i class="fas fa-chevron-down ml-auto transition-transform duration-300"
                            :class="{ 'rotate-180': open }"></i>
                    </button>
                    <div x-show="open" x-cloak class="ml-4 space-y-1 bg-gray-800 rounded py-2 mt-2">
                        <a href="{{ route('company.settings') }}"
                            class="block px-4 py-2 text-gray-300 hover:bg-gray-700 hover:text-white transition duration-200">Société</a>
                        <a href="{{ route('categories.index') }}"
                            class="block px-4 py-2 text-gray-300 hover:bg-gray-700 hover:text-white transition duration-200">Catégories</a>
                        <a href="{{ route('suppliers.index') }}"
                            class="block px-4 py-2 text-gray-300 hover:bg-gray-700 hover:text-white transition duration-200">Fournisseurs</a>
                        <a href="{{ route('device_identifiers.index') }}"
                            class="block px-4 py-2 text-gray-300 hover:bg-gray-700 hover:text-white transition duration-200">Produits</a>
                        <a href="{{ route('employees.index') }}"
                            class="block px-4 py-2 text-gray-300 hover:bg-gray-700 hover:text-white transition duration-200">Employés</a>
                    </div>
                </div>
            </nav>

            <!-- Section des paramètres -->
            <div class="border-t border-gray-800 p-4">
                <a href="#"
                    class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 hover:text-white transition duration-200">
                    <i class="fas fa-cog w-6"></i> <!-- Icône avec largeur fixe -->
                    <span class="ml-2">Settings</span>
                </a>
            </div>
        </div>


        <!-- Main content area -->
        <div class="flex-grow bg-gray-100">
            <!-- Header -->
            <header class="bg-white shadow p-4">
                <div class="flex justify-between items-center max-w-7xl mx-auto">
                    <!-- Barre de recherche -->
                    <div class="w-1/3 relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                            <i class="fas fa-search"></i>
                        </span>
                        <input type="search" placeholder="Rechercher..."
                            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-full focus:ring-2 focus:ring-blue-500">
                    </div>

                    <!-- Notifications et profil utilisateur -->
                    <div class="flex items-center space-x-6">
                        <!-- Notifications -->
                        <button class="relative text-gray-400 hover:text-gray-600">
                            <i class="fas fa-bell"></i>
                        </button>

                        <!-- Menu utilisateur -->
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" class="flex items-center focus:outline-none">
                                <img class="h-10 w-10 rounded-full" src="https://via.placeholder.com/150"
                                    alt="User Image">
                                <span class="ml-3 text-gray-900 font-medium">{{ Auth::user()->name }}</span>
                                <i class="ml-2 fas fa-chevron-down text-gray-400"></i>
                            </button>

                            <!-- Dropdown -->
                            <div x-show="open" @click.away="open = false" x-cloak
                                class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 ring-1 ring-black ring-opacity-5">
                                <a href="{{ route('profile.show') }}"
                                    class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-user-circle mr-2"></i> Votre profil
                                </a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-sign-out-alt mr-2"></i> Déconnexion
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Contenu de la page -->
            <main class="p-6">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Alpine.js pour la gestion du dropdown -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.7.0/dist/cdn.min.js"></script>
</body>

</html>
