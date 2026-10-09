<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add role & nis_nip to users table if not exists
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'role')) {
                $table->string('role')->default('murid'); // 'guru' or 'murid'
            }
            if (! Schema::hasColumn('users', 'nis_nip')) {
                $table->string('nis_nip')->nullable();
            }
        });

        // Classrooms / Mata Pelajaran
        Schema::create('classrooms', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->foreignId('teacher_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });

        // Learning Materials / Materi Belajar
        Schema::create('materials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('classroom_id')->constrained('classrooms')->cascadeOnDelete();
            $table->foreignId('teacher_id')->constrained('users')->cascadeOnDelete();
            $table->string('title');
            $table->string('topic');
            $table->longText('content');
            $table->timestamps();
        });

        // Assignments / Tugas
        Schema::create('assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('classroom_id')->constrained('classrooms')->cascadeOnDelete();
            $table->foreignId('teacher_id')->constrained('users')->cascadeOnDelete();
            $table->string('title');
            $table->string('topic')->nullable();
            $table->longText('material_source')->nullable(); // Materi yang dijadikan acuan AI
            $table->text('instructions')->nullable();
            $table->longText('questions_content'); // Soal & opsi/rubrik (bisa AI-generated)
            $table->longText('answer_key')->nullable(); // Kunci jawaban & pembahasan untuk guru
            $table->date('due_date')->nullable();
            $table->string('status')->default('published'); // 'published', 'draft'
            $table->boolean('ai_generated')->default(false);
            $table->timestamps();
        });

        // Submissions / Pengumpulan Tugas Murid
        Schema::create('submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assignment_id')->constrained('assignments')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('users')->cascadeOnDelete();
            $table->longText('student_answers');
            $table->integer('score')->nullable();
            $table->text('feedback')->nullable();
            $table->string('status')->default('submitted'); // 'submitted', 'graded'
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();
        });

        // Attendances / Absensi Siswa
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('classroom_id')->constrained('classrooms')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('users')->cascadeOnDelete();
            $table->date('date');
            $table->enum('status', ['hadir', 'izin', 'sakit', 'alpha'])->default('hadir');
            $table->string('notes')->nullable();
            $table->timestamps();

            $table->unique(['classroom_id', 'student_id', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
        Schema::dropIfExists('submissions');
        Schema::dropIfExists('assignments');
        Schema::dropIfExists('materials');
        Schema::dropIfExists('classrooms');

        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'role')) {
                $table->dropColumn('role');
            }
            if (Schema::hasColumn('users', 'nis_nip')) {
                $table->dropColumn('nis_nip');
            }
        });
    }
};
