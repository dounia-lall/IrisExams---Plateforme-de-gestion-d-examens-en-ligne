<x-app-layout>

    {{-- HEADER JAUNE --}}
    <x-slot name="header">
        <div class="bg-[#FFD84D] rounded-xl px-6 py-5 shadow-sm flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-[#0B1C33]">
                    Examen : {{ $exam->title }}
                </h2>
                <p class="text-sm text-[#3A3A3A]">
                    Correction — {{ $user->name }}
                </p>
            </div>

            <a href="{{ route('teacher.exams.submissions.index', $exam) }}"
               class="px-5 py-2 rounded-lg bg-white text-[#0B1C33]
                      font-medium hover:bg-gray-100 transition">
                ← Retour
            </a>
        </div>
    </x-slot>

    <div class="py-10 bg-gray-50 min-h-screen">
        <div class="max-w-4xl mx-auto space-y-8">

            {{-- 🔹 RÉSUMÉ --}}
            <div class="bg-white rounded-xl shadow p-6 space-y-3">
                <div class="flex justify-between">
                    <span class="font-medium text-gray-600">Score automatique</span>
                    <span class="font-semibold text-[#0B1C33]">
                        {{ $score }} / {{ $totalAuto }}
                    </span>
                </div>

                <div class="flex justify-between">
                    <span class="font-medium text-gray-600">Note finale</span>
                    <span class="font-bold text-lg text-[#0B1C33]">
                        {{ $finalScore ?? '—' }}
                    </span>
                </div>

                <div class="flex justify-between items-center">
                    <span class="font-medium text-gray-600">Statut</span>
                    @if($isCorrected)
                        <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-sm font-semibold">
                            ✔ Corrigé
                        </span>
                    @else
                        <span class="px-3 py-1 rounded-full bg-yellow-100 text-yellow-700 text-sm font-semibold">
                            ⏳ En attente
                        </span>
                    @endif
                </div>
            </div>

            {{-- ✅ FORMULAIRE --}}
            <form method="POST"
                  action="{{ route('teacher.exams.submissions.grade', [$exam, $user]) }}"
                  class="space-y-6">
                @csrf

                @foreach($exam->questions as $question)

                    @php
                        $questionAnswers = $answers->where('question_id', $question->id);
                    @endphp

                    <div class="bg-white rounded-xl shadow p-6 space-y-4">

                        {{-- QUESTION --}}
                        <div>
                            <h3 class="text-lg font-semibold text-[#0B1C33]">
                                {{ $question->label ?? $question->question }}
                            </h3>

                            <span class="inline-block mt-1 px-3 py-1 rounded-full text-xs font-semibold
                                {{ $question->type === 'qcm'
                                    ? 'bg-blue-100 text-blue-700'
                                    : ($question->type === 'vrai_faux'
                                        ? 'bg-green-100 text-green-700'
                                        : 'bg-gray-200 text-gray-700') }}">
                                {{ strtoupper(str_replace('_', ' ', $question->type)) }}
                            </span>
                        </div>

                        {{-- =========================
                             RÉPONSE ÉTUDIANT
                        ========================== --}}
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-xs text-gray-500 mb-1">
                                Réponse de l’étudiant
                            </p>

                            <p class="font-medium text-gray-800">

                                {{-- TEXTE --}}
                                @if($question->type === 'texte')
                                    {{ $questionAnswers->first()?->text_answer ?? '—' }}

                                {{-- VRAI / FAUX --}}
                                @elseif($question->type === 'vrai_faux')
                                    @php
                                        $ans = $questionAnswers->first();
                                    @endphp
                                    {{ is_null($ans?->boolean_answer)
                                        ? '—'
                                        : ($ans->boolean_answer ? 'Vrai' : 'Faux') }}

                                {{-- QCM MULTIPLE --}}
                                @elseif($question->type === 'qcm')

                                    @if($questionAnswers->isEmpty())
                                        —
                                    @else
                                        @foreach($questionAnswers as $ans)
                                            • {{ $ans->choice?->text }}<br>
                                        @endforeach
                                    @endif

                                @endif

                            </p>
                        </div>

                        {{-- =========================
                             BONNE RÉPONSE
                        ========================== --}}
                        @if(in_array($question->type, ['qcm', 'vrai_faux']))

                            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                                <p class="text-xs text-green-700 mb-1 font-semibold">
                                    Bonne réponse
                                </p>

                                <p class="text-green-900 font-semibold">

                                    {{-- VRAI / FAUX --}}
                                    @if($question->type === 'vrai_faux')
                                        {{ $question->correct_boolean ? 'Vrai' : 'Faux' }}

                                    {{-- QCM MULTIPLE --}}
                                    @else
                                        @php
                                            $correctChoices = $question->choices->where('is_correct', true);
                                        @endphp

                                        @if($correctChoices->isEmpty())
                                            —
                                        @else
                                            @foreach($correctChoices as $choice)
                                                • {{ $choice->text }}<br>
                                            @endforeach
                                        @endif
                                    @endif

                                </p>
                            </div>

                        @endif

                        {{-- =========================
                             CORRECTION TEXTE
                        ========================== --}}
                        @if($question->type === 'texte')
                            <div class="bg-[#0B1C33]/5 border border-[#0B1C33]/10 rounded-lg p-4 space-y-3">

                                <div>
                                    
                                   <label class="block text-sm font-medium text-[#0B1C33]">
    Note (/50)
</label>
<input
    type="number"
    name="manual[{{ $question->id }}][score]"
    min="0"
    max="50"
    class="w-28 border rounded-lg px-3 py-2
           focus:ring focus:ring-yellow-300"
>
                                    
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-[#0B1C33]">
                                        Commentaire
                                    </label>
                                    <textarea
                                        name="manual[{{ $question->id }}][comment]"
                                        rows="2"
                                        class="w-full border rounded-lg p-3
                                               focus:ring focus:ring-yellow-300"
                                    ></textarea>
                                </div>
                            </div>
                        @endif

                    </div>

                @endforeach

                {{-- BOUTON --}}
                <div class="flex justify-end pt-6">
                    <button type="submit"
                            class="px-8 py-3 rounded-lg bg-[#0B1C33] text-white
                                   font-bold hover:bg-[#132b4d] transition shadow">
                        💾 Enregistrer la correction
                    </button>
                </div>
            </form>

        </div>
    </div>

</x-app-layout>
