<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Models\Scopes\PortalScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;;

class LeaveType extends Model
{
    use HasFactory;


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'max_days',
        'data',
        'portal_id'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'data' => 'array'
    ];

    protected static function booted()
    {
        static::addGlobalScope(new PortalScope(session('portal_id')));

        static::creating(function ($model) {
            $model->portal_id = session('portal_id');
        });
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
