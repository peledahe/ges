<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'tenant_id',
        'role',
        'is_active',
        'is_demo',
        'demo_password',
        'area_id',
        'can_view_contact_info',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'is_demo' => 'boolean',
            'can_view_contact_info' => 'boolean',
        ];
    }

    public function isDemo(): bool
    {
        return (bool) ($this->is_demo ?? false);
    }

    public function isDemoTenant(): bool
    {
        return $this->tenant?->isDemo() ?? false;
    }

    public function isActive(): bool
    {
        return (bool) ($this->is_active ?? true);
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    public function hasRole($role)
    {
        return $this->role === $role;
    }

    public function isRoot(): bool
    {
        return $this->role === 'root';
    }

    public function isAdmin(): bool
    {
        return in_array($this->role, ['admin', 'root']);
    }

    public function areas()
    {
        return $this->belongsToMany(Area::class);
    }

    /**
     * Determina si el usuario tiene permiso para ver y gestionar una determinada área.
     * Soporta talleres unipersonales (acceso total automático) o talleres con múltiples áreas y usuarios.
     */
    public function canAccessArea(int|string $areaId): bool
    {
        if ($this->isAdmin()) {
            return true;
        }

        // Si tiene áreas asignadas en la relación muchos-a-muchos
        if ($this->areas()->exists()) {
            return $this->areas()->where('areas.id', $areaId)->exists();
        }

        // Si tiene un área única asignada en area_id
        if ($this->area_id) {
            return (int) $this->area_id === (int) $areaId;
        }

        // Si es el único usuario del taller o no tiene áreas restrictivas configuradas
        return true;
    }

    /**
     * Retorna los IDs de las áreas a las que tiene acceso el usuario, o null si tiene acceso a todas.
     */
    public function accessibleAreaIds(): ?array
    {
        if ($this->isAdmin()) {
            return null; // Acceso a todas
        }

        $assignedIds = $this->areas()->pluck('areas.id')->toArray();
        if (!empty($assignedIds)) {
            return $assignedIds;
        }

        if ($this->area_id) {
            return [$this->area_id];
        }

        return null; // Sin restricción
    }
}

