<x-app-layout>

    {{-- HEADER --}}
    <x-slot name="header">
        <div class="bg-[#FFD84D] rounded-xl px-6 py-5 shadow-sm flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-[#0B1C33]">
                    Questions — {{ $exam->title }}
                </h2>
                <p class="text-sm text-[#3A3A3A]">
                    Ajoutez et gérez les questions de l’examen
                </p>
            </div>

            <a href="{{ route('exams.index') }}"
               class="px-5 py-2 bg-gray-200 rounded-lg text-gray-700 hover:bg-gray-300 transition">
                ← Retour
            </a>
        </div>
    </x-slot>

    <div class="py-10 bg-white min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- ALERTES --}}
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

            {{-- 🟦 AJOUTER QUESTION --}}
            <div class="bg-white rounded-xl shadow-md p-6">

                <h3 class="text-lg font-semibold text-[#0B1C33] mb-6">
                    ➕ Ajouter une question
                </h3>

                @if($exam->status !== 'published')

                    <form method="POST"
                          action="{{ route('questions.store', $exam) }}"
                          class="space-y-5">
                        @csrf

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Question
                            </label>
                            <input
                                type="text"
                                name="question"
                                value="{{ old('question') }}"
                                class="w-full border rounded-lg px-4 py-2 focus:ring focus:ring-yellow-300"
                                required
                            >
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Type de question
                            </label>
                            <select
                                name="type"
                                class="w-full border rounded-lg px-4 py-2 focus:ring focus:ring-yellow-300"
                                id="typeSelect"
                            >
                                <option value="qcm">QCM</option>
                                <option value="vrai_faux">Vrai / Faux</option>
                                <option value="texte">Texte</option>
                            </select>
                        </div>

                        <div id="trueFalseBlock" class="hidden">
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Bonne réponse
                            </label>
                            <select
                                name="correct_boolean"
                                class="w-full border rounded-lg px-4 py-2"
                                id="correctBooleanSelect"
                            >
                                <option value="">— Choisir —</option>
                                <option value="1">Vrai</option>
                                <option value="0">Faux</option>
                            </select>
                        </div>

                        <button
                            class="inline-flex items-center gap-2 bg-[#0B1C33] text-white px-6 py-2 rounded-lg font-semibold hover:bg-[#132b4d] transition">
                            ➕ Ajouter la question
                        </button>

                    </form>

                @else

                    <div class="text-gray-500 flex items-center gap-2">
                        🔒 Examen publié — modification impossible
                    </div>

                @endif
            </div>

            {{-- 📋 LISTE DES QUESTIONS --}}
            <div class="bg-white rounded-xl shadow-md p-6">
                <h3 class="text-lg font-semibold text-[#0B1C33] mb-6">
                    📋 Liste des questions
                </h3>

                @if ($questions->isEmpty())
                    <p class="text-gray-600">Aucune question pour le moment.</p>
                @else
                    <div class="space-y-4">
                        @foreach ($questions as $q)
                            <div class="p-5 border rounded-xl hover:shadow transition">

                                <div class="flex justify-between items-start">

                                    <div>
                                        <div class="font-medium text-gray-900">
                                            {{ $q->question }}
                                        </div>

                                        {{-- INFO VRAI / FAUX --}}
                                        @if ($q->type === 'vrai_faux')
                                            <div class="text-sm mt-2">
                                                Bonne réponse :
                                                <span class="font-semibold">
                                                    {{ $q->correct_boolean ? 'Vrai' : 'Faux' }}
                                                </span>
                                            </div>
                                        @endif

                                        {{-- ACTION QCM --}}
                                        @if ($q->type === 'qcm')
                                            @if($exam->status !== 'published')
                                                <a href="{{ route('choices.index', $q) }}"
                                                   class="inline-block mt-2 text-sm text-indigo-600 hover:underline">
                                                    👉 Gérer les choix
                                                </a>
                                            @else
                                                <span class="inline-block mt-2 text-sm text-gray-400">
                                                    🔒 Gestion verrouillée
                                                </span>
                                            @endif
                                        @endif
                                    </div>

                                    <div class="flex flex-col items-end gap-2">

                                        <span class="px-3 py-1 rounded-full text-xs font-semibold
                                            @if($q->type === 'qcm')
                                                bg-blue-100 text-blue-700
                                            @elseif($q->type === 'vrai_faux')
                                                bg-purple-100 text-purple-700
                                            @else
                                                bg-gray-100 text-gray-700
                                            @endif">
                                            {{ $q->type === 'qcm' ? 'QCM' :
                                               ($q->type === 'vrai_faux' ? 'Vrai / Faux' : 'Texte') }}
                                        </span>

                                        {{-- SUPPRESSION --}}
                                        @if($exam->status !== 'published')
                                            <form method="POST"
                                                  action="{{ route('questions.destroy', $q) }}">
                                                @csrf
                                                @method('DELETE')

                                                <button class="text-red-600 hover:text-red-800 text-sm">
                                                    🗑 Supprimer
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-gray-400 text-sm">
                                                🔒 Examen publié
                                            </span>
                                        @endif

                                    </div>

                                </div>

                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>
    </div>
<script>
document.addEventListener('DOMContentLoaded', function () {

    const typeSelect = document.getElementById('typeSelect');
    const trueFalseBlock = document.getElementById('trueFalseBlock');

    function toggleTrueFalse() {
        if (typeSelect.value === 'vrai_faux') {
            trueFalseBlock.classList.remove('hidden');
        } else {
            trueFalseBlock.classList.add('hidden');
        }
    }

    toggleTrueFalse();
    typeSelect.addEventListener('change', toggleTrueFalse);
});
</script>

</x-app-layout>
