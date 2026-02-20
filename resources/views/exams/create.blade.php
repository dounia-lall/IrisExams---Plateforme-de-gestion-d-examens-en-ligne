<x-app-layout>

    {{-- HEADER --}}
    <x-slot name="header">
        <div class="bg-[#FFD84D] rounded-xl px-6 py-5 shadow-sm">
            <h2 class="text-2xl font-bold text-[#0B1C33]">
                Créer un examen
            </h2>
            <p class="text-sm text-[#3A3A3A]">
                Renseignez les informations de base de l’examen
            </p>
        </div>
    </x-slot>

    <div class="py-10 bg-white min-h-screen">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white rounded-xl shadow-md p-6">
                <form method="POST" action="{{ route('exams.store') }}" class="space-y-6">
                    @csrf

                    {{-- TITRE --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Titre
                        </label>
                        <input
                            type="text"
                            name="title"
                            required
                            class="w-full border rounded-lg px-4 py-2
                                   focus:ring focus:ring-yellow-300"
                        >
                    </div>

                    {{-- DESCRIPTION --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Description
                        </label>
                        <textarea
                            name="description"
                            rows="4"
                            class="w-full border rounded-lg px-4 py-2
                                   focus:ring focus:ring-yellow-300"
                        ></textarea>
                    </div>

                    {{-- DURÉE --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Durée (minutes)
                        </label>
                        <input
                            type="number"
                            name="duration_min"
                            required
                            class="w-full border rounded-lg px-4 py-2
                                   focus:ring focus:ring-yellow-300"
                        >
                    </div>

                    {{-- DATES --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Date de début
                            </label>
                            <input
                                type="datetime-local"
                                name="start_at"
                                class="w-full border rounded-lg px-4 py-2"
                            >
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Date de fin
                            </label>
                            <input
                                type="datetime-local"
                                name="end_at"
                                class="w-full border rounded-lg px-4 py-2"
                            >
                        </div>
                    </div>

                    {{-- ACTIONS --}}
                    <div class="flex justify-between items-center pt-6">

                        {{-- 👉 BOUTON AUTRE INTERFACE --}}
                        <a href="{{ route('exams.index') }}"
                           class="px-5 py-2 rounded-lg bg-gray-200 text-gray-700
                                  hover:bg-gray-300 transition">
                            Annuler
                        </a>

                        <button
                            type="submit"
                            class="px-6 py-2 rounded-lg bg-[#0B1C33] text-white
                                   font-semibold hover:bg-[#132b4d] transition"
                        >
                            Créer l’examen
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>

</x-app-layout>
