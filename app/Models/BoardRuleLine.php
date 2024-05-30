<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;;

class BoardRuleLine extends Model
{
    use HasFactory;

    public const ACTIONS = [
        'MOVE',
        'SET',
        'ADD',
        'REMOVE'
    ];

    public const TYPES = [
        'TRIGGER',
        'ACTION'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'board_rule_id',
        'operator',
        'order',
        'data'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'data' => 'array'
    ];

    protected function sentence(): Attribute
    {
        return new Attribute(
            get: fn ($value) => 'When a card is moved to the list "Done" by anyone',
        );
    }

    public function board_rule(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(BoardRule::class);
    }

    public function parent(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(__CLASS__);
    }

    public function childs(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(__CLASS__, 'parent_id');
    }
}
