<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Task Detail
            </h2>
            <a href="{{ route('tasks.index') }}" class="text-sm text-gray-500 hover:underline">
                ← Back to Tasks
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- ── Success Flash ───────────────────────────────────── --}}
            @if (session('success'))
                <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            {{-- ── Task Details Card ───────────────────────────────── --}}
            <div class="bg-white shadow rounded-lg p-6">

                {{-- Title + Action Buttons --}}
                <div class="flex items-start justify-between mb-6">
                    <h3 class="text-2xl font-bold text-gray-900">
                        {{ $task->title }}
                    </h3>
                    <div class="flex gap-2">
                        @can('update', $task)
                            <a href="{{ route('tasks.edit', $task) }}"
                                class="bg-indigo-600 text-white px-4 py-2 rounded-md text-sm hover:bg-indigo-700">
                                Edit
                            </a>
                        @endcan

                        @can('delete', $task)
                            <form method="POST" action="{{ route('tasks.destroy', $task) }}"
                                onsubmit="return confirm('Are you sure you want to delete this task?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="bg-red-500 text-white px-4 py-2 rounded-md text-sm hover:bg-red-600">
                                    Delete
                                </button>
                            </form>
                        @endcan
                    </div>
                </div>

                {{-- Status + Priority Badges --}}
                <div class="flex gap-3 mb-6">
                    @php
                        $statusColors = match ($task->status->value) {
                            'pending' => 'bg-gray-100 text-gray-600',
                            'in_progress' => 'bg-blue-100 text-blue-700',
                            'completed' => 'bg-green-100 text-green-700',
                            default => 'bg-red-100 text-red-600',
                        };
                        $priorityColors = match ($task->priority->value) {
                            'low' => 'bg-green-100 text-green-700',
                            'medium' => 'bg-yellow-100 text-yellow-700',
                            'high' => 'bg-red-100 text-red-600',
                            default => 'bg-gray-100 text-gray-600',
                        };
                    @endphp

                    <span class="px-3 py-1 rounded-full text-xs font-medium {{ $statusColors }}">
                        {{ ucfirst(str_replace('_', ' ', $task->status->value)) }}
                    </span>
                    <span class="px-3 py-1 rounded-full text-xs font-medium {{ $priorityColors }}">
                        {{ ucfirst($task->priority->value) }} Priority
                    </span>
                </div>

                {{-- Description --}}
                <div class="mb-6">
                    <p class="text-sm font-medium text-gray-500 mb-1">Description</p>
                    <p class="text-gray-700 leading-relaxed">
                        {{ $task->description ?? 'No description provided.' }}
                    </p>
                </div>

                {{-- Meta Grid --}}
                <div class="grid grid-cols-2 gap-4 pt-4 border-t border-gray-100">
                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Assigned To</p>
                        <p class="text-sm text-gray-700 font-medium">
                            {{ $task->assignedUser?->name ?? 'Unassigned' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Due Date</p>
                        <p class="text-sm text-gray-700 font-medium">
                            {{ $task->due_date?->format('M d, Y') ?? '—' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Created</p>
                        <p class="text-sm text-gray-700 font-medium">
                            {{ $task->created_at->format('M d, Y h:i A') }}
                        </p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Last Updated</p>
                        <p class="text-sm text-gray-700 font-medium">
                            {{ $task->updated_at->format('M d, Y h:i A') }}
                        </p>
                    </div>
                </div>

            </div>

            {{-- ── AI Summary Card ─────────────────────────────────── --}}
            <div class="bg-white shadow rounded-lg p-6 border-l-4 border-indigo-500">

                <div class="flex items-center gap-2 mb-4">
                    <span class="text-indigo-600 text-lg">✨</span>
                    <h4 class="text-md font-semibold text-gray-800">AI Analysis</h4>
                </div>

                @if ($task->ai_summary)
                    {{-- AI Summary --}}
                    <div class="mb-4">
                        <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Summary</p>
                        <p class="text-gray-700 leading-relaxed">
                            {{ $task->ai_summary }}
                        </p>
                    </div>

                    {{-- AI Priority --}}
                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Suggested Priority</p>
                        @php
                            $aiPriorityColors = match ($task->ai_priority) {
                                'low' => 'bg-green-100 text-green-700',
                                'medium' => 'bg-yellow-100 text-yellow-700',
                                'high' => 'bg-red-100 text-red-600',
                                default => 'bg-gray-100 text-gray-600',
                            };
                        @endphp
                        <span class="px-3 py-1 rounded-full text-xs font-medium {{ $aiPriorityColors }}">
                            {{ ucfirst($task->ai_priority) }}
                        </span>
                    </div>
                @else
                    {{-- No AI data yet --}}
                    <div class="flex items-center gap-3 text-gray-400">
                        <svg class="animate-spin h-4 w-4 text-indigo-400" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                        </svg>
                        <p class="text-sm italic">AI summary is being generated...</p>
                    </div>
                @endif

            </div>

        </div>
    </div>

</x-app-layout>
