<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Item extends Model
{
    use HasFactory;
    public const PAGE_SIZE = 10;

    protected $fillable = [
        'name',
        'stock',
        'price',
        'description',
        'shop_place',
        'reference'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function assignments(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ItemUserInventory::class);
    }

    public function files(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ItemFile::class);
    }

    public function getRemainingStockAttribute(): int
    {
        return $this->stock - $this->assignments->sum('quantity');
    }

    public function getCoverAttribute(): ?string
    {
        if($path = $this->files()->first()?->path) {
            return asset('storage/' . $path);
        }

        return asset('img/default-image.jpeg');
    }
}
