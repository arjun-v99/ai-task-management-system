<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ isset($task) ? 'Edit Task' : 'Create Task' }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-lg p-6">

                <form method="POST" action="{{ isset($task) ? route('tasks.update', $task) : route('tasks.store') }}">
                    @csrf
                    @isset($task)
                        @method('PUT')
                    @endisset

                    {{-- ── Title ───────────────────────────────────────── --}}
                    <div class="mb-5">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Title <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="title" value="{{ old('title', $task->title ?? '') }}"
                            class="w-full rounded-md border-gray-300 shadow-sm text-sm
                                      @error('title') border-red-500 @enderror"
                            placeholder="Task title" />
                        @error('title')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- ── Description ─────────────────────────────────── --}}
                    <div class="mb-5">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Description
                        </label>
                        <textarea name="description" rows="4"
                            class="w-full rounded-md border-gray-300 shadow-sm text-sm
                                         @error('description') border-red-500 @enderror"
                            placeholder="Describe the task...">{{ old('description', $task->description ?? '') }}</textarea>
                        @error('description')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- ── Status + Priority (side by side) ────────────── --}}
                    <div class="mb-5 grid grid-cols-2 gap-4">

                        {{-- Status --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Status <span class="text-red-500">*</span>
                            </label>
                            <select name="status"
                                class="w-full rounded-md border-gray-300 shadow-sm text-sm
                                           @error('status') border-red-500 @enderror">
                                @foreach (App\Enums\TaskStatus::cases() as $status)
                                    <option value="{{ $status->value }}"
                                        {{ old('status', $task->status->value ?? '') === $status->value ? 'selected' : '' }}>
                                        {{ ucfirst(str_replace('_', ' ', $status->value)) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('status')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Priority --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Priority <span class="text-red-500">*</span>
                            </label>
                            <select name="priority"
                                class="w-full rounded-md border-gray-300 shadow-sm text-sm
                                           @error('priority') border-red-500 @enderror">
                                @foreach (App\Enums\TaskPriority::cases() as $priority)
                                    <option value="{{ $priority->value }}"
                                        {{ old('priority', $task->priority->value ?? '') === $priority->value ? 'selected' : '' }}>
                                        {{ ucfirst($priority->value) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('priority')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>

                    {{-- ── Due Date + Assigned To (side by side) ───────── --}}
                    <div class="mb-5 grid grid-cols-2 gap-4">

                        {{-- Due Date --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Due Date
                            </label>
                            <input type="date" name="due_date"
                                value="{{ old('due_date', isset($task) ? $task->due_date?->format('Y-m-d') : '') }}"
                                class="w-full rounded-md border-gray-300 shadow-sm text-sm
                                          @error('due_date') border-red-500 @enderror" />
                            @error('due_date')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Assigned To --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Assign To
                            </label>
                            <select name="assigned_to"
                                class="w-full rounded-md border-gray-300 shadow-sm text-sm
                                           @error('assigned_to') border-red-500 @enderror">
                                <option value="">— Unassigned —</option>

                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}"
                                        {{ old('assigned_to', $task->assigned_to ?? '') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('assigned_to')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>

                    {{-- ── AI Notice ────────────────────────────────────── --}}
                    @unless (isset($task))
                        <div
                            class="mb-5 bg-indigo-50 border border-indigo-200 rounded-md px-4 py-3 text-sm text-indigo-700">
                            An AI summary and priority suggestion will be automatically generated after saving.
                        </div>
                    @endunless

                    {{-- ── Form Actions ─────────────────────────────────── --}}
                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                        <a href="{{ route('tasks.index') }}" class="text-sm text-gray-500 hover:underline">
                            Cancel
                        </a>
                        <button type="submit"
                            class="bg-indigo-600 text-white px-5 py-2 rounded-md text-sm hover:bg-indigo-700">
                            {{ isset($task) ? 'Update Task' : 'Create Task' }}
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>

</x-app-layout>
