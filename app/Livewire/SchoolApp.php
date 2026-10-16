<?php

namespace App\Livewire;

use App\Models\Assignment;
use App\Models\Attendance;
use App\Models\Classroom;
use App\Models\Material;
use App\Models\Submission;
use App\Models\User;
use App\Services\GroqAiService;
use Carbon\Carbon;
use Livewire\Component;

class SchoolApp extends Component
{
    // Active User & Role
    public int $currentUserId = 0; // Diisi dari user yang sedang login (auth)

    public string $activeTab = 'dashboard'; // 'dashboard', 'materi', 'tugas', 'absensi', 'ai_generator'

    public ?int $selectedClassroomId = null;

    // Materi State
    public ?int $activeMaterialId = null;

    public string $newMaterialTitle = '';

    public string $newMaterialTopic = '';

    public string $newMaterialContent = '';

    public ?int $newMaterialClassroomId = null;

    public bool $showMaterialModal = false;

    // AI Task Generator State (Guru)
    public string $aiTopic = 'Pemrograman Web Modern';

    public string $aiMaterial = '';

    public int $aiQuestionCount = 3;

    public string $aiQuestionType = 'campuran'; // 'pilihan ganda', 'esai', 'campuran'

    public string $aiDifficulty = 'sedang';

    public ?int $aiTargetClassroomId = null;

    public string $aiDueDate = '';

    public string $aiGeneratedQuestions = '';

    public string $aiGeneratedAnswerKey = '';

    public bool $isAiGenerating = false;

    public string $aiGenerateStatus = '';

    // Tugas & Pengerjaan (Murid)
    public ?int $activeAssignmentId = null;

    public string $studentAnswerInput = '';

    public bool $showAssignmentModal = false;

    // Guru: Edit Tugas
    public ?int $editingAssignmentId = null;

    public string $editTitle = '';

    public string $editTopic = '';

    public ?int $editClassroomId = null;

    public string $editInstructions = '';

    public string $editQuestionsContent = '';

    public string $editAnswerKey = '';

    public string $editDueDate = '';

    public bool $showEditAssignmentModal = false;

    // Guru: Penilaian Submission
    public ?int $activeSubmissionId = null;

    public ?int $gradeScore = null;

    public string $gradeFeedback = '';

    public bool $showGradingModal = false;

    // Absensi State (Murid)
    public string $attendanceStatus = 'hadir';

    public string $attendanceNotes = '';

    public string $attendanceDate = '';

    // AI Chatbot State
    public bool $isChatOpen = false;

    public array $chatMessages = [];

    public string $chatInput = '';

    public ?string $chatContext = null;

    public string $chatContextTitle = 'Umum';

    // Alerts / Flash Notifications
    public string $flashMessage = '';

    public string $flashType = 'success'; // 'success', 'error', 'info'

    public function mount()
    {
        if (auth()->check()) {
            $this->currentUserId = auth()->id();
        }

        $firstClass = Classroom::first();
        if ($firstClass) {
            $this->selectedClassroomId = $firstClass->id;
            $this->newMaterialClassroomId = $firstClass->id;
            $this->aiTargetClassroomId = $firstClass->id;
        }

        $this->attendanceDate = Carbon::today()->toDateString();
        $this->aiDueDate = Carbon::today()->addDays(5)->toDateString();

        // Inisialisasi pesan sambutan chatbot
        $this->chatMessages = [
            [
                'role' => 'assistant',
                'content' => "Halo! Saya **EduBot AI**, asisten belajar dan asisten guru Anda.\n\n- **Murid:** Tanyakan maksud soal, minta petunjuk konsep, atau pembahasan materi jika mengalami kesulitan.\n- **Guru:** Anda dapat meminta bantuan penyusunan ide tugas, materi, atau analisis soal secara otomatis.\n\nAda yang ingin didiskusikan hari ini?",
            ],
        ];
    }

    /**
     * User yang sedang login. Peran diambil dari Spatie permission.
     */
    protected function actingUser(): ?User
    {
        return auth()->user() ?? User::find($this->currentUserId);
    }

    protected function isGuru(): bool
    {
        return (bool) $this->actingUser()?->isGuru();
    }

    /**
     * Penjaga otorisasi khusus guru berbasis Spatie role.
     */
    protected function authorizeGuru(): bool
    {
        if ($this->isGuru()) {
            return true;
        }

        $this->notify('Anda tidak memiliki akses untuk fitur ini (khusus Guru).', 'error');

        return false;
    }

    protected function authorizeMurid(): bool
    {
        if ($this->actingUser()?->isMurid()) {
            return true;
        }

        $this->notify('Anda tidak memiliki akses untuk fitur ini (khusus Siswa).', 'error');

        return false;
    }

    public function setTab(string $tab)
    {
        if ($tab === 'ai_generator' && ! $this->isGuru()) {
            $this->notify('Generator Soal AI hanya tersedia untuk akun Guru.', 'error');

            return;
        }

        $this->activeTab = $tab;
    }

    public function notify(string $message, string $type = 'success')
    {
        $this->flashMessage = $message;
        $this->flashType = $type;
    }

    public function clearNotification()
    {
        $this->flashMessage = '';
    }

    // ==================== MATERI ====================

    public function openMaterial(int $materialId)
    {
        $this->activeMaterialId = $materialId;
        $material = Material::find($materialId);
        if ($material) {
            $this->chatContextTitle = 'Materi: '.$material->title;
            $this->chatContext = 'Judul Materi: '.$material->title."\nTopik: ".$material->topic."\nIsi:\n".$material->content;
        }
    }

    public function closeMaterial()
    {
        $this->activeMaterialId = null;
    }

    public function saveNewMaterial()
    {
        if (! $this->authorizeGuru()) {
            return;
        }

        if (empty(trim($this->newMaterialTitle)) || empty(trim($this->newMaterialContent))) {
            $this->notify('Judul dan isi materi wajib diisi.', 'error');

            return;
        }

        $classId = $this->newMaterialClassroomId ?: $this->selectedClassroomId;

        Material::create([
            'classroom_id' => $classId,
            'teacher_id' => $this->currentUserId,
            'title' => $this->newMaterialTitle,
            'topic' => $this->newMaterialTopic ?: 'Umum',
            'content' => $this->newMaterialContent,
        ]);

        $this->newMaterialTitle = '';
        $this->newMaterialTopic = '';
        $this->newMaterialContent = '';
        $this->showMaterialModal = false;
        $this->notify('Materi pembelajaran berhasil ditambahkan!', 'success');
    }

    public function prepareAiFromMaterial(int $materialId)
    {
        if (! $this->isGuru()) {
            $this->notify('Hanya guru yang dapat membuat tugas dari materi.', 'error');

            return;
        }

        $mat = Material::find($materialId);
        if ($mat) {
            $this->aiTopic = $mat->topic ?: $mat->title;
            $this->aiMaterial = $mat->content;
            $this->aiTargetClassroomId = $mat->classroom_id;
            $this->activeTab = 'ai_generator';
            $this->notify("Materi '{$mat->title}' dimuat ke AI Generator. Silakan pilih opsi dan generate!", 'info');
        }
    }

    // ==================== AI TASK GENERATOR (GURU) ====================

    public function generateAndPublishAssignment(GroqAiService $aiService, bool $autoPublish = true)
    {
        if (! $this->authorizeGuru()) {
            return;
        }

        if (empty(trim($this->aiMaterial))) {
            $this->notify('Materi pelajaran acuan tidak boleh kosong!', 'error');

            return;
        }

        $this->isAiGenerating = true;
        $this->aiGenerateStatus = 'AI sedang membaca materi dan menyusun soal...';

        $result = $aiService->generateAssignment(
            material: $this->aiMaterial,
            topic: $this->aiTopic,
            questionCount: $this->aiQuestionCount,
            type: $this->aiQuestionType,
            difficulty: $this->aiDifficulty
        );

        $this->isAiGenerating = false;

        if (! $result['success']) {
            $this->notify('Gagal generate tugas AI: '.($result['error'] ?? 'Terjadi kesalahan'), 'error');

            return;
        }

        $this->aiGeneratedQuestions = $result['questions'];
        $this->aiGeneratedAnswerKey = $result['answer_key'];

        if ($autoPublish) {
            $classId = $this->aiTargetClassroomId ?: $this->selectedClassroomId;
            $class = Classroom::find($classId);

            $assignment = Assignment::create([
                'classroom_id' => $classId,
                'teacher_id' => $this->currentUserId,
                'title' => 'Tugas AI: '.($this->aiTopic ?: 'Latihan Pemahaman'),
                'topic' => $this->aiTopic,
                'material_source' => $this->aiMaterial,
                'instructions' => 'Pelajari materi yang diberikan dan kerjakan butir soal di bawah ini. Anda dapat berkonsultasi dengan EduBot AI jika butuh petunjuk atau penjelasan konsep.',
                'questions_content' => $this->aiGeneratedQuestions,
                'answer_key' => $this->aiGeneratedAnswerKey,
                'due_date' => $this->aiDueDate ?: Carbon::today()->addDays(5)->toDateString(),
                'status' => 'published',
                'ai_generated' => true,
            ]);

            $this->activeTab = 'tugas';
            $this->notify("Soal tugas berhasil disusun oleh AI dan langsung diterbitkan ke kelas {$class?->name}.", 'success');
        } else {
            $this->notify('Soal berhasil digenerate oleh AI! Silakan periksa pratinjau sebelum diterbitkan.', 'info');
        }
    }

    public function publishExistingGenerated()
    {
        if (! $this->authorizeGuru()) {
            return;
        }

        if (empty(trim($this->aiGeneratedQuestions))) {
            $this->notify('Belum ada soal yang di-generate!', 'error');

            return;
        }

        $classId = $this->aiTargetClassroomId ?: $this->selectedClassroomId;
        $class = Classroom::find($classId);

        Assignment::create([
            'classroom_id' => $classId,
            'teacher_id' => $this->currentUserId,
            'title' => 'Tugas AI: '.($this->aiTopic ?: 'Latihan Pemahaman'),
            'topic' => $this->aiTopic,
            'material_source' => $this->aiMaterial,
            'instructions' => 'Kerjakan tugas berikut dengan seksama.',
            'questions_content' => $this->aiGeneratedQuestions,
            'answer_key' => $this->aiGeneratedAnswerKey,
            'due_date' => $this->aiDueDate ?: Carbon::today()->addDays(5)->toDateString(),
            'status' => 'published',
            'ai_generated' => true,
        ]);

        $this->activeTab = 'tugas';
        $this->notify("Tugas berhasil diterbitkan untuk murid di {$class?->name}!", 'success');
    }

    // ==================== TUGAS & PENGERJAAN (MURID) ====================

    public function startAssignment(int $assignmentId)
    {
        $this->activeAssignmentId = $assignmentId;
        $this->showAssignmentModal = true;

        $assignment = Assignment::find($assignmentId);
        $submission = Submission::where('assignment_id', $assignmentId)
            ->where('student_id', $this->currentUserId)
            ->first();

        $this->studentAnswerInput = $submission ? $submission->student_answers : '';

        if ($assignment) {
            // Set context for AI Chatbot
            $this->chatContextTitle = 'Tugas: '.$assignment->title;
            $this->chatContext = 'Judul Tugas: '.$assignment->title."\nPetunjuk:\n".$assignment->instructions."\n\nSOAL TUGAS:\n".$assignment->questions_content;
        }
    }

    public function closeAssignmentModal()
    {
        $this->showAssignmentModal = false;
        $this->activeAssignmentId = null;
    }

    public function submitAssignment()
    {
        if (! $this->authorizeMurid()) {
            return;
        }

        if (empty(trim($this->studentAnswerInput))) {
            $this->notify('Jawaban tidak boleh kosong!', 'error');

            return;
        }

        $submission = Submission::updateOrCreate(
            [
                'assignment_id' => $this->activeAssignmentId,
                'student_id' => $this->currentUserId,
            ],
            [
                'student_answers' => $this->studentAnswerInput,
                'status' => 'submitted',
                'submitted_at' => Carbon::now(),
            ]
        );

        $this->showAssignmentModal = false;
        $this->notify('Tugas berhasil dikirim ke guru. Anda dapat melihat status pengumpulan pada daftar tugas.', 'success');
    }

    /**
     * Hapus tugas beserta jawaban siswa yang terkumpul (khusus guru).
     */
    public function deleteAssignment(int $assignmentId)
    {
        if (! $this->authorizeGuru()) {
            return;
        }

        $assignment = Assignment::find($assignmentId);

        if (! $assignment) {
            $this->notify('Tugas tidak ditemukan.', 'error');

            return;
        }

        $assignment->delete();

        if ($this->activeAssignmentId === $assignmentId) {
            $this->showAssignmentModal = false;
            $this->activeAssignmentId = null;
        }

        if ($this->editingAssignmentId === $assignmentId) {
            $this->showEditAssignmentModal = false;
            $this->editingAssignmentId = null;
        }

        $this->notify('Tugas berhasil dihapus.', 'success');
    }

    /**
     * Buka form edit tugas (khusus guru).
     */
    public function editAssignment(int $assignmentId)
    {
        if (! $this->authorizeGuru()) {
            return;
        }

        $assignment = Assignment::find($assignmentId);

        if (! $assignment) {
            $this->notify('Tugas tidak ditemukan.', 'error');

            return;
        }

        $this->editingAssignmentId = $assignment->id;
        $this->editTitle = $assignment->title;
        $this->editTopic = $assignment->topic ?? '';
        $this->editClassroomId = $assignment->classroom_id;
        $this->editInstructions = $assignment->instructions ?? '';
        $this->editQuestionsContent = $assignment->questions_content ?? '';
        $this->editAnswerKey = $assignment->answer_key ?? '';
        $this->editDueDate = $assignment->due_date ? $assignment->due_date->toDateString() : '';
        $this->showEditAssignmentModal = true;
    }

    public function updateAssignment()
    {
        if (! $this->authorizeGuru()) {
            return;
        }

        $validated = $this->validate([
            'editTitle' => ['required', 'string', 'max:255'],
            'editTopic' => ['nullable', 'string', 'max:255'],
            'editClassroomId' => ['required', 'exists:classrooms,id'],
            'editInstructions' => ['nullable', 'string'],
            'editQuestionsContent' => ['required', 'string'],
            'editAnswerKey' => ['nullable', 'string'],
            'editDueDate' => ['nullable', 'date'],
        ]);

        $assignment = Assignment::find($this->editingAssignmentId);

        if (! $assignment) {
            $this->notify('Tugas tidak ditemukan.', 'error');

            return;
        }

        $assignment->update([
            'classroom_id' => $validated['editClassroomId'],
            'title' => $validated['editTitle'],
            'topic' => $validated['editTopic'] ?: 'Umum',
            'instructions' => $validated['editInstructions'],
            'questions_content' => $validated['editQuestionsContent'],
            'answer_key' => $validated['editAnswerKey'],
            'due_date' => $validated['editDueDate'] ?: null,
        ]);

        $this->showEditAssignmentModal = false;
        $this->editingAssignmentId = null;

        $this->notify("Tugas '{$assignment->title}' berhasil diperbarui.", 'success');
    }

    public function closeEditAssignmentModal()
    {
        $this->showEditAssignmentModal = false;
        $this->editingAssignmentId = null;
    }

    // ==================== PENILAIAN GURU ====================

    public function openGrading(int $submissionId)
    {
        $sub = Submission::with(['student', 'assignment'])->find($submissionId);
        if ($sub) {
            $this->activeSubmissionId = $sub->id;
            $this->gradeScore = $sub->score;
            $this->gradeFeedback = $sub->feedback ?? '';
            $this->showGradingModal = true;
        }
    }

    public function saveGrading()
    {
        if (! $this->authorizeGuru()) {
            return;
        }

        if ($this->gradeScore === null || $this->gradeScore < 0 || $this->gradeScore > 100) {
            $this->notify('Nilai harus berupa angka antara 0 hingga 100!', 'error');

            return;
        }

        $sub = Submission::find($this->activeSubmissionId);
        if ($sub) {
            $sub->update([
                'score' => $this->gradeScore,
                'feedback' => $this->gradeFeedback,
                'status' => 'graded',
            ]);

            $this->showGradingModal = false;
            $this->notify("Nilai ({$this->gradeScore}) dan catatan feedback untuk {$sub->student->name} berhasil disimpan!", 'success');
        }
    }

    // ==================== ABSENSI KELAS ====================

    public function submitAttendance()
    {
        if (! $this->authorizeMurid()) {
            return;
        }

        $classId = $this->selectedClassroomId ?: Classroom::first()?->id;
        $today = Carbon::today()->toDateString();

        $att = Attendance::where('classroom_id', $classId)
            ->where('student_id', $this->currentUserId)
            ->whereDate('date', $today)
            ->first();

        if ($att) {
            $att->update([
                'status' => $this->attendanceStatus,
                'notes' => $this->attendanceNotes ?: 'Hadir melalui sistem e-Learning',
            ]);
        } else {
            Attendance::create([
                'classroom_id' => $classId,
                'student_id' => $this->currentUserId,
                'date' => $today,
                'status' => $this->attendanceStatus,
                'notes' => $this->attendanceNotes ?: 'Hadir melalui sistem e-Learning',
            ]);
        }

        $this->notify("Presensi kehadiran tanggal {$today} berhasil dicatat dengan status: ".strtoupper($this->attendanceStatus), 'success');
    }

    public function updateStudentAttendanceByTeacher(int $attendanceId, string $newStatus)
    {
        if (! $this->authorizeGuru()) {
            return;
        }

        $att = Attendance::find($attendanceId);
        if ($att) {
            $att->update(['status' => $newStatus]);
            $this->notify("Status absensi {$att->student->name} diperbarui menjadi: ".strtoupper($newStatus), 'info');
        }
    }

    // ==================== AI CHATBOT TUTOR ====================

    public function toggleChat()
    {
        $this->isChatOpen = ! $this->isChatOpen;
    }

    public function openChatWithContext(?string $title = null, ?string $context = null)
    {
        $this->isChatOpen = true;
        if ($title) {
            $this->chatContextTitle = $title;
        }
        if ($context) {
            $this->chatContext = $context;
        }

        // Berikan pesan saran pembuka
        $this->chatMessages[] = [
            'role' => 'assistant',
            'content' => "Saya siap membantu Anda membahas **{$this->chatContextTitle}**! Silakan tanyakan bagian mana yang belum Anda mengerti, minta petunjuk pengerjaan, atau analisis jawaban.",
        ];
    }

    public function askQuickQuestion(string $preset)
    {
        $this->chatInput = $preset;
        $this->sendChatMessage();
    }

    public function sendChatMessage(?GroqAiService $aiService = null)
    {
        if (empty(trim($this->chatInput))) {
            return;
        }

        if (! $aiService) {
            $aiService = app(GroqAiService::class);
        }

        $userText = $this->chatInput;
        $this->chatInput = '';

        $this->chatMessages[] = [
            'role' => 'user',
            'content' => $userText,
        ];

        // Dapatkan konteks
        $context = $this->chatContext;
        if (! $context && $this->activeAssignmentId) {
            $assign = Assignment::find($this->activeAssignmentId);
            if ($assign) {
                $context = "Soal Tugas:\n".$assign->questions_content;
            }
        }

        // Panggil Groq AI Tutor
        $reply = $aiService->chatWithAiTutor($this->chatMessages, $context);

        $this->chatMessages[] = [
            'role' => 'assistant',
            'content' => $reply,
        ];
    }

    public function clearChatHistory()
    {
        $this->chatMessages = [
            [
                'role' => 'assistant',
                'content' => 'Riwayat percakapan telah dibersihkan. Ada yang ingin kamu diskusikan lagi seputar materi atau tugas sekolah?',
            ],
        ];
    }

    public function render()
    {
        $currentUser = $this->actingUser() ?? User::first();
        $classrooms = Classroom::all();
        $activeClassroom = Classroom::with(['teacher'])->find($this->selectedClassroomId) ?? $classrooms->first();

        // Data Materials
        $materials = Material::when($this->selectedClassroomId, function ($q) {
            $q->where('classroom_id', $this->selectedClassroomId);
        })->latest()->get();

        $activeMaterial = $this->activeMaterialId ? Material::find($this->activeMaterialId) : null;

        // Data Assignments
        $assignments = Assignment::with(['submissions', 'teacher', 'classroom'])
            ->when($this->selectedClassroomId, function ($q) {
                $q->where('classroom_id', $this->selectedClassroomId);
            })->latest()->get();

        $activeAssignment = $this->activeAssignmentId ? Assignment::with(['submissions.student', 'classroom'])->find($this->activeAssignmentId) : null;

        // Data Attendances
        $today = Carbon::today()->toDateString();
        $classStudents = User::where('role', 'murid')->get();
        $todayAttendances = Attendance::with('student')
            ->where('classroom_id', $this->selectedClassroomId)
            ->where('date', $this->attendanceDate)
            ->get();

        $myAttendanceToday = Attendance::where('classroom_id', $this->selectedClassroomId)
            ->where('student_id', $this->currentUserId)
            ->where('date', $today)
            ->first();

        $myAttendanceHistory = Attendance::where('student_id', $this->currentUserId)
            ->latest('date')
            ->take(10)
            ->get();

        return view('livewire.school-app', [
            'currentUser' => $currentUser,
            'classrooms' => $classrooms,
            'activeClassroom' => $activeClassroom,
            'materials' => $materials,
            'activeMaterial' => $activeMaterial,
            'assignments' => $assignments,
            'activeAssignment' => $activeAssignment,
            'classStudents' => $classStudents,
            'todayAttendances' => $todayAttendances,
            'myAttendanceToday' => $myAttendanceToday,
            'myAttendanceHistory' => $myAttendanceHistory,
        ]);
    }
}
