<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TaskService;

class TaskController extends Controller
{
    public function __construct(protected TaskService $taskService) {}

    public function index(Request $request)
    {
        $filters = $request->only(['status', 'priority', 'search']);

        // Admins see all tasks; regular users see only their own
        $tasks = $request->user()->isAdmin()
            ? $this->taskService->getAll($filters)
            : $this->taskService->getAllForUser($request->user()->id, $filters);

        return view('tasks.index', compact('tasks'));
    }
}
