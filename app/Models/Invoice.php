<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

;

class Invoice extends Model
{
    use HasFactory, SoftDeletes;
    public const PAGE_SIZE = 10;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'number',
        'project_id',
        'issue_date',
        'amount',
        'tax',
        'total',
        'status_id',
        'data',
        'client_id'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
        'data' => 'array'
    ];

    public function project(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function status(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Status::class);
    }

    public function client(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        if(!is_null($this->project)) {
            return $this->project->client();
        }

        return $this->belongsTo(Client::class);
    }
}
