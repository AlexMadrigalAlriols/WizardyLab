<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Helpers\TaskAttendanceHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    use HasFactory;

    public const ACTIONS = [
        'created' => 'created',
        'updated' => 'updated',
        'deleted' => 'deleted',
        'moved' => 'moved',
        'attached' => 'attached'
    ];

    public const MORPHABLES = [
        Task::class
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'event',
        'auditable_type',
        'auditable_id',
        'data'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'data' => 'array'
    ];


    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function auditable()
    {
        return $this->morphTo();
    }

    public function getIconAttribute(): string
    {
        $icons = [
            'created' => 'bx bx-plus',
            'updated' => 'bx bx-pencil',
            'deleted' => 'bx bx-trash',
            'moved' => 'bx bx-move',
            'attached' => 'bx bx-paperclip'
        ];

        return $icons[$this->event];
    }
}
