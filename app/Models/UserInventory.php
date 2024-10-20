<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserInventory extends Model
{
    use HasFactory;


    public static $filterable = [
        'user.id',
        'user.name',
        'user.email',
        'items.item.reference',
        'items.item.name',
        'items.item.stock',
        'extract_date',
        'return_date'
    ];

    protected $fillable = [
        'user_id',
        'extract_date',
        'return_date'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function items(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ItemUserInventory::class);
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}


