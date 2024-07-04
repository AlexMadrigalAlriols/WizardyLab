<?php

namespace App\Models;

use App\Models\Scopes\PortalScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DeliveryNote extends Model
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
        'client.phone'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'number',
        'issue_date',
        'client_id',
        'amount',
        'tax',
        'total',
        'data'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
        'issue_date' => 'date',
        'data' => 'array'
    ];

    protected static function booted()
    {
        static::addGlobalScope(new PortalScope(session('portal_id')));

        static::creating(function ($model) {
            $model->portal_id = session('portal_id');
        });
    }

    public function portal(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Portal::class);
    }

    public function client(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function getFilePathAttribute() {
        return 'deliveryNotes/' . $this->portal_id . '/' . $this->number . '.pdf';
    }
}
