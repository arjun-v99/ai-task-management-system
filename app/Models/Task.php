<?php

namespace App\Models;

use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'status',
        'priority',
        'due_date',
        'assigned_to',
        'ai_summary',
        'ai_priority',
    ];

    protected $casts = [
        'due_date' => 'date',
        'status'   => TaskStatus::class,
        'priority' => TaskPriority::class,
    ];

    // ─── Relationships ────────────────────────────────────────────────────────

    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    // ─── Query Scopes (used by Repository filters) ────────────────────────────

    public function scopeFilter($query, array $filters)
    {
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['priority'])) {
            $query->where('priority', $filters['priority']);
        }

        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('title', 'like', "%{$filters['search']}%")
                    ->orWhere('description', 'like', "%{$filters['search']}%");
            });
        }

        return $query;
    }
}
