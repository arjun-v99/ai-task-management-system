<x-app-layout>

    <div class="min-h-screen bg-gradient-to-br from-slate-800 via-slate-700 to-slate-900 py-10">
        <div class="max-w-7xl mx-auto px-6">

            {{-- HEADER --}}
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-3xl font-bold text-white">
                    Task Detail + AI Summary
                </h1>

                <a href="{{ route('tasks.create') }}"
                    class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg shadow">
                    + New Task
                </a>
            </div>


            {{-- FILTERS --}}
            <form method="GET" action="{{ route('tasks.index') }}" class="flex flex-wrap items-center gap-3 mb-8">

                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search Filter Task"
                    class="bg-white/90 rounded-md px-4 py-2 w-60 focus:outline-none">

                <select name="status" class="bg-white/90 rounded-md px-4 py-2">
                    <option value="">Status</option>
                    <option value="pending">Pending</option>
                    <option value="in_progress">In Progress</option>
                    <option value="completed">Completed</option>
                </select>

                <select name="assigned" class="bg-white/90 rounded-md px-4 py-2">
                    <option value="">Assigned</option>
                </select>

                <select name="priority" class="bg-white/90 rounded-md px-4 py-2">
                    <option value="">Priority</option>
                    <option value="low">Low</option>
                    <option value="medium">Medium</option>
                    <option value="high">High</option>
                </select>

                <button class="bg-blue-500 text-white px-4 py-2 rounded-md">
                    Filter
                </button>

                <a href="{{ route('tasks.index') }}" class="bg-gray-300 px-4 py-2 rounded-md">
                    Clear
                </a>

            </form>


            {{-- MAIN GRID --}}
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">


                {{-- TASK DETAIL CARD --}}
                <div class="lg:col-span-3">

                    <div class="bg-white rounded-xl shadow-md p-8">

                        {{-- TITLE --}}
                        <div class="flex items-start justify-between mb-6">

                            <h2 class="text-2xl font-bold text-gray-900">
                                {{ $task->title }}
                            </h2>

                            <span class="text-gray-400 text-xl">•••</span>

                        </div>


                        {{-- STATUS + PRIORITY --}}
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
                                Status {{ ucfirst(str_replace('_', ' ', $task->status->value)) }}
                            </span>

                            <span class="px-3 py-1 rounded-full text-xs font-medium {{ $priorityColors }}">
                                Priority {{ ucfirst($task->priority->value) }}
                            </span>

                        </div>



                        {{-- DESCRIPTION BLOCK --}}
                        <div class="bg-gray-50 rounded-lg p-6 mb-6">

                            <h4 class="font-semibold text-gray-800 mb-3">
                                Description
                            </h4>

                            <p class="text-sm text-gray-500 mb-3">
                                Assigned to: {{ $task->assignedUser?->name ?? 'Unassigned' }}
                            </p>

                            <div class="bg-white rounded-md border px-4 py-2 text-sm text-gray-600 mb-4">
                                Due Date: {{ $task->due_date?->format('Y-m-d') ?? '—' }}
                            </div>

                            <p class="text-gray-600 text-sm">
                                {{ $task->description ?? 'No description provided.' }}
                            </p>

                        </div>



                        {{-- AI SUMMARY --}}
                        <div class="bg-gray-50 rounded-lg p-6 mb-6">

                            <h4 class="font-semibold text-gray-800 mb-3">
                                AI Generated Summary
                            </h4>

                            @if ($task->ai_summary)
                                <p class="text-gray-600 text-sm mb-4">
                                    {{ $task->ai_summary }}
                                </p>

                                <div class="text-sm">
                                    <span class="font-semibold">AI Priority:</span>
                                    {{ ucfirst($task->ai_priority) }}
                                </div>
                            @else
                                <p class="text-sm text-gray-400 italic">
                                    AI summary not generated yet.
                                </p>
                            @endif

                        </div>



                        {{-- SAVE BUTTON --}}
                        <div class="flex justify-center">

                            <button class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg">
                                Save Changes
                            </button>

                        </div>

                    </div>

                </div>



                {{-- RIGHT PANEL (same as dashboard) --}}
                <div class="space-y-6">

                    <div class="bg-white rounded-xl shadow p-6">

                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-10 h-10 bg-gray-300 rounded-full"></div>

                            <div>
                                <p class="font-semibold">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-gray-500">Admin User</p>
                            </div>
                        </div>

                        <div class="space-y-2 text-sm">

                            <div class="bg-blue-500 text-white px-3 py-2 rounded">
                                Tasks
                            </div>


                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button class="w-full text-left px-3 py-2 rounded-md hover:bg-gray-100 text-gray-700">
                                    Logout
                                </button>
                            </form>
                        </div>

                    </div>



                    {{-- METRICS --}}
                    <div class="bg-white rounded-xl shadow p-6">

                        <h4 class="font-semibold mb-4">Task Metrics</h4>

                        <div class="grid grid-cols-2 gap-4 text-center">


                            <div>
                                <p class="text-2xl font-bold text-green-500">{{ $stats['completed'] }}</p>
                                <p class="text-xs text-gray-400">Completed</p>
                            </div>

                            <div>
                                <p class="text-2xl font-bold text-yellow-500">{{ $stats['pending'] }}</p>
                                <p class="text-xs text-gray-400">Pending</p>
                            </div>

                            <div>
                                <p class="text-2xl font-bold text-red-500">{{ $stats['high'] }}</p>
                                <p class="text-xs text-gray-400">High</p>
                            </div>

                        </div>

                    </div>

                </div>


            </div>

        </div>
    </div>

</x-app-layout>
