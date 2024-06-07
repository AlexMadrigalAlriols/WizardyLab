<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserInventories extends Model
{
    use HasFactory;

    protected $fillable = [
        'users_id',
        'inventories_id',
        'quantity',
        'extract_date',
        'return_date'
    ];

    public function inventory(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Inventory::class, 'inventories_id');
    }
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'users_id');
    }
}


