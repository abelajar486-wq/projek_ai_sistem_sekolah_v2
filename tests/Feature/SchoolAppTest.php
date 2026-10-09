<?php

use App\Livewire\SchoolApp;
use App\Models\Assignment;
use App\Models\Attendance;
use App\Models\Classroom;
use App\Models\Material;
use App\Models\Submission;
use App\Models\User;
use App\Services\GroqAiService;
use Carbon\Carbon;
use Livewire\Livewire;

beforeEach(function () {
    $this->guru = User::factory()->create(['role' => 'guru']);
    $this->guru->assignSchoolRole('guru');

    $this->murid = User::factory()->create(['role' => 'murid']);
    $this->murid->assignSchoolRole('murid');

    $this->classroom = Classroom::create([
        'name' => 'RPL 11A',
        'code' => 'RPL11A',
        'description' => 'Kelas uji coba',
        'teacher_id' => $this->guru->id,
    ]);
});

test('guest cannot open the school app', function () {
    $this->get('/')->assertRedirect('/login');
});

test('authenticated user sees the school app', function () {
    $response = $this->actingAs($this->murid)->get('/');

    $response->assertOk()->assertSee('EduSmart');
});

test('school app component loads and can switch tabs', function () {
    $this->actingAs($this->murid);

    Livewire::test(SchoolApp::class)
        ->assertSet('activeTab', 'dashboard')
        ->call('setTab', 'materi')
        ->assertSet('activeTab', 'materi')
        ->call('setTab', 'tugas')
        ->assertSet('activeTab', 'tugas')
        ->call('setTab', 'absensi')
        ->assertSet('activeTab', 'absensi');
});

test('ai generator tab is blocked for murid', function () {
    $this->actingAs($this->murid);

    Livewire::test(SchoolApp::class)
        ->call('setTab', 'ai_generator')
        ->assertSet('activeTab', 'dashboard')
        ->assertSet('flashType', 'error');
});

test('guru can open ai generator tab', function () {
    $this->actingAs($this->guru);

    Livewire::test(SchoolApp::class)
        ->call('setTab', 'ai_generator')
        ->assertSet('activeTab', 'ai_generator');
});

test('murid can submit daily attendance', function () {
    $this->actingAs($this->murid);

    Livewire::test(SchoolApp::class)
        ->set('selectedClassroomId', $this->classroom->id)
        ->set('attendanceStatus', 'hadir')
        ->set('attendanceNotes', 'Hadir tepat waktu')
        ->call('submitAttendance');

    expect(Attendance::where('student_id', $this->murid->id)
        ->where('classroom_id', $this->classroom->id)
        ->whereDate('date', Carbon::today())
        ->exists())->toBeTrue();
});

test('murid can submit assignment answers', function () {
    $this->actingAs($this->murid);

    $assignment = Assignment::create([
        'classroom_id' => $this->classroom->id,
        'teacher_id' => $this->guru->id,
        'title' => 'Tugas Uji Coba',
        'topic' => 'Routing',
        'questions_content' => '1. Apa itu routing?',
        'due_date' => Carbon::today()->addDays(5)->toDateString(),
        'status' => 'published',
    ]);

    Livewire::test(SchoolApp::class)
        ->call('startAssignment', $assignment->id)
        ->set('studentAnswerInput', 'Jawaban uji coba pest test')
        ->call('submitAssignment');

    expect(Submission::where('student_id', $this->murid->id)
        ->where('assignment_id', $assignment->id)
        ->where('student_answers', 'Jawaban uji coba pest test')
        ->exists())->toBeTrue();
});

test('guru can generate and publish ai assignment', function () {
    $this->actingAs($this->guru);

    $mockService = Mockery::mock(GroqAiService::class);
    $mockService->shouldReceive('generateAssignment')
        ->once()
        ->andReturn([
            'success' => true,
            'questions' => "### Soal Uji Coba AI\n1. Apa itu routing?",
            'answer_key' => 'Kunci: Routing memetakan URL ke controller',
        ]);

    app()->instance(GroqAiService::class, $mockService);

    Livewire::test(SchoolApp::class)
        ->set('aiTargetClassroomId', $this->classroom->id)
        ->set('aiTopic', 'Routing Laravel')
        ->set('aiMaterial', 'Routing adalah komponen krusial pada Laravel...')
        ->call('generateAndPublishAssignment', $mockService, true);

    expect(Assignment::where('title', 'like', '%Routing Laravel%')
        ->where('ai_generated', true)
        ->exists())->toBeTrue();
});

test('murid cannot create material or ai assignment', function () {
    $this->actingAs($this->murid);

    Livewire::test(SchoolApp::class)
        ->set('newMaterialTitle', 'Materi Ilegal')
        ->set('newMaterialContent', 'Isi materi')
        ->call('saveNewMaterial')
        ->assertSet('flashType', 'error');

    expect(Material::where('title', 'Materi Ilegal')->exists())->toBeFalse();
});
