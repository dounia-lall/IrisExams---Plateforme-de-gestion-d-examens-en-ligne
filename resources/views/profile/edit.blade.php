<x-app-layout>
    <x-slot name="header">
        <div class="bg-[#FFD84D] rounded-xl px-6 py-4">
            <h2 class="text-2xl font-bold text-[#0B1C33]">
                👤 Mon profil
            </h2>
            <p class="text-sm text-[#3A4A66]">
                Gérer vos informations personnelles et votre sécurité
            </p>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-5xl mx-auto px-6 space-y-8">

            {{-- 🧾 INFORMATIONS PROFIL --}}
            <div class="bg-[#0B1C33] text-white shadow-lg rounded-2xl p-8">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            {{-- 🔐 MOT DE PASSE --}}
            <div class="bg-[#0B1C33] text-white shadow-lg rounded-2xl p-8">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            {{-- ❌ SUPPRESSION COMPTE --}}
            <div class="bg-[#0B1C33] text-white shadow-lg rounded-2xl p-8">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
