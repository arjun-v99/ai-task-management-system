<div class="min-h-screen bg-gradient-to-br from-slate-800 via-slate-700 to-slate-900 py-10">
    <div class="max-w-7xl mx-auto px-6">

        {{-- Header --}}
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-3xl font-bold text-white">
                {{ $title }}
            </h1>

            <a href="{{ route('tasks.create') }}"
                class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg shadow">
                + New Task
            </a>
        </div>

        {{-- Filters --}}
        {{ $filters ?? '' }}

        {{-- Main Grid --}}
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">

            {{-- Main Content --}}
            <div class="lg:col-span-3">
                {{ $slot }}
            </div>

            {{-- Sidebar --}}
            <x-dashboard.sidebar :stats="$stats ?? null" />
        </div>

    </div>
</div>
