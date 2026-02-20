<x-app-layout>

    <x-slot name="header">
        <div class="bg-[#FFD84D] rounded-xl px-6 py-5 shadow-sm">
            <h2 class="text-2xl font-bold text-[#0B1C33]">
                Étudiants autorisés
            </h2>
            <p class="text-sm text-[#3A3A3A]">
                {{ $exam->title }}
            </p>
        </div>
    </x-slot>

    <div class="py-10 bg-white min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            <form method="POST" action="{{ route('exams.students.update', $exam) }}">
                @csrf

                <div class="bg-[#0B1C33] rounded-xl shadow-md p-6">
                    <h3 class="text-lg font-semibold text-white mb-6">
                        Sélection des étudiants par formation
                    </h3>

                    @foreach($students as $formation => $group)

                        <div 
                            x-data="{
                                open: false,
                                toggleAll(event) {
                                    let checked = event.target.checked;
                                    $el.querySelectorAll('.student-checkbox').forEach(cb => {
                                        cb.checked = checked;
                                    });
                                }
                            }"
                            class="mb-4 bg-white/10 rounded-lg"
                        >

                            {{-- HEADER FORMATION --}}
                            <div class="flex items-center justify-between px-4 py-3">

                                <div class="flex items-center gap-3">

                                    {{-- CHECKBOX FORMATION --}}
                                    <input
                                        type="checkbox"
                                        @change="toggleAll($event)"
                                        class="rounded text-yellow-500"
                                    >

                                    {{-- TITRE + CHEVRON --}}
                                    <button type="button"
                                            @click="open = !open"
                                            class="flex items-center gap-2 text-yellow-400 font-semibold hover:text-yellow-300 transition">

                                        🎓 {{ $formation ?? 'Sans formation' }}

                                        <svg xmlns="http://www.w3.org/2000/svg"
                                             class="w-4 h-4 transform transition-transform duration-200"
                                             :class="{ 'rotate-180': open }"
                                             fill="none"
                                             viewBox="0 0 24 24"
                                             stroke="currentColor">
                                            <path stroke-linecap="round"
                                                  stroke-linejoin="round"
                                                  stroke-width="2"
                                                  d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </button>

                                </div>

                            </div>

                            {{-- LISTE ÉTUDIANTS --}}
                            <div x-show="open"
                                 x-transition
                                 class="grid grid-cols-1 sm:grid-cols-2 gap-3 p-4">

                                @foreach($group as $student)
                                    <label class="flex items-center gap-3 bg-white rounded-lg px-4 py-2 cursor-pointer">
                                        <input
                                            type="checkbox"
                                            name="students[]"
                                            value="{{ $student->id }}"
                                            class="student-checkbox rounded text-yellow-500"
                                            {{ $exam->students->contains($student->id) ? 'checked' : '' }}
                                        >
                                        <div>
                                            <div class="text-sm font-medium text-[#0B1C33]">
                                                {{ $student->name }}
                                            </div>
                                            <div class="text-xs text-gray-500">
                                                {{ $student->email }}
                                            </div>
                                        </div>
                                    </label>
                                @endforeach

                            </div>

                        </div>

                    @endforeach

                </div>

                {{-- BOUTONS --}}
                <div class="flex justify-end gap-4 pt-6">
                    <a href="{{ route('exams.index') }}"
                       class="px-6 py-2 rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300 transition">
                        Retour
                    </a>

                    <button
                        type="submit"
                        class="px-8 py-2 rounded-lg bg-[#FFD84D] text-[#0B1C33]
                               font-semibold hover:bg-yellow-400 transition">
                        Enregistrer
                    </button>
                </div>

            </form>

        </div>
    </div>

</x-app-layout>
