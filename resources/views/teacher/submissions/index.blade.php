<x-app-layout>

    {{-- HEADER --}}
    <x-slot name="header">
        <div class="bg-[#FFD84D] rounded-xl px-6 py-5 shadow-sm flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-[#0B1C33]">
                    Copies — {{ $exam->title }}
                </h2>
                <p class="text-sm text-[#3A3A3A]">
                    Liste des copies soumises par les étudiants
                </p>
            </div>

            <a href="{{ route('exams.index') }}"
               class="px-5 py-2 bg-gray-200 rounded-lg text-gray-700
                      hover:bg-gray-300 transition">
                ← Retour
            </a>
        </div>
    </x-slot>

    <div class="py-10 bg-white min-h-screen">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white rounded-xl shadow-md p-6">

                @if($students->isEmpty())
                    <p class="text-gray-600">
                        Aucune copie soumise pour le moment.
                    </p>
                @else

                    <div class="overflow-x-auto">
                        <table class="w-full border-separate border-spacing-y-2">

                            <thead>
                                <tr class="text-left text-gray-600 text-sm">
                                    <th class="px-4 py-2">Étudiant</th>
                                    <th class="px-4 py-2">Email</th>
                                    <th class="px-4 py-2 text-center">Score auto</th>
                                    <th class="px-4 py-2 text-center">Note finale</th>
                                    <th class="px-4 py-2 text-center">Statut</th>
                                    <th class="px-4 py-2 text-center">Actions</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($students as $student)
                                    @php
                                        $attempt = $student->examAttempts->first();
                                    @endphp

                                    <tr class="bg-gray-50 hover:bg-gray-100 transition rounded-lg">

                                        {{-- ÉTUDIANT --}}
                                        <td class="px-4 py-3 font-medium text-gray-900 rounded-l-lg">
                                            {{ $student->name }}
                                        </td>

                                        {{-- EMAIL --}}
                                        <td class="px-4 py-3 text-gray-600">
                                            {{ $student->email }}
                                        </td>

                                        {{-- SCORE AUTO --}}
                                        <td class="px-4 py-3 text-center">
                                            @if($attempt && $attempt->score_auto !== null)
                                                <span class="font-semibold">
                                                    {{ $attempt->score_auto }} / 50
                                                </span>
                                            @else
                                                —
                                            @endif
                                        </td>

                                        {{-- NOTE FINALE --}}
                                        <td class="px-4 py-3 text-center">
                                            @if($attempt && $attempt->final_score !== null)
                                                <span class="font-bold text-[#0B1C33]">
                                                    {{ $attempt->final_score }} / 100
                                                </span>
                                            @else
                                                <span class="text-yellow-700 text-sm">
                                                    ⏳ En attente
                                                </span>
                                            @endif
                                        </td>

                                        {{-- STATUT --}}
                                        <td class="px-4 py-3 text-center">
                                            @if($attempt && $attempt->is_corrected)
                                                <span class="inline-flex items-center gap-1
                                                             px-3 py-1 rounded-full
                                                             bg-green-100 text-green-700 text-sm">
                                                    ✅ Corrigé
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1
                                                             px-3 py-1 rounded-full
                                                             bg-yellow-100 text-yellow-700 text-sm">
                                                    ⏳ Non corrigé
                                                </span>
                                            @endif
                                        </td>

                                        {{-- ACTIONS --}}
                                        <td class="px-4 py-3 text-center rounded-r-lg">
                                            <div class="flex justify-center gap-2 flex-wrap">

                                                <a href="{{ route('teacher.exams.submissions.show', [$exam, $student]) }}"
                                                   class="px-3 py-2 rounded-lg bg-indigo-600
                                                          text-white text-sm hover:bg-indigo-700 transition">
                                                    👁️ Voir
                                                </a>

                                                <a href="{{ route('teacher.students.result.pdf', [$exam, $student]) }}"
                                                   class="px-3 py-2 rounded-lg bg-red-600
                                                          text-white text-sm hover:bg-red-700 transition">
                                                    📄 PDF
                                                </a>

                                                <a href="{{ route('teacher.students.result.csv', [$exam, $student]) }}"
                                                   class="px-3 py-2 rounded-lg bg-green-600
                                                          text-white text-sm hover:bg-green-700 transition">
                                                    📊 CSV
                                                </a>

                                            </div>
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
