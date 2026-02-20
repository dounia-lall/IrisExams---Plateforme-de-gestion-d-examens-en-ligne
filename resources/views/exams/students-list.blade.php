<x-app-layout>

    <x-slot name="header">
        <div class="bg-[#FFD84D] rounded-xl px-6 py-5 shadow-sm">
            <h2 class="text-2xl font-bold text-[#0B1C33]">
                🎓 Liste des étudiants
            </h2>
            <p class="text-sm text-[#3A3A3A]">
                Organisation par formation
            </p>
        </div>
    </x-slot>

    <div class="py-10 bg-white min-h-screen">
        <div class="max-w-5xl mx-auto px-6">

            <div class="bg-[#0B1C33] rounded-xl shadow-md p-6">

                @forelse($students as $formation => $group)

                    <details class="mb-4 bg-white/10 rounded-lg">
                        <summary class="cursor-pointer px-4 py-3
                                         text-yellow-400 font-semibold
                                         hover:bg-white/10 rounded-lg transition">
                            ▶ {{ $formation ?? 'Sans formation' }}
                            <span class="text-sm text-gray-300">
                                ({{ $group->count() }})
                            </span>
                        </summary>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 p-4">
                            @foreach($group as $student)
                                <div class="bg-white rounded-lg px-4 py-3 shadow-sm">
                                    <div class="text-sm font-medium text-[#0B1C33]">
                                        {{ $student->name }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ $student->email }}
                                    </div>
                                </div>
                            @endforeach
                        </div>

                    </details>

                @empty
                    <p class="text-white">Aucun étudiant trouvé.</p>
                @endforelse

            </div>

        </div>
    </div>

</x-app-layout>
