<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Tasks
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- ── Top Bar ─────────────────────────────────────────── --}}
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-medium text-gray-900">All Tasks</h3>
                <a href="{{ route('tasks.create') }}"
                    class="bg-indigo-600 text-white px-4 py-2 rounded-md text-sm hover:bg-indigo-700">
                    + New Task
                </a>
            </div>

            {{-- ── Success Flash ───────────────────────────────────── --}}
            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            {{-- ── Filter Bar ──────────────────────────────────────── --}}
            <form method="GET" action="{{ route('tasks.index') }}" class="mb-6 flex gap-3 flex-wrap">

                {{-- Status dropdown --}}
                <select name="status" class="rounded-md border-gray-300 text-sm">
                    <option value="">All Statuses</option>
                    {{-- LOOP through TaskStatus enum and render each as an option --}}
                    {{-- Mark as selected if request('status') matches --}}
                </select>

                {{-- Priority dropdown --}}
                <select name="priority" class="rounded-md border-gray-300 text-sm">
                    <option value="">All Priorities</option>
                    {{-- LOOP through TaskPriority enum and render each as an option --}}
                </select>

                {{-- Search --}}
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search tasks..."
                    class="rounded-md border-gray-300 text-sm flex-1" />

                <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded-md text-sm">
                    Filter
                </button>

                <a href="{{ route('tasks.index') }}" class="text-sm text-gray-500 self-center underline">
                    Clear
                </a>
            </form>

            {{-- ── Task Table ──────────────────────────────────────── --}}
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50 text-left text-gray-500 uppercase text-xs">
                        <tr>
                            <th class="px-6 py-3">Title</th>
                            <th class="px-6 py-3">Status</th>
                            <th class="px-6 py-3">Priority</th>
                            <th class="px-6 py-3">AI Priority</th>
                            <th class="px-6 py-3">Due Date</th>
                            <th class="px-6 py-3">Assigned To</th>
                            <th class="px-6 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-gray-700">

                        @forelse($tasks as $task)
                            <tr class="hover:bg-gray-50">

                                {{-- Title --}}
                                <td class="px-6 py-4 font-medium">
                                    <a href="{{ route('tasks.show', $task) }}" class="text-indigo-600 hover:underline">
                                        {{ $task->title }}
                                    </a>
                                </td>

                                {{-- Status Badge --}}
                                <td class="px-6 py-4">
                                    {{-- RENDER a badge span with color based on $task->status --}}
                                    {{-- pending=grey, in_progress=blue, completed=green, cancelled=red --}}
                                </td>

                                {{-- Priority Badge --}}
                                <td class="px-6 py-4">
                                    {{-- RENDER a badge span with color based on $task->priority --}}
                                    {{-- low=green, medium=yellow, high=red --}}
                                </td>

                                {{-- AI Priority --}}
                                <td class="px-6 py-4">
                                    @if ($task->ai_priority)
                                        {{-- RENDER same badge style as priority --}}
                                    @else
                                        <span class="text-gray-400 italic">Pending...</span>
                                    @endif
                                </td>

                                {{-- Due Date --}}
                                <td class="px-6 py-4">
                                    {{ $task->due_date?->format('M d, Y') ?? '—' }}
                                </td>

                                {{-- Assigned To --}}
                                <td class="px-6 py-4">
                                    {{ $task->assignedUser?->name ?? 'Unassigned' }}
                                </td>

                                {{-- Actions --}}
                                <td class="px-6 py-4 flex gap-2">
                                    @can('update', $task)
                                        <a href="{{ route('tasks.edit', $task) }}"
                                            class="text-indigo-600 hover:underline">Edit</a>
                                    @endcan

                                    @can('delete', $task)
                                        <form method="POST" action="{{ route('tasks.destroy', $task) }}"
                                            onsubmit="return confirm('Delete this task?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:underline">
                                                Delete
                                            </button>
                                        </form>
                                    @endcan
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-10 text-center text-gray-400">
                                    No tasks found.
                                </td>
                            </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>

            {{-- ── Pagination ──────────────────────────────────────── --}}
            <div class="mt-4">
                {{ $tasks->withQueryString()->links() }}
            </div>

        </div>
    </div>

</x-app-layout>
