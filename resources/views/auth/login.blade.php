<x-guest-layout>

    <div class="bg-white rounded-2xl shadow-2xl p-8 text-[#0B1C33]">

        <div class="text-center mb-6">
            <h1 class="text-2xl font-extrabold">
                Connexion
            </h1>
            <p class="text-gray-500 mt-1">
                Accédez à votre espace
                <span class="font-semibold text-[#FFD84D]">MyExam</span>
            </p>
        </div>

        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf

            {{-- EMAIL --}}
            <div>
                <x-input-label for="email" value="Email" />
                <x-text-input
                    id="email"
                    class="block mt-1 w-full rounded-lg"
                    type="email"
                    name="email"
                    :value="old('email')"
                    required
                    autofocus
                />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            {{-- PASSWORD --}}
            <div>
                <x-input-label for="password" value="Mot de passe" />
                <x-text-input
                    id="password"
                    class="block mt-1 w-full rounded-lg"
                    type="password"
                    name="password"
                    required
                />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            {{-- BUTTON --}}
            <button
                type="submit"
                class="w-full py-3 rounded-lg bg-[#FFD84D] text-[#0B1C33]
                       font-bold hover:bg-yellow-400 transition"
            >
                Se connecter
            </button>

            <p class="text-center text-sm text-gray-500 mt-4">
                Pas encore de compte ?
                <a href="{{ route('register') }}"
                   class="font-semibold text-[#0B1C33] hover:underline">
                    Inscription
                </a>
            </p>

        </form>
    </div>

</x-guest-layout>
