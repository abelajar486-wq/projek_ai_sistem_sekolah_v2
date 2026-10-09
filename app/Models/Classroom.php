<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Classroom extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'description',
        'teacher_id',
    ];

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function materials(): HasMany
    {
        return $this->hasMany(Material::class)->latest();
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(Assignment::class)->latest();
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class)->latest('date');
    }
}
