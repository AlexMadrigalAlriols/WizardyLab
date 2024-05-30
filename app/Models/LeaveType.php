<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;;

class LeaveType extends Model
{
    use HasFactory;
    public const PAGE_SIZE = 10;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'max_days',
        'data'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'data' => 'array'
    ];

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
