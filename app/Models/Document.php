<?php

namespace App\Models;

use App\Models\Scopes\PortalScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'document_folder_id',
        'user_id',
        'title',
        'path',
        'size',
        'mime_type',
        'user_upload_id',
        'data'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'data' => 'array'
    ];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function folder(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(DocumentFolder::class, 'document_folder_id');
    }

    public function userUpload(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'user_upload_id');
    }

    public function getNeedSignedAttribute(): bool
    {
        return $this->data['needSigned'] ?? false;
    }

    public function getSignedAttribute(): bool
    {
        return $this->data['signed'] ?? false;
    }

    public function getUserSignedAttribute(): ?User
    {
        if($this->data['signed_user_id'] ?? false)
        {
            return User::find($this->data['signed_user_id']);
        }

        return null;
    }

    public function getIconAttribute() {
        return match($this->mime_type) {
           'image/jpeg', 'image/png', 'image/gif' => 'bx bx-image',
            'application/pdf' => 'bx bxs-file-pdf',
            'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'bx bxs-file-doc',
            'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'bx bx-file-doc',
            'application/vnd.ms-powerpoint', 'application/vnd.openxmlformats-officedocument.presentationml.presentation' => 'bx bxs-file-doc',
            default => 'bx bx-file'
        };
    }
}
