<x-app-layout>

    <x-dashboard-layout title="Task List" :stats="$stats">

        <x-slot:filters>
            <x-dashboard.filters />
        </x-slot:filters>

        <div class="grid md:grid-cols-2 gap-6">
            @foreach ($tasks as $task)
                <x-task.card :task="$task" />
            @endforeach
        </div>

    </x-dashboard-layout>

</x-app-layout>
