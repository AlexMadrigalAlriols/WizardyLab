<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

;

class Notification extends Model
{
    use HasFactory, SoftDeletes;
    public const PAGE_SIZE = 10;

    public const TYPES = [
        'task' => 'task',
        'comment' => 'comment',
        'project' => 'project',
        'leave' => 'leave',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'title',
        'content',
        'type',
        'is_read'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function scopeUnread($query): void
    {
        $query->where('is_read', false);
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getIconAttribute(): string
    {
        return match ($this->type) {
            'task' => 'bx-clipboard',
            'comment' => 'bx-comment',
            'project' => 'bx-dashboard',
            'leave' => 'bxs-plane-take-off',
            default => 'bx-bell',
        };
    }

    public function getRouteUrlAttribute(): string
    {
        return match ($this->type) {
            'task' => route('dashboard.tasks.show', $this->content),
            'comment' => route('dashboard.tasks.show', $this->content),
            'project' => route('dashboard.projects.board', $this->content),
            'leave' => route('dashboard.leaves.index'),
            default => '#',
        };
    }
}
