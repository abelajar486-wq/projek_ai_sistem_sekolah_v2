<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, HasRoles, Notifiable;

    /**
     * Peran sekolah yang tersedia: kolom legacy `role` <-> nama Spatie role.
     */
    public const ROLES = [
        'guru' => 'Guru',
        'murid' => 'Siswa',
        'admin' => 'Administrator',
    ];

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'nis_nip',
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
        ];
    }

    public function isGuru(): bool
    {
        return $this->role === 'guru' || $this->hasRole('guru');
    }

    public function isMurid(): bool
    {
        return $this->role === 'murid' || $this->hasRole('murid');
    }

    /**
     * Tetapkan peran sekolah: sinkronkan kolom legacy `role` dengan Spatie role.
     */
    public function assignSchoolRole(string $role): void
    {
        if (! array_key_exists($role, self::ROLES)) {
            $role = 'murid';
        }

        $this->forceFill(['role' => $role])->save();
        $this->syncRoles([$role]);
    }

    public function taughtClasses(): HasMany
    {
        return $this->hasMany(Classroom::class, 'teacher_id');
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(Submission::class, 'student_id');
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class, 'student_id');
    }
}
