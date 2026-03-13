@props(['task'])

<div class="bg-white rounded-xl shadow-md p-5 hover:shadow-lg transition">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-3">

        <x-task.status-badge :status="$task->status->value" />

        <div class="text-gray-400 text-lg leading-none">
            •••
        </div>

    </div>


    {{-- Title --}}
    <h3 class="font-semibold text-lg text-gray-900 mb-2">
        {{ $task->title }}
    </h3>


    {{-- Priority --}}
    <div class="mb-3">
        <x-task.priority-badge :priority="$task->priority->value" />
    </div>


    {{-- Description --}}
    <p class="text-sm text-gray-500 mb-4">
        {{ Str::limit($task->description, 90) }}
    </p>


    {{-- Meta --}}
    <div class="text-xs text-gray-400 space-y-1 mb-4">

        <div>
            Assigned:
            <span class="text-gray-600">
                {{ $task->assignedUser?->name ?? 'Unassigned' }}
            </span>
        </div>

        <div>
            Due:
            <span class="text-gray-600">
                {{ $task->due_date?->format('M d, Y') ?? '—' }}
            </span>
        </div>

    </div>


    {{-- Actions --}}
    <div class="flex justify-end gap-2">

        <a href="{{ route('tasks.edit', $task) }}" class="text-sm bg-gray-200 hover:bg-gray-300 px-3 py-1 rounded-md">
            Edit
        </a>

        <a href="{{ route('tasks.show', $task) }}"
            class="text-sm bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-md">
            View
        </a>

    </div>

</div>
