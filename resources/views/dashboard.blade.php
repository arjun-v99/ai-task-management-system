<x-app-layout>

    <div class="min-h-screen bg-gradient-to-br from-slate-800 via-slate-700 to-slate-900 py-10">
        <div class="max-w-7xl mx-auto px-6">

            {{-- HEADER --}}
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-3xl font-bold text-white">Task List</h1>

                <a href="{{ route('tasks.create') }}"
                    class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg shadow">
                    + New Task
                </a>
            </div>


            {{-- FILTERS --}}
            <form method="GET" action="{{ route('dashboard') }}" class="flex flex-wrap items-center gap-3 mb-8">

                {{-- Search --}}
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search Filter Task"
                    class="bg-white/90 rounded-md px-4 py-2 w-60 focus:outline-none focus:ring-2 focus:ring-blue-400">

                {{-- Status --}}
                <select name="status" class="bg-white/90 rounded-md px-4 py-2 focus:outline-none">

                    <option value="">Status</option>

                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>
                        Pending
                    </option>

                    <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>
                        In Progress
                    </option>

                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>
                        Completed
                    </option>

                </select>





                {{-- Priority --}}
                <select name="priority" class="bg-white/90 rounded-md px-4 py-2 focus:outline-none">

                    <option value="">Priority</option>

                    <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Low</option>
                    <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                    <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>High</option>

                </select>


                {{-- Filter Button --}}
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md shadow">
                    Filter
                </button>


                {{-- Clear Button --}}
                <a href="{{ route('dashboard') }}"
                    class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-md">
                    Clear
                </a>

            </form>


            {{-- MAIN GRID --}}
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">

                {{-- TASK CARDS --}}
                <div class="lg:col-span-3">

                    <div class="grid md:grid-cols-2 gap-6">

                        @forelse ($tasks as $task)
                            <div class="bg-white rounded-xl shadow-md p-5">

                                {{-- STATUS --}}
                                <div class="flex items-center justify-between mb-3">
                                    <span class="text-sm font-medium text-blue-600">
                                        ● {{ ucfirst($task->status->value) }}
                                    </span>

                                    <span class="text-gray-400">•••</span>
                                </div>

                                {{-- TITLE --}}
                                <h3 class="font-semibold text-lg mb-2">
                                    {{ $task->title }}
                                </h3>

                                {{-- PRIORITY --}}
                                <span class="text-xs px-2 py-1 rounded-full bg-red-100 text-red-600">
                                    Priority {{ ucfirst($task->priority->value ?? 'Low') }}
                                </span>

                                {{-- DESCRIPTION --}}
                                <p class="text-sm text-gray-500 mt-3 mb-4">
                                    {{ Str::limit($task->description, 90) }}
                                </p>

                                {{-- DUE DATE --}}
                                <p class="text-sm text-gray-400 mb-4">
                                    Due {{ $task->due_date }}
                                </p>

                                {{-- BUTTONS --}}
                                <div class="flex justify-end gap-2">

                                    <a href="{{ route('tasks.edit', $task) }}"
                                        class="text-sm bg-gray-200 px-3 py-1 rounded">
                                        Edit
                                    </a>

                                    <a href="{{ route('tasks.show', $task) }}"
                                        class="text-sm bg-blue-500 text-white px-3 py-1 rounded">
                                        View
                                    </a>

                                </div>

                            </div>
                        @empty
                            <div class="col-span-2 text-center text-white/60 py-12">
                                No tasks found.
                            </div>
                        @endforelse

                    </div>
                    {{-- Pagination --}}
                    <div class="mt-6">
                        {{ $tasks->withQueryString()->links() }}
                    </div>
                </div>



                {{-- PROFILE PANEL --}}
                <div class="space-y-6">

                    <div class="bg-white rounded-xl shadow p-6">

                        {{-- USER --}}
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-10 h-10 bg-gray-300 rounded-full"></div>

                            <div>
                                <p class="font-semibold">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-gray-500">Admin User</p>
                            </div>
                        </div>


                        {{-- MENU --}}
                        <div class="space-y-2 text-sm">

                            <div class="bg-blue-500 text-white px-3 py-2 rounded">
                                Tasks
                            </div>

                            <div class="px-3 py-2 rounded hover:bg-gray-100">
                                Users
                            </div>

                            <div class="px-3 py-2 rounded hover:bg-gray-100">
                                Logout
                            </div>

                        </div>

                    </div>



                    {{-- METRICS --}}
                    <div class="bg-white rounded-xl shadow p-6">

                        <h4 class="font-semibold mb-4">Task Metrics</h4>

                        <div class="grid grid-cols-2 gap-4 text-center">

                            <div>
                                <p class="text-2xl font-bold">{{ $stats['total'] }}</p>
                                <p class="text-xs text-gray-400">Total</p>
                            </div>

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
