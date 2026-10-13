<?php

use App\Models\Assignment;
use App\Models\Classroom;
use App\Models\Material;
use App\Models\User;
use App\Services\DocumentExportService;
use App\Services\GroqAiService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

beforeEach(function () {
    $this->guru = User::factory()->create(['role' => 'guru']);
    $this->guru->assignSchoolRole('guru');

    $this->classroom = Classroom::create([
        'name' => 'RPL 11A',
        'code' => 'RPL11A',
        'description' => 'Kelas uji coba',
        'teacher_id' => $this->guru->id,
    ]);

    $this->material = Material::create([
        'classroom_id' => $this->classroom->id,
        'teacher_id' => $this->guru->id,
        'title' => 'Persamaan Linear Satu Variabel',
        'topic' => 'Matematika',
        'content' => <<<'MD'
# PLSV

Selesaikan $3x - 2 = 7$ dan hitung $\frac{72}{3.6}$ dengan $a \neq 0$.
MD,
    ]);
});

test('generate assignment tidak error saat materi memuat notasi matematika', function () {
    Http::fake([
        'api.groq.com/*' => Http::response([
            'choices' => [
                ['message' => ['content' => "### Soal\n1. Tentukan x.\n===KUNCI_JAWABAN_GURU===\nKunci: x = 3"]],
            ],
        ]),
    ]);

    $result = app(GroqAiService::class)->generateAssignment(
        material: 'Selesaikan $3x - 2 = 7$ dan $a \neq 0$ serta $\frac{72}{3.6}$.',
        topic: 'PLSV',
        questionCount: 2,
    );

    expect($result['success'])->toBeTrue()
        ->and($result['questions'])->toContain('Soal')
        ->and($result['answer_key'])->toContain('x = 3');

    Http::assertSent(fn ($request) => str_contains($request['messages'][1]['content'], '$3x - 2 = 7$')
        && str_contains($request['messages'][1]['content'], '$a \neq 0$'));
});

test('normalizeMath mengubah notasi latex menjadi karakter unicode', function () {
    $out = app(DocumentExportService::class)
        ->normalizeMath('Rumus: $a \times b$ dan $x^2$ serta $\frac{1}{2}$ dan $a \neq 0$.');

    expect($out)->toContain('a × b')
        ->and($out)->toContain('x²')
        ->and($out)->toContain('1/2')
        ->and($out)->toContain('a ≠ 0')
        ->and($out)->not->toContain('$');
});

test('materi dapat diunduh sebagai dokumen word', function () {
    $response = $this->actingAs($this->guru)->get(route('materials.download', [
        'material' => $this->material->id,
        'format' => 'word',
    ]));

    $response->assertOk()->assertDownload();
    expect($response->headers->get('content-disposition'))->toContain('.docx');
});

test('materi dapat diunduh sebagai dokumen pdf', function () {
    $response = $this->actingAs($this->guru)->get(route('materials.download', [
        'material' => $this->material->id,
        'format' => 'pdf',
    ]));

    $response->assertOk();
    expect($response->headers->get('content-type'))->toContain('application/pdf');
});

test('dokumen tugas menyertakan kunci jawaban hanya untuk guru', function () {
    $assignment = Assignment::create([
        'classroom_id' => $this->classroom->id,
        'teacher_id' => $this->guru->id,
        'title' => 'Tugas PLSV',
        'topic' => 'Matematika',
        'instructions' => 'Kerjakan dengan teliti.',
        'questions_content' => 'Tentukan nilai $x$ dari $2x = 8$.',
        'answer_key' => 'x = 4',
        'due_date' => Carbon::today()->addDays(3)->toDateString(),
        'status' => 'published',
        'ai_generated' => true,
    ]);

    $service = app(DocumentExportService::class);

    $denganKunci = array_column($service->assignmentDocument($assignment, true)['sections'], 'heading');
    $tanpaKunci = array_column($service->assignmentDocument($assignment, false)['sections'], 'heading');

    expect($denganKunci)->toContain('Kunci Jawaban & Rubrik Penilaian')
        ->and($tanpaKunci)->not->toContain('Kunci Jawaban & Rubrik Penilaian');
});

test('guest tidak dapat mengunduh dokumen materi', function () {
    $this->get(route('materials.download', [
        'material' => $this->material->id,
        'format' => 'pdf',
    ]))->assertRedirect('/login');
});
