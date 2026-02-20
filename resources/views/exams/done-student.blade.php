<x-app-layout>
    <x-slot name="header">
        <div class="bg-[#FFD84D] rounded-xl px-6 py-4 shadow-sm flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-[#0B1C33]">
                    Examen terminé
                </h2>
                <p class="text-sm text-[#3A3A3A]">
                    {{ $exam->title }}
                </p>
            </div>

            <a href="{{ route('student.exams.index') }}"
               class="px-5 py-2 rounded-lg bg-white text-[#0B1C33]
                      font-semibold hover:bg-gray-100 transition">
                ← Retour aux examens
            </a>
        </div>
    </x-slot>

    <div class="py-10 bg-gray-50 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white rounded-2xl shadow-lg p-6 space-y-6">

                {{-- MESSAGE SUCCÈS --}}
                @if(session('success'))
                    <div class="flex items-center gap-3 p-4 rounded-xl
                                bg-green-50 border border-green-200 text-green-800">
                        <span class="text-xl">✅</span>
                        <span class="font-medium">{{ session('success') }}</span>
                    </div>
                @endif

                {{-- NOTE FINALE --}}
                @if($attempt->final_score !== null)
                    <div class="p-5 rounded-xl bg-[#0B1C33]/5 border border-[#0B1C33]/10">
                        <p class="text-sm text-gray-600">🎓 Note finale</p>
                        <p class="text-2xl font-bold text-[#0B1C33]">
                            {{ $attempt->final_score }} / 100
                        </p>
                    </div>
                @else
                    <div class="p-5 rounded-xl bg-yellow-50 border border-yellow-200 text-yellow-800">
                        ⏳ En attente de correction du professeur
                    </div>
                @endif

                {{-- SCORE AUTOMATIQUE --}}
                <div class="p-5 rounded-xl bg-blue-50 border border-blue-200">
                    <p class="text-sm text-blue-700 font-semibold">
                        Score automatique
                    </p>

                    <p class="text-lg font-bold text-[#0B1C33] mt-1">
                        {{ $attempt->score_auto !== null ? $attempt->score_auto.' / 50' : '—' }}
                    </p>

                    <p class="text-xs text-gray-500 mt-1">
                        (QCM et Vrai / Faux)
                    </p>
                </div>

                {{-- DÉTAIL PAR QUESTION --}}
                <div>
                    <h3 class="text-xl font-bold text-[#0B1C33] mb-4">
                        Détails par question
                    </h3>

                    <div class="space-y-5">
                        @foreach($rows as $i => $row)
                            <div class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm">

                                {{-- QUESTION --}}
                                <div class="flex justify-between items-start">
                                    <div>
                                        <p class="font-semibold text-[#0B1C33]">
                                            {{ $i + 1 }}. {{ $row['question'] }}
                                        </p>
                                        <p class="text-xs uppercase tracking-wide text-gray-400 mt-1">
                                            {{ $row['type'] === 'qcm' ? 'QCM' :
                                               ($row['type'] === 'vrai_faux' ? 'Vrai / Faux' : 'Texte') }}
                                        </p>
                                    </div>
                                </div>

                                {{-- RÉPONSE ÉTUDIANT --}}
                                <div class="mt-4 bg-gray-50 rounded-lg p-4">
                                    <p class="text-xs text-gray-500 mb-1">
                                        Ta réponse
                                    </p>
                                    <p class="font-medium text-gray-800">
                                        {{ $row['student_answer'] ?? '—' }}
                                    </p>
                                </div>

                                {{-- QCM / VRAI-FAUX --}}
                                @if($row['type'] !== 'texte')
                                    <div class="mt-3 text-sm">
                                        <span class="text-gray-600">Bonne réponse :</span>
                                        <span class="font-semibold text-[#0B1C33]">
                                            {{ $row['correct_answer'] ?? '—' }}
                                        </span>
                                    </div>

                                    <div class="mt-2">
                                        @if($row['is_correct'] === true)
                                            <span class="px-3 py-1 rounded-full
                                                         bg-green-100 text-green-700 text-sm font-medium">
                                                ✔ Correct
                                            </span>
                                        @elseif($row['is_correct'] === false)
                                            <span class="px-3 py-1 rounded-full
                                                         bg-red-100 text-red-700 text-sm font-medium">
                                                ✖ Incorrect
                                            </span>
                                        @endif
                                    </div>
                                @endif

                                {{-- TEXTE --}}
                                @if($row['type'] === 'texte')
                                    <div class="mt-4 text-sm">
                                        <span class="text-gray-600">Note professeur :</span>

                                        @if($row['manual_score'] !== null)
                                            <span class="font-semibold text-[#0B1C33]">
                                                {{ $row['manual_score'] }} / 50
                                            </span>
                                        @else
                                            <span class="text-yellow-700">
                                                ⏳ En attente
                                            </span>
                                        @endif
                                    </div>

                                    @if(!empty($row['manual_comment']))
                                        <div class="mt-3 bg-gray-50 rounded-lg p-4 text-sm">
                                            <p class="text-gray-600 mb-1">
                                                Commentaire du professeur
                                            </p>
                                            <p class="italic text-gray-800">
                                                {{ $row['manual_comment'] }}
                                            </p>
                                        </div>
                                    @endif
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

                <p class="text-sm text-gray-500 text-center pt-4">
                    Tu peux revenir à la liste des examens à tout moment.
                </p>

            </div>
        </div>
    </div>
</x-app-layout>
