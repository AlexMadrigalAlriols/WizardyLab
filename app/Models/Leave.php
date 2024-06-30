<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Models\Scopes\PortalScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

;

class Leave extends Model
{
    use HasFactory, SoftDeletes;
    public const PAGE_SIZE = 10;

    public static $filterable = [
        'date',
        'reason',
        'approved',
        'user.name',
        'user.email',
        'leaveType.name'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'date',
        'leave_type_id',
        'approved',
        'reason',
        'portal_id'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'date' => 'datetime'
    ];

    protected static function booted()
    {
        static::addGlobalScope(new PortalScope(session('portal_id'), true));
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function leaveType(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(LeaveType::class);
    }
}
