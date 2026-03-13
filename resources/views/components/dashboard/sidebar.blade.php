@props(['stats' => null])

<div class="space-y-6">

    {{-- Profile Card --}}
    <div class="bg-white rounded-xl shadow p-6">

        <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 rounded-full bg-gray-300 flex items-center justify-center text-sm font-semibold">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>

            <div>
                <p class="font-semibold text-gray-800">
                    {{ auth()->user()->name }}
                </p>
                <p class="text-xs text-gray-500">
                    Admin User
                </p>
            </div>
        </div>

        <nav class="space-y-1 text-sm">

            <a href="{{ route('tasks.index') }}"
                class="block px-3 py-2 rounded-md
               {{ request()->routeIs('tasks.*') ? 'bg-blue-500 text-white' : 'hover:bg-gray-100 text-gray-700' }}">
                Tasks
            </a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="w-full text-left px-3 py-2 rounded-md hover:bg-gray-100 text-gray-700">
                    Logout
                </button>
            </form>

        </nav>

    </div>


    {{-- Metrics --}}
    @if ($stats)
        <div class="bg-white rounded-xl shadow p-6">

            <h4 class="font-semibold mb-4 text-gray-800">
                Task Metrics
            </h4>

            <div class="grid grid-cols-2 gap-4 text-center">

                <div>
                    <p class="text-2xl font-bold text-gray-800">
                        {{ $stats['total'] }}
                    </p>
                    <p class="text-xs text-gray-400">
                        Total
                    </p>
                </div>

                <div>
                    <p class="text-2xl font-bold text-green-500">
                        {{ $stats['completed'] }}
                    </p>
                    <p class="text-xs text-gray-400">
                        Completed
                    </p>
                </div>

                <div>
                    <p class="text-2xl font-bold text-yellow-500">
                        {{ $stats['pending'] }}
                    </p>
                    <p class="text-xs text-gray-400">
                        Pending
                    </p>
                </div>

                <div>
                    <p class="text-2xl font-bold text-red-500">
                        {{ $stats['high'] }}
                    </p>
                    <p class="text-xs text-gray-400">
                        High
                    </p>
                </div>

            </div>

        </div>
    @endif

</div>
