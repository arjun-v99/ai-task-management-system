<x-app-layout>

    <div class="min-h-screen bg-gradient-to-br from-slate-800 via-slate-700 to-slate-900 py-10">
        <div class="max-w-7xl mx-auto px-6">

            {{-- HEADER --}}
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-3xl font-bold text-white">
                    {{ isset($task) ? 'Edit Task' : 'Create Task' }}
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


                {{-- FORM CARD --}}
                <div class="lg:col-span-3">

                    <div class="bg-white rounded-xl shadow-md p-8">

                        <form method="POST"
                            action="{{ isset($task) ? route('tasks.update', $task) : route('tasks.store') }}">

                            @csrf
                            @isset($task)
                                @method('PUT')
                            @endisset


                            {{-- TITLE --}}
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Task Title
                                </label>

                                <input type="text" name="title" value="{{ old('title', $task->title ?? '') }}"
                                    class="w-full border rounded-lg px-4 py-2 text-sm
                               @error('title') border-red-500 @enderror"
                                    placeholder="e.g. Launch New Campaign">

                                @error('title')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>


                            {{-- DESCRIPTION --}}
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Description
                                </label>

                                <textarea name="description" rows="4"
                                    class="w-full border rounded-lg px-4 py-3 text-sm
                                  @error('description') border-red-500 @enderror"
                                    placeholder="Describe the task...">{{ old('description', $task->description ?? '') }}</textarea>
                            </div>



                            {{-- PRIORITY BUTTON GROUP --}}
                            <div class="mb-6">

                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Priority
                                </label>

                                <div class="flex gap-3">

                                    @foreach (App\Enums\TaskPriority::cases() as $priority)
                                        <label class="cursor-pointer">

                                            <input type="radio" name="priority" value="{{ $priority->value }}"
                                                class="hidden peer"
                                                {{ old('priority', $task->priority->value ?? '') === $priority->value ? 'checked' : '' }}>

                                            <span
                                                class="px-4 py-2 rounded-lg border text-sm
                                        peer-checked:bg-blue-500
                                        peer-checked:text-white">

                                                {{ ucfirst($priority->value) }}

                                            </span>

                                        </label>
                                    @endforeach

                                </div>

                            </div>



                            {{-- STATUS + DUE DATE --}}
                            <div class="grid grid-cols-2 gap-4 mb-6">

                                <div>

                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Status
                                    </label>

                                    <select name="status" class="w-full border rounded-lg px-4 py-2 text-sm">

                                        @foreach (App\Enums\TaskStatus::cases() as $status)
                                            <option value="{{ $status->value }}"
                                                {{ old('status', $task->status->value ?? '') === $status->value ? 'selected' : '' }}>

                                                {{ ucfirst(str_replace('_', ' ', $status->value)) }}

                                            </option>
                                        @endforeach

                                    </select>

                                </div>



                                <div>

                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Due Date
                                    </label>

                                    <input type="date" name="due_date"
                                        value="{{ old('due_date', isset($task) ? $task->due_date?->format('Y-m-d') : '') }}"
                                        class="w-full border rounded-lg px-4 py-2 text-sm">

                                </div>

                            </div>



                            {{-- ASSIGN USER --}}
                            <div class="mb-6">

                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Assign To
                                </label>

                                <select name="assigned_to" class="w-full border rounded-lg px-4 py-2 text-sm">

                                    <option value="">Unassigned</option>

                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}"
                                            {{ old('assigned_to', $task->assigned_to ?? '') == $user->id ? 'selected' : '' }}>

                                            {{ $user->name }}

                                        </option>
                                    @endforeach

                                </select>

                            </div>



                            {{-- AI NOTICE --}}
                            @unless (isset($task))
                                <div
                                    class="mb-6 bg-indigo-50 border border-indigo-200 rounded-lg px-4 py-3 text-sm text-indigo-700">
                                    AI summary and priority suggestion will be generated after saving.
                                </div>
                            @endunless



                            {{-- SAVE BUTTON --}}
                            <div class="flex justify-center pt-4">

                                <button type="submit"
                                    class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg">

                                    {{ isset($task) ? 'Save Changes' : 'Create Task' }}

                                </button>

                            </div>

                        </form>

                    </div>

                </div>



                {{-- RIGHT SIDEBAR --}}
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

                            <div class="px-3 py-2 rounded hover:bg-gray-100">
                                Users
                            </div>

                            <div class="px-3 py-2 rounded hover:bg-gray-100">
                                Logout
                            </div>
                        </div>

                    </div>

                </div>

            </div>

        </div>
    </div>

</x-app-layout>
