<form method="GET" class="flex flex-wrap items-center gap-3 mb-8">

    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search Filter Task"
        class="bg-white/90 rounded-md px-4 py-2 w-60">

    <select name="status" class="bg-white/90 rounded-md px-4 py-2">
        <option value="">Status</option>
        <option value="pending">Pending</option>
        <option value="in_progress">In Progress</option>
        <option value="completed">Completed</option>
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
