<x-app-layout>
    <x-slot name="header">
        <div class="bg-[#FFD84D] rounded-xl px-6 py-5">
            <h2 class="text-2xl font-bold text-[#071A33] flex items-center gap-2">
                Statistiques par question
            </h2>
            <p class="text-sm text-[#3A4A66] mt-1">
                {{ $exam->title }}
            </p>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-6xl mx-auto space-y-10">

            {{-- 📈 GRAPHIQUE --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-[#071A33] mb-4 flex items-center gap-2">
                    📈 Résultats par question
                </h3>

                <div class="bg-[#071A33]/5 rounded-lg p-4">
                    <canvas id="questionChart" height="120"></canvas>
                </div>
            </div>

            {{-- 📋 TABLEAU --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-[#071A33] mb-4 flex items-center gap-2">
                    📋 Détails par question
                </h3>

                <div class="overflow-x-auto">
                    <table class="w-full border-collapse text-sm">
                        <thead>
                            <tr class="bg-[#071A33]/5 text-[#071A33] border-b">
                                <th class="px-4 py-3 text-left font-semibold">Question</th>
                                <th class="px-4 py-3 text-left font-semibold">Type</th>
                                <th class="px-4 py-3 text-center font-semibold">Réponses</th>
                                <th class="px-4 py-3 text-center font-semibold">Réussite</th>
                                <th class="px-4 py-3 text-center font-semibold">Moyenne</th>
                            </tr>
                        </thead>

                        <tbody>
                        @forelse($stats as $index => $row)
                            <tr class="border-b hover:bg-gray-50 transition">
                                <td class="px-4 py-3 font-medium text-[#071A33]">
                                    Q{{ $index + 1 }} — {{ $row['question'] }}
                                </td>

                                <td class="px-4 py-3">
                                    @if($row['type'] === 'qcm')
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-[#071A33]/10 text-[#071A33]">
                                            QCM
                                        </span>
                                    @elseif($row['type'] === 'vrai_faux')
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-[#071A33]/10 text-[#071A33]">
                                            Vrai / Faux
                                        </span>
                                    @else
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-700">
                                            Texte
                                        </span>
                                    @endif
                                </td>

                                <td class="px-4 py-3 text-center font-medium">
                                    {{ $row['answers'] }}
                                </td>

                                <td class="px-4 py-3 text-center">
                                    @if($row['success_rate'] !== null)
                                        <span class="text-green-700 font-semibold">
                                            {{ $row['success_rate'] }} %
                                        </span>
                                    @else
                                        <span class="text-gray-400">—</span>
                                    @endif
                                </td>

                                <td class="px-4 py-3 text-center">
                                    @if($row['average'] !== null)
                                        <span class="font-semibold text-[#071A33]">
                                            @if($row['average'] !== null)
    {{ $row['average'] }} / 50
@else
    —
@endif
                                        </span>
                                    @else
                                        <span class="text-gray-400">—</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-gray-500 py-6">
                                    Aucune statistique disponible.
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    {{-- 📊 Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        const ctx = document.getElementById('questionChart');

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: @json($labels),
                datasets: [{
                    label: 'Taux de réussite (%)',
                    data: @json($values),
                    backgroundColor: '#071A33',
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100,
                        ticks: { color: '#071A33' }
                    },
                    x: {
                        ticks: { color: '#071A33' }
                    }
                },
                plugins: {
                    legend: {
                        labels: {
                            color: '#071A33',
                            font: { weight: 'bold' }
                        }
                    }
                }
            }
        });
    </script>
</x-app-layout>
