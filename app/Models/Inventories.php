<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Inventories extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'stock',
        'price',
        'description',
        'shop_place',
        'reference'
    ];
    public function users_inventories(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Users_inventories::class);
    }
}
