<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Models\Scopes\PortalScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;;

class Project extends Model
{
    use HasFactory;



    public const DEFAULT_STATUSES = [5, 6, 7, 8];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'code',
        'name',
        'deadline',
        'start_date',
        'status_id',
        'limit_hours',
        'description',
        'client_id'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deadline' => 'date',
        'start_date' => 'date',
    ];

    protected static function booted()
    {
        static::addGlobalScope(new PortalScope(session('portal_id')));

        static::creating(function ($model) {
            $model->portal_id = session('portal_id');
        });
    }

    public function tasks(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Task::class);
    }

    public function status(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Status::class);
    }

    public function client(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function users(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(User::class, 'users_projects');
    }

    public function invoices(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public function expenses(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Expense::class);
    }

    public function avaiableStatuses(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ProjectStatuses::class);
    }

    public function boardRules(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(BoardRule::class, 'project_board_rules');
    }

    public function getisOverdueAttribute(): bool
    {
        return $this->deadline < now()->format('Y-m-d');
    }

    public function getTotalHoursAttribute(): int
    {
        return $this->tasks->sum('total_hours') ?? 0;
    }
}
