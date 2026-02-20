<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>MyExam</title>

    <!-- Font -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-[#0B1C33] text-white flex flex-col">

    {{-- NAVBAR --}}
    <header class="w-full px-10 py-6 flex justify-between items-center">
        <div class="flex items-center gap-3">
            <img
                src="{{ asset('images/myexam-logo.png') }}"
                alt="MyExam"
                class="h-8 w-auto"
            >
            <span class="font-bold text-xl tracking-wide">
                MyExam
            </span>
        </div>

    </header>

    {{-- HERO --}}
    <main class="flex-1 flex items-center justify-center px-6">
        <div class="max-w-6xl w-full grid md:grid-cols-2 gap-12 items-center">

            {{-- TEXTE --}}
            <div class="space-y-6">
                <h1 class="text-4xl md:text-5xl font-extrabold leading-tight">
                    Créez et gérez vos examens<br>
                    <span class="text-[#FFD84D]">en toute simplicité</span>
                </h1>

                <p class="text-gray-300 text-lg">
                    MyExam est une plateforme moderne dédiée aux enseignants
                    pour créer, publier et corriger des examens en ligne.
                </p>

                <div class="flex gap-4">
                    <a
                        href="{{ route('register') }}"
                        class="px-8 py-4 rounded-xl bg-[#FFD84D] text-[#0B1C33]
                               font-bold text-lg hover:bg-yellow-400 transition shadow-lg"
                    >
                        S'inscrire
                    </a>

                    <a
                        href="{{ route('login') }}"
                        class="px-8 py-4 rounded-xl bg-white/10 hover:bg-white/20 transition"
                    >
                        Se connecter
                    </a>
                </div>
            </div>

            {{-- CARD FONCTIONNALITÉS --}}
            <div class="bg-white rounded-2xl p-8 text-[#0B1C33] shadow-xl">
                <h2 class="text-xl font-bold mb-4">
                    Pourquoi MyExam ?
                </h2>

                <ul class="space-y-3 font-medium">
                    <li>✅ Création d’examens (QCM, texte, V/F)</li>
                    <li>✅ Corrections automatiques</li>
                    <li>✅ Gestion des étudiants</li>
                    <li>✅ Résultats & statistiques</li>
                </ul>
            </div>

        </div>
    </main>

    {{-- FOOTER --}}
    <footer class="text-center text-gray-400 py-6 text-sm">
        © {{ date('Y') }} MyExam — Tous droits réservés
    </footer>

</body>
</html>
