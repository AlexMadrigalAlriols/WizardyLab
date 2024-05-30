<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

;

class BoardRule extends Model
{
    use HasFactory, SoftDeletes;

    public const TYPES = [
        'TRIGGER',
        'SCHEDULE'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'order',
        'data',
        'type'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
        'data' => 'array'
    ];

    public function actions()
    {
        return $this->hasMany(BoardRuleLine::class);
    }

    public function projects()
    {
        return $this->belongsToMany(Project::class, 'project_board_rules');
    }

    public function getBoardCountAttribute()
    {
        return $this->projects()->count();
    }
}
