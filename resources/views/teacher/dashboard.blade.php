<x-app-layout>
   <x-slot name="header">
    <div class="bg-[#FFD84D] rounded-xl px-6 py-5 shadow-sm">
        <div class="flex items-center gap-3">
            <span class="text-2xl"></span>

            <div>
                <h2 class="text-2xl font-bold text-[#0B1C33]">
                    Suivi des examens
                </h2>
                <p class="text-sm text-[#3A3A3A]">
                    Vue d’ensemble de vos examens et résultats
                </p>
            </div>
        </div>
    </div>
</x-slot>

    {{-- FOND BLANC --}}
    <div class="py-10 bg-white min-h-screen">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">

            <h3 class="text-lg font-semibold text-[#0B1C33] mb-6">
                Mes examens
            </h3>

            @if($exams->isEmpty())
                <div class="bg-gray-100 p-6 rounded-xl text-gray-600">
                    Aucun examen créé pour le moment.
                </div>
            @else
                <div class="space-y-6">
                    @foreach($exams as $exam)

                        {{-- 🟦 CARTE EXAMEN --}}
                        <div class="bg-[#0B1C33] rounded-xl shadow-lg p-6">

                            {{-- HEADER --}}
                            <div class="flex justify-between items-center">
                                <h4 class="text-lg font-semibold text-white">
                                    {{ $exam->title }}
                                </h4>

                                <span class="text-sm text-gray-300 flex items-center gap-1">
                                    👥 {{ $exam->students_count }} étudiant(s)
                                </span>
                            </div>

                            {{-- STATS --}}
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">

                                {{-- MOYENNE --}}
                                <div class="p-4 rounded-lg bg-white/95 text-center">
                                    <div class="text-sm text-gray-500">
                                        Moyenne
                                    </div>
                                    <div class="text-2xl font-bold text-[#0B1C33]">
                                        {{ $exam->average_score !== null
                                            ? $exam->average_score.' / 100'
                                            : '—' }}
                                    </div>
                                </div>

                                {{-- RÉUSSITE --}}
                                <div class="p-4 rounded-lg bg-green-50 text-center">
                                    <div class="text-sm text-gray-500">
                                        Réussite
                                    </div>
                                    <div class="text-2xl font-bold text-green-600">
                                        {{ $exam->success_rate !== null
                                            ? $exam->success_rate.' %'
                                            : '—' }}
                                    </div>
                                </div>

                                {{-- COPIES --}}
                                <div class="p-4 rounded-lg bg-yellow-50 text-center">
                                    <div class="text-sm text-gray-500">
                                        Copies rendues
                                    </div>
                                    <div class="text-2xl font-bold text-yellow-600">
                                        {{ $exam->submitted_count }}
                                    </div>
                                </div>

                            </div>

                            {{-- ACTION --}}
                            <div class="mt-5 flex justify-end">
                                <a href="{{ route('teacher.exams.stats', $exam) }}"
                                   class="inline-flex items-center gap-2 px-5 py-2
                                          bg-[#FFD84D] text-[#0B1C33]
                                          font-semibold rounded-lg
                                          hover:bg-yellow-400 transition">
                                    📄 Voir les statistiques
                                </a>
                            </div>

                        </div>
                    @endforeach
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
