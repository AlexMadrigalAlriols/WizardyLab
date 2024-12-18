<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Helpers\AttendanceHelper;
use App\Models\Scopes\PortalScope;
use App\Models\Traits\HasAttendance;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable, HasAttendance, HasRoles;

    public const GENDERS = [
        'female' => 'female',
        'male' => 'male',
        'other' => 'other',
    ];

    public static $filterable = [
        'name',
        'email',
        'code',
        'gender',
        'department.name',
        'roles.name',
        'country.name',
        'reportingUser.name',
        'projects.name',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'portal_id',
        'name',
        'email',
        'password',
        'code',
        'profile_img',
        'gender',
        'last_login_at',
        'joining_date',
        'birthday_date',
        'reporting_user_id',
        'department_id',
        'country_id',
        'attendance_template_id',
        'portal_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'last_login_at' => 'datetime',
        'joining_date' => 'datetime',
        'birthday_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected static function booted()
    {
        static::addGlobalScope(new PortalScope(session('portal_id')));

        static::creating(function ($model) {
            $model->portal_id = session('portal_id');
        });
    }

    // Implementa los métodos requeridos por JWTSubject
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function department(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function roles(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'model_has_roles', 'model_id', 'role_id');
    }

    public function country(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function reportingUser(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'reporting_user_id');
    }

    public function attendances(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    public function tasks(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Task::class, 'users_tasks');
    }

    public function projects(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Project::class, 'users_projects');
    }

    public function taskAttendance(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(TaskAttendance::class);
    }

    public function activeTaskTimers(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(TaskAttendance::class)->whereNull('check_out');
    }

    public function notifications(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Notification::class);
    }

    public function portal(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Portal::class);
    }

    public function UserInventories(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(UserInventory::class);
    }

    public function attendanceTemplate(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(AttendanceTemplate::class);
    }

    public function leaveDays(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Leave::class);
    }
   // [TODO] Change leaveDays or leaves
    public function leaves(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Leave::class);
    }

    public function getRoleAttribute()
    {
        return $this->belongsToMany(Role::class, 'model_has_roles', 'model_id', 'role_id')->first();
    }

    public function getProfileUrlAttribute()
    {
        if(!$this->profile_img) {
            return asset('img/default-user.png');
        }

        if($this->profile_img && filter_var($this->profile_img, FILTER_VALIDATE_URL)) {
            return $this->profile_img;
        }

        return asset('storage/' . $this->profile_img) ?? null;
    }

}
