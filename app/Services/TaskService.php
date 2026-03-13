<?php

namespace App\Services;

use App\Repositories\Contracts\TaskRepositoryInterface;
use Illuminate\Support\Facades\DB;
use App\Models\Task;

class TaskService
{
    public function __construct(
        protected TaskRepositoryInterface $repo,
        protected AIService $aiService,
    ) {}

    public function getAll(array $filters = [])
    {
        return $this->repo->all($filters);
    }

    public function getAllForUser(int $userId, array $filters = [])
    {
        return $this->repo->allForUser($userId, $filters);
    }

    public function getById(int $id)
    {
        return $this->repo->find($id);
    }

    public function store(array $data)
    {
        return DB::transaction(function () use ($data) {
            // 1. Create the task
            $task = $this->repo->create($data);

            // 2. Ask AI for summary & priority
            $aiData = $this->aiService->generateSummary($task);

            // 3. Persist AI results back to the task
            $task = $this->repo->update($task->id, $aiData);

            return $task;
        });
    }

    public function update(int $id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $task = $this->repo->update($id, $data);

            // Re-generate AI summary when task content changes
            if (isset($data['title']) || isset($data['description'])) {
                $aiData = $this->aiService->generateSummary($task);
                $task   = $this->repo->update($task->id, $aiData);
            }

            return $task;
        });
    }

    public function destroy(int $id): bool
    {
        return $this->repo->delete($id);
    }

    public function getDashboardStats(): array
    {
        // These raw counts are fine here — no business reason to repo-ify them
        return [
            'total'     => Task::count(),
            'completed' => Task::where('status', 'completed')->count(),
            'pending'   => Task::where('status', 'pending')->count(),
            'high'      => Task::where('priority', 'high')->count(),
        ];
    }
}
