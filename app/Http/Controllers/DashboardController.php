<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TaskService;
use App\Models\Task;

class DashboardController extends Controller
{
    public function __construct(protected TaskService $taskService) {}
    public function index(Request $request)
    {
        $filters = $request->only(['status', 'priority', 'search']);

        $stats = $this->taskService->getDashboardStats();

        // Pass filters so repository scopes kick in
        $tasks = auth()->user()->isAdmin()
            ? $this->taskService->getAll($filters)
            : $this->taskService->getAllForUser(auth()->id(), $filters);

        return view('dashboard', compact('stats', 'tasks'));
    }
}
