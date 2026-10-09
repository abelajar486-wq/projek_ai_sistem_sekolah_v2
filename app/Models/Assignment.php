<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Assignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'classroom_id',
        'teacher_id',
        'title',
        'topic',
        'material_source',
        'instructions',
        'questions_content',
        'answer_key',
        'due_date',
        'status',
        'ai_generated',
    ];

    protected function casts(): array
    {
        return [
            'due_date' => 'date',
            'ai_generated' => 'boolean',
        ];
    }

    public function classroom(): BelongsTo
    {
        return $this->belongsTo(Classroom::class);
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(Submission::class);
    }

    public function studentSubmission(int $studentId)
    {
        return $this->submissions()->where('student_id', $studentId)->first();
    }
}
