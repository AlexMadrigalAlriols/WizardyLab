<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Models\Scopes\PortalScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use HasFactory, SoftDeletes;


    public static $filterable = [
        'number',
        'issue_date',
        'total',
        'amount',
        'tax',
        'client.name',
        'client.email',
        'client.phone',
        'project.id',
        'project.name',
        'status.name'
    ];

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

    protected static function booted()
    {
        static::addGlobalScope(new PortalScope(session('portal_id')));

        static::creating(function ($model) {
            $model->portal_id = session('portal_id');
        });
    }

    public function project(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function portal(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Portal::class);
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

    public function getFilePathAttribute() {
        return 'invoices/' . $this->portal_id . '/' . $this->number . '.pdf';
    }
}
