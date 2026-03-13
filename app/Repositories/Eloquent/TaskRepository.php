<?php

namespace App\Repositories\Eloquent;

use App\Models\Task;
use App\Repositories\Contracts\TaskRepositoryInterface;

class TaskRepository implements TaskRepositoryInterface
{
    public function all(array $filters = [])
    {
        return Task::query()
            ->with('assignedUser')
            ->filter($filters)
            ->latest()
            ->paginate(10);
    }

    public function allForUser(int $userId, array $filters = [])
    {
        return Task::query()
            ->with('assignedUser')
            ->where('assigned_to', $userId)
            ->filter($filters)
            ->latest()
            ->paginate(10);
    }

    public function find(int $id)
    {
        return Task::with('assignedUser')->findOrFail($id);
    }

    public function create(array $data)
    {
        return Task::create($data);
    }

    public function update(int $id, array $data)
    {
        $task = $this->find($id);
        $task->update($data);
        return $task->fresh();
    }

    public function delete(int $id)
    {
        $task = $this->find($id);
        return $task->delete();
    }

    public function getRecent(int $limit = 5)
    {
        return Task::query()
            ->with('assignedUser')
            ->latest()
            ->take($limit)
            ->get();
    }

    public function count(): int
    {
        return Task::count();
    }

    public function countByStatus(string $status): int
    {
        return Task::where('status', $status)->count();
    }

    public function countByPriority(string $priority): int
    {
        return Task::where('priority', $priority)->count();
    }
}
