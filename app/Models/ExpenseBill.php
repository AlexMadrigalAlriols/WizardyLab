<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class ExpenseBill extends Model
{
    use HasFactory;

    protected $fillable = [
        'expense_id',
        'user_id',
        'title',
        'path',
        'size'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function expense(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Expense::class);
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getRealSizeAttribute(): string
    {
        return round($this->size / 1024, 2) . 'kb';
    }

    public function getDownloadLinkAttribute(): string
    {
        return route('dashboard.inventories.download_file', $this->id);
    }
}
