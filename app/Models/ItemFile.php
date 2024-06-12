<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class ItemFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id',
        'user_id',
        'title',
        'path',
        'size'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function item(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getRealSizeAttribute(): string
    {
        return round($this->size / 1024, 2) . 'kb';
    }

    public function getIsImageAttribute(): bool
    {
        return in_array($this->mime_type, ['image/jpeg', 'image/png', 'image/gif']);
    }

    public function getDownloadLinkAttribute(): string
    {
        return route('dashboard.items.download_file', $this->id);
    }
}
