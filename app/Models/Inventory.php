<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Inventory extends Model
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

    public function UsersInventories(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(UserInventories::class, 'inventory_id');
    }

    public function inventory_files(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(InventoryFile::class);
    }

    public function getRemainingStockAttribute(): int
    {
        return $this->stock - $this->UsersInventories->sum('quantity');
    }

    public function getCoverAttribute(): ?string
    {
        if($path = $this->inventory_files()->first()?->path) {
            return asset('storage/' . $path);
        }

        return null;
    }
}
