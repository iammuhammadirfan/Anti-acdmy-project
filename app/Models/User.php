<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'avatar',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    public function sectionPermissions(): HasMany
    {
        return $this->hasMany(UserSectionPermission::class);
    }

    public function blogs(): HasMany
    {
        return $this->hasMany(Blog::class, 'author_id');
    }

    public function activityLogs(): HasMany
    {
        return $this->hasMany(ActivityLog::class);
    }

    public function isSuperAdmin(): bool
    {
        return $this->roles()->where('slug', 'super-admin')->exists();
    }

    public function hasRole(string|array $roles): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        $roles = (array) $roles;
        return $this->roles()->whereIn('slug', $roles)->exists();
    }

    public function hasPermission(string $module, string $action = 'view'): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        // First check custom section permissions assigned directly to this user
        $sectionPerm = $this->sectionPermissions()->where('module', $module)->first();
        if ($sectionPerm) {
            $field = 'can_' . $action;
            if (isset($sectionPerm->{$field})) {
                return (bool) $sectionPerm->{$field};
            }
        }

        // Fallback to role permissions
        return $this->roles()->whereHas('permissions', function ($q) use ($module, $action) {
            $q->where('module', $module)->where('action', $action);
        })->exists();
    }

    public function canAccessSection(string $module, string $action = 'view'): bool
    {
        return $this->hasPermission($module, $action);
    }
}
