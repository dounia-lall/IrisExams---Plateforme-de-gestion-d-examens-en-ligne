<x-app-layout>

    {{-- HEADER --}}
    <x-slot name="header">
        <div class="bg-[#FFD84D] rounded-xl px-6 py-5 shadow-sm flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-[#0B1C33]">
                    Mes examens
                </h2>
                <p class="text-sm text-[#3A3A3A]">
                    Gestion et publication de vos examens
                </p>
            </div>

            <a href="{{ route('exams.create') }}"
               class="px-5 py-2 bg-[#0B1C33] text-white rounded-lg font-semibold
                      hover:bg-[#132b4d] transition">
                + Nouvel examen
            </a>
        </div>
    </x-slot>

    {{-- CONTENU --}}
    <div class="py-10 bg-white min-h-screen">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">

            @if ($exams->isEmpty())
                <div class="bg-gray-100 p-6 rounded-xl text-gray-600">
                    Aucun examen créé pour le moment.
                </div>
            @else

                <div class="bg-white rounded-xl shadow-md overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">

                            <thead class="bg-gray-50 text-[#0B1C33]">
                                <tr>
                                    <th class="px-4 py-3 text-left font-semibold">Titre</th>
                                    <th class="px-4 py-3 text-center">Durée</th>
                                    <th class="px-4 py-3 text-center">Début</th>
                                    <th class="px-4 py-3 text-center">Fin</th>
                                    <th class="px-4 py-3 text-center">État</th>
                                    <th class="px-4 py-3 text-center">Questions</th>
                                    <th class="px-4 py-3 text-center">Copies</th>
                                    <th class="px-4 py-3 text-center">Publication</th>
                                </tr>
                            </thead>

                            <tbody class="divide-y">
                            @foreach ($exams as $exam)

                                @php
                                    $questions = $exam->questions ?? collect();
                                    $qcm = $questions->where('type', 'qcm');

                                    $invalidQcmCount = $qcm->filter(function ($q) {
                                        $choices = $q->choices ?? collect();
                                        return $choices->count() < 2
                                            || $choices->where('is_correct', true)->count() < 1;
                                    })->count();

                                    $isReady = $questions->count() > 0 && $invalidQcmCount === 0;
                                @endphp

                                <tr class="hover:bg-gray-50 transition">

                                    {{-- TITRE --}}
                                    <td class="px-4 py-3 font-medium text-[#0B1C33]">
                                        {{ $exam->title }}
                                    </td>

                                    {{-- DURÉE --}}
                                    <td class="px-4 py-3 text-center">
                                        {{ $exam->duration_min }} min
                                    </td>

                                    {{-- DÉBUT --}}
                                    <td class="px-4 py-3 text-center">
                                        {{ $exam->start_at?->format('d/m/Y H:i') ?? '—' }}
                                    </td>

                                    {{-- FIN --}}
                                    <td class="px-4 py-3 text-center">
                                        {{ $exam->end_at?->format('d/m/Y H:i') ?? '—' }}
                                    </td>

                                    {{-- ÉTAT --}}
                                    <td class="px-4 py-3 text-center">
                                        @if ($isReady)
                                            <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs font-semibold">
                                                Prêt
                                            </span>
                                        @else
                                            <span class="px-3 py-1 rounded-full bg-yellow-100 text-yellow-700 text-xs font-semibold">
                                                Incomplet
                                            </span>
                                        @endif
                                    </td>

                                    {{-- QUESTIONS --}}
                                    <td class="px-4 py-3 text-center">
                                        <a href="{{ route('questions.index', $exam) }}"
                                           class="text-indigo-600 hover:underline">
                                            Gérer
                                        </a>
                                    </td>

                                    {{-- COPIES --}}
                                    <td class="px-4 py-3 text-center">
                                        <a href="{{ route('teacher.exams.submissions.index', $exam) }}"
                                           class="text-indigo-600 hover:underline">
                                            Voir
                                        </a>
                                    </td>

                                    {{-- PUBLICATION --}}
                                    <td class="px-4 py-3 text-center">

                                        @if($exam->status === 'published')

                                            <span class="text-green-600 font-semibold">
                                                ✔ Publié
                                            </span>

                                        @elseif($isReady)

                                            <form method="POST" action="{{ route('exams.publish', $exam) }}">
                                                @csrf
                                                <button
                                                    type="submit"
                                                    class="px-4 py-1.5 bg-green-600 text-white rounded-lg
                                                           hover:bg-green-700 transition text-sm">
                                                    Publier
                                                </button>
                                            </form>

                                        @else

                                            —
                                        
                                        @endif

                                    </td>

                                </tr>

                            @endforeach
                            </tbody>

                        </table>
                    </div>
                </div>

            @endif

        </div>
    </div>

</x-app-layout>
