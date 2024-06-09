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

    public function getIconAttribute(): string
    {
        return match($this->mime_type) {
            'image/jpeg', 'image/png', 'image/gif' => 'bx bx-image',
            'application/pdf' => 'bx bx-file-pdf',
            'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'bx bx-file-word',
            'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'bx bx-file-excel',
            'application/vnd.ms-powerpoint', 'application/vnd.openxmlformats-officedocument.presentationml.presentation' => 'bx bx-file-ppt',
            default => 'bx bx-file'
        };
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
        return route('dashboard.inventories.download_file', $this->id);
    }
}
