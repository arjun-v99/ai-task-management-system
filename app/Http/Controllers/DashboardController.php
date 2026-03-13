<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TaskService;
use App\Models\Task;

class DashboardController extends Controller
{
    public function __construct(protected TaskService $taskService) {}
    public function index()
    {
        $stats       = $this->taskService->getDashboardStats();
        $recentTasks = $this->taskService->getRecentTasks();

        return view('dashboard', compact('stats', 'recentTasks'));
    }
}
