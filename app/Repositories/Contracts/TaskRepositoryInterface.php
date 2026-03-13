<?php

namespace App\Repositories\Contracts;

interface TaskRepositoryInterface
{
    public function all(array $filters = []);
    public function find(int $id);
    public function create(array $data);
    public function update(int $id, array $data);
    public function delete(int $id);
    public function allForUser(int $userId, array $filters = []);
    public function getRecent(int $limit = 5);
    public function count(): int;
    public function countByStatus(string $status): int;
    public function countByPriority(string $priority): int;
}
