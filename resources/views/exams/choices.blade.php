<x-app-layout>

    {{-- HEADER --}}
    <x-slot name="header">
        <div class="bg-[#FFD84D] rounded-xl px-6 py-5 shadow-sm flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-[#0B1C33]">
                    Choix — QCM
                </h2>
                <p class="text-sm text-[#3A3A3A]">
                    Examen : {{ $exam->title }} — Question : {{ $question->question }}
                </p>
            </div>

            <a href="{{ route('questions.index', $exam) }}"
               class="px-5 py-2 bg-gray-200 rounded-lg text-gray-700
                      hover:bg-gray-300 transition">
                ← Retour
            </a>
        </div>
    </x-slot>

    <div class="py-10 bg-white min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- MESSAGE SUCCESS --}}
            @if (session('success'))
                <div class="p-4 rounded-lg bg-green-100 text-green-800">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="p-4 rounded-lg bg-red-100 text-red-800">
                    {{ session('error') }}
                </div>
            @endif

            {{-- ➕ AJOUTER UN CHOIX --}}
            <div class="bg-white rounded-xl shadow-md p-6">
                <h3 class="text-lg font-semibold text-[#0B1C33] mb-6">
                    ➕ Ajouter un choix
                </h3>

                <form method="POST" action="{{ route('choices.store', $question) }}" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Nouveau choix
                        </label>
                        <input
                            type="text"
                            name="text"
                            required
                            class="w-full border rounded-lg px-4 py-2
                                   focus:ring focus:ring-yellow-300"
                        >
                    </div>

                    <button
                        class="inline-flex items-center gap-2
                               bg-[#0B1C33] text-white px-6 py-2
                               rounded-lg font-semibold
                               hover:bg-[#132b4d] transition"
                    >
                        ➕ Ajouter le choix
                    </button>
                </form>
            </div>

            {{-- 📋 LISTE DES CHOIX --}}
            <div class="bg-white rounded-xl shadow-md p-6">

                @php
                    $totalChoices = $choices->count();
                    $correctChoices = $choices->where('is_correct', true)->count();
                @endphp
{{-- 🚦 STATUT QCM --}}
@if ($totalChoices < 2)
    <div class="mb-6 p-4 rounded-lg bg-yellow-100 text-yellow-800">
        ⚠️ Un QCM doit avoir au moins <strong>2 choix</strong>.
    </div>

@elseif ($correctChoices === 0)
    <div class="mb-6 p-4 rounded-lg bg-yellow-100 text-yellow-800">
        ⚠️ Sélectionnez au moins <strong>une bonne réponse</strong>.
    </div>

@else
    <div class="mb-6 p-4 rounded-lg bg-green-100 text-green-800">
        ✅ QCM valide ({{ $totalChoices }} choix,
        {{ $correctChoices }} {{ $correctChoices > 1 ? 'bonnes réponses' : 'bonne réponse' }})
    </div>
@endif


                <h3 class="text-lg font-semibold text-[#0B1C33] mb-6">
                    📋 Liste des choix
                </h3>

                @if ($choices->isEmpty())
                    <p class="text-gray-600">Aucun choix pour le moment.</p>
                @else
                    <div class="space-y-4">
                        @foreach ($choices as $c)
                            <div class="p-5 border rounded-xl flex justify-between items-center
                                        hover:shadow transition">

                                <div>
                                    <div class="font-medium text-gray-900">
                                        {{ $c->text }}
                                    </div>

                                    @if ($c->is_correct)
                                        <span class="inline-block mt-1 px-3 py-1 rounded-full
                                                     bg-green-100 text-green-700 text-xs font-semibold">
                                            ✅ Bonne réponse
                                        </span>
                                    @else
                                        <span class="inline-block mt-1 text-sm text-gray-400">
                                            —
                                        </span>
                                    @endif
                                </div>

                                {{-- ACTIONS --}}
                                <div class="flex items-center gap-3">

                                    {{-- DEFINIR COMME BONNE --}}
                                    @if (!$c->is_correct)
                                      <form method="POST" action="{{ route('choices.correct', $c) }}">
    @csrf

    <button
        type="submit"
        class="px-4 py-2 rounded-lg text-sm
        {{ $c->is_correct
            ? 'bg-green-100 text-green-700 hover:bg-green-200'
            : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}"
    >
        {{ $c->is_correct ? '✔ Retirer bonne réponse' : 'Définir comme bonne' }}
    </button>
</form>

                                    @else
                                        <span class="px-4 py-2 rounded-lg bg-green-100 text-green-700 text-sm">
                                            ✔ Déjà bonne
                                        </span>
                                    @endif

                                 {{-- 🗑 SUPPRESSION --}}
@if (!$c->is_correct)
    <form method="POST"
          action="{{ route('choices.destroy', $c->id) }}"
          onsubmit="return confirm('Supprimer ce choix ?');">
        @csrf
        @method('DELETE')

        <button
            type="submit"
            class="text-red-600 hover:text-red-800 text-sm font-semibold"
        >
            🗑 Supprimer
        </button>
    </form>
@endif


                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

            </div>

        </div>
    </div>

</x-app-layout>
