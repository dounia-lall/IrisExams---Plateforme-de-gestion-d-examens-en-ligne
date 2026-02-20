<x-app-layout>

    <x-slot name="header">
        <div class="bg-[#FFD84D] rounded-xl px-6 py-5 shadow-sm flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-[#0B1C33]">
                    {{ $exam->title }}
                </h2>

                <div class="mt-3 px-4 py-2 bg-white rounded-lg inline-block shadow">
                    ⏳ Temps restant :
                    <span id="timer" class="font-bold text-lg text-[#0B1C33]"></span>
                </div>

                <div class="text-sm text-[#3A3A3A] mt-3">
                    Durée : {{ $exam->duration_min }} min ·
                    Début : {{ $exam->start_at ? $exam->start_at->format('d/m/Y H:i') : '-' }} ·
                    Fin : {{ $exam->end_at ? $exam->end_at->format('d/m/Y H:i') : '-' }}
                </div>
            </div>

            <a href="{{ route('student.exams.index') }}"
               class="px-5 py-2 rounded-lg bg-white text-[#0B1C33]
                      font-semibold hover:bg-gray-100 transition">
                ← Retour
            </a>
        </div>
    </x-slot>

    <div class="py-10 bg-gray-50 min-h-screen">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            {{-- FORMULAIRE --}}
            <form id="exam-form"
                  method="POST"
                  action="{{ route('student.exams.submit', $exam) }}"
                  onsubmit="examSubmitted = true;">
                @csrf

                <div class="space-y-6">
                    @foreach ($exam->questions as $i => $q)

                        <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6">

                            {{-- QUESTION --}}
                            <div class="mb-3">
                                <p class="text-lg font-semibold text-[#0B1C33]">
                                    {{ $i+1 }}. {{ $q->question }}
                                </p>

                                <p class="text-xs uppercase tracking-wide text-gray-400 mt-1">
                                    {{ $q->type === 'qcm' ? 'QCM' : ($q->type === 'vrai_faux' ? 'Vrai / Faux' : 'Texte') }}
                                </p>

                                @if($q->type === 'qcm')
                                    <p class="text-xs text-gray-500 mt-1">
                                        (Plusieurs réponses possibles)
                                    </p>
                                @endif
                            </div>

                            {{-- =========================
                                 TEXTE
                            ========================== --}}
                            @if ($q->type === 'texte')

                                <input
                                    type="text"
                                    name="answers[{{ $q->id }}]"
                                    class="w-full rounded-lg border-gray-300 focus:border-[#FFD84D]
                                           focus:ring-[#FFD84D] px-4 py-3"
                                    placeholder="Écris ta réponse ici…"
                                >

                            {{-- =========================
                                 VRAI / FAUX
                            ========================== --}}
                            @elseif ($q->type === 'vrai_faux')

                                <div class="space-y-3">

                                    <label class="flex items-center gap-3 p-4 rounded-lg border
                                                  cursor-pointer hover:bg-gray-50">
                                        <input type="radio"
                                               name="answers[{{ $q->id }}]"
                                               value="true"
                                               class="text-[#FFD84D] focus:ring-[#FFD84D]">
                                        <span class="font-medium text-[#0B1C33]">Vrai</span>
                                    </label>

                                    <label class="flex items-center gap-3 p-4 rounded-lg border
                                                  cursor-pointer hover:bg-gray-50">
                                        <input type="radio"
                                               name="answers[{{ $q->id }}]"
                                               value="false"
                                               class="text-[#FFD84D] focus:ring-[#FFD84D]">
                                        <span class="font-medium text-[#0B1C33]">Faux</span>
                                    </label>

                                </div>

                            {{-- =========================
                                 QCM MULTIPLE
                            ========================== --}}
                            @else

                                <div class="space-y-3">
                                    @foreach ($q->choices as $c)

                                        <label class="flex items-center gap-3 p-4 rounded-lg border
                                                      cursor-pointer hover:bg-gray-50">

                                            <input type="checkbox"
                                                   name="answers[{{ $q->id }}][]"
                                                   value="{{ $c->id }}"
                                                   class="text-[#FFD84D] focus:ring-[#FFD84D]">

                                            <span class="text-[#0B1C33]">
                                                {{ $c->text }}
                                            </span>
                                        </label>

                                    @endforeach
                                </div>

                            @endif

                        </div>

                    @endforeach
                </div>

                {{-- BOUTON --}}
                <div class="mt-8 flex justify-end">
                    <button type="submit"
                            class="px-8 py-3 rounded-xl bg-[#0B1C33] text-white
                                   font-bold hover:bg-[#132b4d] transition shadow-lg">
                        🚀 Envoyer mes réponses
                    </button>
                </div>

            </form>

        </div>
    </div>

<script>
let examSubmitted = false;
let examForced = false;

const finishAt = {{ $finishAt ?? 0 }} * 1000;
const timerEl = document.getElementById('timer');
const examForm = document.getElementById('exam-form');

function updateTimer() {

    if (!finishAt) return;

    const now = Date.now();
    const remainingMs = finishAt - now;

    if (remainingMs <= 0) {
        examSubmitted = true;
        examForm.submit();
        return;
    }

    const totalSeconds = Math.floor(remainingMs / 1000);
    const minutes = Math.floor(totalSeconds / 60);
    const seconds = totalSeconds % 60;

    timerEl.innerText = `${minutes} min ${seconds}s`;
}

setInterval(updateTimer, 1000);
updateTimer();

/* ===============================
   🔒 ANTI-TRICHE
================================= */

function forceSubmitExam() {

    if (examSubmitted || examForced) return;

    examForced = true;

    fetch("{{ route('student.exams.force', $exam) }}", {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": "{{ csrf_token() }}",
            "Content-Type": "application/json"
        }
    }).then(() => {
        alert("⛔ Changement d’onglet détecté.\nExamen terminé.\nNote = 0");
        window.location.href = "{{ route('student.exams.index') }}";
    });
}

document.addEventListener("visibilitychange", function () {
    if (document.hidden) {
        forceSubmitExam();
    }
});

window.addEventListener("blur", function () {
    forceSubmitExam();
});

window.addEventListener("beforeunload", function (e) {
    if (!examSubmitted && !examForced) {
        e.preventDefault();
        e.returnValue = "Quitter = examen terminé avec 0.";
    }
});
</script>

</x-app-layout>
