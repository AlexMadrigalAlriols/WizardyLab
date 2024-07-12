<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Models\Scopes\PortalScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;;

class Status extends Model
{
    use HasFactory;


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'data',
        'morphable',
        'portal_id'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'data' => 'json'
    ];

    protected static function booted()
    {
        static::addGlobalScope(new PortalScope(session('portal_id')));

        static::creating(function ($model) {
            $model->portal_id = session('portal_id');
        });
    }

    public function tasks(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Task::class);
    }

    public function projects(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Project::class);
    }

    public function getBadgeAttribute(): string
    {
        return match ($this->data['background'] ?? 'default') {
            'primary' => 'bg-primary text-white',
            'warning' => 'bg-warning',
            'success' => 'bg-success',
            'danger' => 'bg-danger text-white',
            'secondary' => 'bg-secondary text-white',
            default => 'bg-light text-dark'
        };
    }

    public function getStylesAttribute() {
        $styles = [
            'background-color' => $this->data['background'] ?? '#fff',
            'color' => $this->data['color'] ?? '#000',
        ];

        $string = '';
        foreach ($styles as $key => $style) {
            $string .= $key . ': ' . $style . ';';
        }

        return $string;
    }
}
