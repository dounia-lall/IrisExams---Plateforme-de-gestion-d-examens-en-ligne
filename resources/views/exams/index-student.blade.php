<x-app-layout>
    <x-slot name="header">
        <div class="bg-[#FFD84D] rounded-xl px-6 py-4 shadow-sm">
            <h2 class="text-2xl font-bold text-[#0B1C33]">
                Mes examens
            </h2>
            <p class="text-sm text-[#3A3A3A]">
                Consultez vos examens et résultats
            </p>
        </div>
    </x-slot>

    <div class="py-10 bg-gray-50 min-h-screen">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white rounded-2xl shadow-lg p-6">

                @if($exams->isEmpty())
                    <p class="text-gray-500 text-center py-6">
                        Aucun examen disponible pour le moment.
                    </p>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full border-collapse">

                            {{-- HEADER --}}
                            <thead>
                                <tr class="border-b bg-gray-100 text-[#0B1C33] text-sm uppercase">
                                    <th class="px-4 py-3 text-left">Examen</th>
                                    <th class="px-4 py-3 text-center">Score auto</th>
                                    <th class="px-4 py-3 text-center">Note finale</th>
                                    <th class="px-4 py-3 text-center">Statut</th>
                                    <th class="px-4 py-3 text-center">Action</th>
                                </tr>
                            </thead>

                            {{-- BODY --}}
                            <tbody class="divide-y">
                            @foreach($exams as $exam)
                                @php
                                    $attempt = $exam->attempt;
                                    $now = now();

                                    $isSubmitted = $attempt && $attempt->submitted_at;
                                    $isCorrected = $attempt && $attempt->is_corrected;
                                    $isNotStarted = $exam->start_at && $now->lt($exam->start_at);
                                    $isInProgress = $exam->start_at 
                                                    && $now->gte($exam->start_at) 
                                                    && (!$exam->end_at || $now->lte($exam->end_at));
                                    $isExpired = $exam->end_at && $now->gt($exam->end_at);
                                @endphp

                                <tr class="hover:bg-gray-50 transition">

                                    {{-- TITRE --}}
                                    <td class="px-4 py-4 font-semibold text-[#0B1C33]">
                                        {{ $exam->title }}
                                    </td>

                                    {{-- SCORE AUTO --}}
                                    <td class="px-4 py-4 text-center text-sm">
                                        {{ $exam->auto_on_50 !== null ? $exam->auto_on_50.' / 50' : '—' }}
                                    </td>

                                    {{-- NOTE FINALE --}}
                                    <td class="px-4 py-4 text-center text-sm">
                                        @if($exam->is_corrected)
                                            <span class="font-bold text-[#0B1C33]">
                                                {{ $exam->final_score }} / 100
                                            </span>
                                        @else
                                            —
                                        @endif
                                    </td>

                                    {{-- STATUT --}}
                                    <td class="px-4 py-4 text-center text-sm">

                                        @if($isCorrected)
                                            <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 font-medium">
                                                ✔ Corrigé
                                            </span>

                                        @elseif($isSubmitted)
                                            <span class="px-3 py-1 rounded-full bg-yellow-100 text-yellow-700 font-medium">
                                                ⏳ En attente
                                            </span>

                                        @elseif($isNotStarted)
                                            <span class="px-3 py-1 rounded-full bg-gray-100 text-gray-700 font-medium">
                                                ⏰ Non commencé
                                            </span>

                                        @elseif($isInProgress)
                                            <span class="px-3 py-1 rounded-full bg-blue-100 text-blue-700 font-medium">
                                                ▶️ En cours
                                            </span>

                                        @else
                                            <span class="px-3 py-1 rounded-full bg-red-100 text-red-700 font-medium">
                                                ⛔ Terminé
                                            </span>
                                        @endif
                                    </td>

                                    {{-- ACTION --}}
                                    <td class="px-4 py-4 text-center">

                                        {{-- Déjà soumis --}}
                                        @if($isSubmitted)
                                            <a href="{{ route('student.exams.result', $exam) }}"
                                               class="px-4 py-2 rounded-lg bg-[#0B1C33] text-white
                                                      text-sm font-semibold hover:bg-[#132b4d] transition">
                                                Voir
                                            </a>

                                        {{-- Examen expiré --}}
                                        @elseif($isExpired)
                                            <span class="text-gray-400 font-semibold">
                                                —
                                            </span>

                                        {{-- Autorisé à passer --}}
                                        @elseif($isInProgress)
                                            <a href="{{ route('student.exams.show', $exam) }}"
                                               class="px-4 py-2 rounded-lg bg-[#FFD84D] text-[#0B1C33]
                                                      text-sm font-bold hover:bg-yellow-400 transition">
                                                Passer
                                            </a>

                                        @else
                                            —
                                        @endif

                                    </td>

                                </tr>
                            @endforeach
                            </tbody>

                        </table>
                    </div>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>
