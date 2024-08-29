<?php

namespace App\Models;

use App\Models\Scopes\PortalScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobCandidate extends Model
{
    use HasFactory;

    public static $filterable = [
        'id',
        'name',
        'email',
        'phone'
    ];

    protected $fillable = [
        'name',
        'email',
        'phone',
        'answers',
        'resume'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'answers' => 'array'
    ];

    protected static function booted()
    {
        static::addGlobalScope(new PortalScope(session('portal_id')));

        static::creating(function ($model) {
            $model->portal_id = session('portal_id');
        });
    }

    public function job_posting()
    {
        return $this->belongsTo(JobPosting::class);
    }
}
