<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class InventoryFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'inventory_id',
        'user_id',
        'title',
        'path',
        'size'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function inventory(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Inventory::class, 'inventory_id');
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
