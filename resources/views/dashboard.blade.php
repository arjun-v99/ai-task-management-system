<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- ── Stats Cards ─────────────────────────────────────── --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">

                {{-- Total --}}
                <div class="bg-white rounded-lg shadow p-6 text-center">
                    <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Total Tasks</p>
                    <p class="text-4xl font-bold text-gray-800">{{ $stats['total'] }}</p>
                </div>

                {{-- Completed --}}
                <div class="bg-white rounded-lg shadow p-6 text-center">
                    <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Completed</p>
                    <p class="text-4xl font-bold text-green-600">{{ $stats['completed'] }}</p>
                </div>

                {{-- Pending --}}
                <div class="bg-white rounded-lg shadow p-6 text-center">
                    <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Pending</p>
                    <p class="text-4xl font-bold text-yellow-500">{{ $stats['pending'] }}</p>
                </div>

                {{-- High Priority --}}
                <div class="bg-white rounded-lg shadow p-6 text-center">
                    <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">High Priority</p>
                    <p class="text-4xl font-bold text-red-500">{{ $stats['high'] }}</p>
                </div>

            </div>

            {{-- ── Chart ───────────────────────────────────────────── --}}
            <div class="bg-white rounded-lg shadow p-6">
                <h4 class="text-md font-semibold text-gray-700 mb-4">Task Overview</h4>
                <div class="relative h-64">
                    <canvas id="taskChart"></canvas>
                </div>
            </div>

            {{-- ── Recent Tasks ─────────────────────────────────────── --}}
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between mb-4">
                    <h4 class="text-md font-semibold text-gray-700">Recent Tasks</h4>
                    <a href="{{ route('tasks.index') }}" class="text-sm text-indigo-600 hover:underline">View all →</a>
                </div>

                @forelse($recentTasks as $task)
                    <div class="flex items-center justify-between py-3 border-b border-gray-100 last:border-0">
                        <a href="{{ route('tasks.show', $task) }}"
                            class="text-sm font-medium text-gray-800 hover:text-indigo-600">
                            {{ $task->title }}
                        </a>
                        @php
                            $colors = match ($task->status->value) {
                                'pending' => 'bg-gray-100 text-gray-600',
                                'in_progress' => 'bg-blue-100 text-blue-700',
                                'completed' => 'bg-green-100 text-green-700',
                                default => 'bg-red-100 text-red-600',
                            };
                        @endphp
                        <span class="px-2 py-1 rounded-full text-xs font-medium {{ $colors }}">
                            {{ ucfirst(str_replace('_', ' ', $task->status->value)) }}
                        </span>
                    </div>
                @empty
                    <p class="text-sm text-gray-400 text-center py-4">No tasks yet.</p>
                @endforelse
            </div>

        </div>
    </div>

    {{-- ── Chart.js ─────────────────────────────────────────────────── --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('taskChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Total', 'Completed', 'Pending', 'High Priority'],
                datasets: [{
                    label: 'Tasks',
                    data: [
                        {{ $stats['total'] }},
                        {{ $stats['completed'] }},
                        {{ $stats['pending'] }},
                        {{ $stats['high'] }}
                    ],
                    backgroundColor: [
                        'rgba(99, 102, 241, 0.6)',
                        'rgba(34, 197, 94, 0.6)',
                        'rgba(234, 179, 8, 0.6)',
                        'rgba(239, 68, 68, 0.6)',
                    ],
                    borderRadius: 6,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    </script>

</x-app-layout>
