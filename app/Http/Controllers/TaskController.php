<?php

namespace App\Http\Controllers;

use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use Illuminate\Http\Request;
use App\Services\TaskService;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;  // <– add if needed

class TaskController extends Controller
{
    use AuthorizesRequests;
    public function __construct(protected TaskService $taskService) {}

    public function index(Request $request)
    {
        $filters = $request->only(['status', 'priority', 'search']);

        // Admins see all tasks; regular users see only their own
        $tasks = $request->user()->isAdmin()
            ? $this->taskService->getAll($filters)
            : $this->taskService->getAllForUser($request->user()->id, $filters);

        $taskStatuses = TaskStatus::cases();
        $priorities = TaskPriority::cases();

        return view('tasks.index', compact('tasks', 'taskStatuses', 'priorities'));
    }

    public function create()
    {
        $users = User::orderBy('name')->get();
        return view('tasks.create', ['users' => $users]);
    }

    public function store(StoreTaskRequest $request)
    {
        $task = $this->taskService->store($request->validated());

        return redirect()->route('tasks.show', $task)
            ->with('success', 'Task created and AI summary generated!');
    }

    public function show(int $id)
    {
        $stats       = $this->taskService->getDashboardStats();
        $task = $this->taskService->getById($id);
        $this->authorize('view', $task);

        return view('tasks.show', compact('task', 'stats'));
    }

    public function edit(int $id)
    {
        $users = User::orderBy('name')->get();
        $task = $this->taskService->getById($id);
        $this->authorize('update', $task);

        return view('tasks.create', compact('task', 'users'));
    }

    public function update(UpdateTaskRequest $request, int $id)
    {
        $this->authorize('update', $this->taskService->getById($id));

        $task = $this->taskService->update($id, $request->validated());

        return redirect()->route('tasks.show', $task)
            ->with('success', 'Task updated successfully!');
    }

    public function destroy(int $id)
    {
        $task = $this->taskService->getById($id);
        $this->authorize('delete', $task);

        $this->taskService->destroy($id);

        return redirect()->route('tasks.index')
            ->with('success', 'Task deleted.');
    }
}
