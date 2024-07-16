<?php

namespace App\Models;

use App\Models\Scopes\PortalScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    protected $connection = 'mysqlCRM';

    use HasFactory;

    public static $filterable = [
        'name',
        'role',
        'email',
        'phone',
        'company',
        'industry',
        'status',
        'origin'
    ];

    protected $fillable = [
        'name',
        'role',
        'email',
        'phone',
        'company',
        'industry',
        'status',
        'origin',
        'data',
    ];

    protected $casts = [
        'data' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected static function booted()
    {
        static::addGlobalScope(new PortalScope(session('portal_id')));

        static::creating(function ($model) {
            $model->portal_id = session('portal_id');
        });
    }
}
