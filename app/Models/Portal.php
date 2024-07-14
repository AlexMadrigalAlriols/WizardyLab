<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Portal extends Model
{
    use HasFactory, SoftDeletes;

    public const SUBSCRIPTION_TYPES = [
        'basic' => 'Basic',
        'pro' => 'Pro',
        'enterprise' => 'Enterprise'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'subdomain',
        'name',
        'active',
        'data',
        'subscription_type'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
        'data' => 'array'
    ];

    public function users(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(User::class);
    }

    public function folders(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(DocumentFolder::class);
    }

    public function getLogoAttribute()
    {
        if($this->data['logo'] && filter_var($this->data['logo'], FILTER_VALIDATE_URL)) {
            return $this->data['logo'];
        }

        return asset('storage/' . $this->data['logo']) ?? null;
    }

    public function getPercentageUsedAttribute()
    {
        if($this->storage_size == 0) {
            return 100;
        }

        $storage_size = $this->storage_size;
        $byteSize = $this->getTotalFolderSize();
        $currentSizeGB = $byteSize / (1024 * 1024 * 1024);

        if($currentSizeGB >= $storage_size) {
            return 100;
        }

        return round(($currentSizeGB / $storage_size) * 100, 2);
    }

    public function getStorageUsedAttribute()
    {
        $byteSize = $this->getTotalFolderSize();

        return round($byteSize / 1024 / 1024 / 1024, 3);
    }

    public function getRemainingStorageAttribute()
    {
        $byteSize = $this->getTotalFolderSize();
        $storage_size = $this->storage_size;

        return ($storage_size * 1024 * 1024 * 1024 - $byteSize);
    }

    public function getSecondaryLightAttribute()
    {
        $hexColor = $this->data['secondary_color']; // Aquí iría tu color dinámico
        $lightenPercent = 0.1; // Ajusta el porcentaje para hacer el color más claro

        return $this->lightenColor($hexColor, $lightenPercent);
    }

    public function getPrimaryLightAttribute()
    {
        $hexColor = $this->data['primary_color']; // Aquí iría tu color dinámico
        $lightenPercent = 0.4; // Ajusta el porcentaje para hacer el color más claro

        return $this->lightenColor($hexColor, $lightenPercent);
    }

    private function hexToRgb($hex) {
        $hex = ltrim($hex, '#');

        if (strlen($hex) == 3) {
            $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
        }

        $r = hexdec($hex[0] . $hex[1]);
        $g = hexdec($hex[2] . $hex[3]);
        $b = hexdec($hex[4] . $hex[5]);

        return [$r, $g, $b];
    }

    private function lightenColor($hex, $percent) {
        list($r, $g, $b) = $this->hexToRgb($hex);

        $r = min(255, round($r + (255 - $r) * $percent));
        $g = min(255, round($g + (255 - $g) * $percent));
        $b = min(255, round($b + (255 - $b) * $percent));

        return sprintf("rgba(%d, %d, %d, 1)", $r, $g, $b);
    }

    private function getTotalFolderSize(): int
    {
        $byteSize = 0;
        foreach($this->folders as $folder) {
            $byteSize += $folder->documents()->sum('size');
        }

        return $byteSize;
    }

    public function getUserCountAttribute()
    {
        return match($this->subscription_type) {
            'Basic' => 15,
            'Pro' => 40,
            'Enterprise' => 1000,
            default => 15
        };
    }
}
