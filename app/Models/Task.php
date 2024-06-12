<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Helpers\TaskAttendanceHelper;
use App\Models\Scopes\PortalScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use HasFactory, SoftDeletes;

    public const PAGE_SIZE = 10;
    public const PRIORITIES = [
        'low' => 'low',
        'medium' => 'medium',
        'high' => 'high',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'code',
        'project_id',
        'title',
        'description',
        'duedate',
        'start_date',
        'limit_hours',
        'priority',
        'assignee_id',
        'status_id',
        'task_id',
        'order',
        'archive_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'duedate' => 'datetime',
        'start_date' => 'datetime',
        'arcive_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    protected static function booted()
    {
        static::addGlobalScope(new PortalScope(session('portal_id')));

        static::creating(function ($model) {
            $model->portal_id = session('portal_id');
        });
    }

    public function project(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function assignee(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function status(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Status::class);
    }

    public function subtasks(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Task::class);
    }

    public function parent(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    public function labels(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Label::class, 'tasks_labels');
    }

    public function users(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(User::class, 'users_tasks');
    }

    public function files(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(TaskFile::class);
    }

    public function comments(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(TaskComment::class);
    }

    public function activity(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(AuditLog::class, 'auditable_id')->where('auditable_type', Task::class);
    }


    public function getAvaiableStatusesAttribute(): \Illuminate\Database\Eloquent\Collection
    {
        if($this->project) {
            return $this->project->avaiableStatuses;
        }

        return Status::where('morphable', self::class)->get();
    }

    public function getCoverAttribute(): ?string
    {
        if($path = $this->files()->where('mime_type', 'like', '%image%')->first()?->path) {
            return asset('storage/' . $path);
        }

        return null;
    }

    // Attendance
    public function getisOverdueAttribute(): bool
    {
        return $this->duedate < now()->format('Y-m-d');
    }

    public function taskAttendances(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(TaskAttendance::class);
    }

    public function getTotalHoursAttribute(): string
    {
        return TaskAttendanceHelper::getTotalHoursDecimal($this);
    }

    public function getTimerAttribute(): string
    {
        return TaskAttendanceHelper::getTimer($this);
    }
}
