<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;;

class Status extends Model
{
    use HasFactory;
    public const PAGE_SIZE = 10;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'data',
        'morphable'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'data' => 'json'
    ];

    public function tasks(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Task::class);
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
