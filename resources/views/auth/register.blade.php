<x-guest-layout>

    <div class="bg-white rounded-2xl shadow-2xl p-8 text-[#0B1C33]">

        <div class="text-center mb-6">
            <h1 class="text-2xl font-extrabold">
                Créer un compte
            </h1>
            <p class="text-gray-500 mt-1">
                Rejoignez la plateforme
                <span class="font-semibold text-[#FFD84D]">MyExam</span>
            </p>
        </div>

        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf

            {{-- NAME --}}
            <div>
                <x-input-label for="name" value="Nom complet" />
                <x-text-input
                    id="name"
                    class="block mt-1 w-full rounded-lg"
                    type="text"
                    name="name"
                    :value="old('name')"
                    required
                    autofocus
                />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

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

            {{-- CONFIRM PASSWORD --}}
            <div>
                <x-input-label for="password_confirmation" value="Confirmer le mot de passe" />
                <x-text-input
                    id="password_confirmation"
                    class="block mt-1 w-full rounded-lg"
                    type="password"
                    name="password_confirmation"
                    required
                />
            </div>

            {{-- ROLE --}}
            <div>
                <x-input-label for="role" value="Je suis" />
                <select
                    id="role"
                    name="role"
                    required
                    class="block mt-1 w-full rounded-lg border-gray-300
                           focus:border-[#FFD84D] focus:ring-[#FFD84D]"
                    onchange="toggleFormation()"
                >
                    <option value="student">🎓 Étudiant</option>
                    <option value="teacher">👨‍🏫 Professeur</option>
                </select>
            </div>

            {{-- FORMATION (VISIBLE UNIQUEMENT SI ETUDIANT) --}}
            <div id="formation-field">
                <x-input-label for="formation" value="Formation" />
                <select
                    name="formation"
                    class="block mt-1 w-full rounded-lg border-gray-300
                           focus:border-[#FFD84D] focus:ring-[#FFD84D]"
                >
                    <option value="">-- Sélectionner une formation --</option>
                    <option value="BTS SIO SLAM">BTS SIO - SLAM</option>
                    <option value="BTS SIO SISR">BTS SIO - SISR</option>
                    <option value="Bachelor DevOps">Bachelor DevOps</option>
                    <option value="Bachelor Cybersécurité">Bachelor Cybersécurité</option>
                    <option value="Mastère IT">Mastère IT</option>
                    <option value="Mastère IT">Mastère Réseau</option>
                </select>
            </div>

            <button
                type="submit"
                class="w-full py-3 rounded-lg bg-[#FFD84D] text-[#0B1C33]
                       font-bold hover:bg-yellow-400 transition mt-4"
            >
                S’inscrire
            </button>

            <p class="text-center text-sm text-gray-500 mt-4">
                Déjà inscrit ?
                <a href="{{ route('login') }}"
                   class="font-semibold text-[#0B1C33] hover:underline">
                    Connexion
                </a>
            </p>

        </form>
    </div>

<script>
function toggleFormation() {
    const role = document.getElementById('role').value;
    const formationField = document.getElementById('formation-field');

    formationField.style.display = role === 'student' ? 'block' : 'none';
}

// Appel au chargement
toggleFormation();
</script>

</x-guest-layout>
