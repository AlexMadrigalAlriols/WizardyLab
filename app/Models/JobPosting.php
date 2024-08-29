<?php

namespace App\Models;

use App\Models\Scopes\PortalScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobPosting extends Model
{
    use HasFactory;

    public static $filterable = [
        'id',
        'title',
        'location',
        'description',
        'requirements',
        'skills',
        'active',
        'posted_at',
        'expires_at'
    ];

    protected $fillable = [
        'title',
        'location',
        'description',
        'requirements',
        'skills',
        'active',
        'posted_at',
        'expires_at'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'posted_at' => 'datetime',
        'expires_at' => 'datetime',
        'questions' => 'array'
    ];

    protected static function booted()
    {
        static::addGlobalScope(new PortalScope(session('portal_id')));

        static::creating(function ($model) {
            $model->portal_id = session('portal_id');
        });
    }

    public function candidates()
    {
        return $this->hasMany(JobCandidate::class, 'job_candidates');
    }
}
