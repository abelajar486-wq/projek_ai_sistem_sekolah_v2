<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Material;
use App\Services\DocumentExportService;

class DocumentController extends Controller
{
    public function material(Material $material, string $format, DocumentExportService $exporter)
    {
        abort_unless(in_array($format, ['word', 'pdf'], true), 404);

        return $exporter->download($format, $exporter->materialDocument($material));
    }

    public function assignment(Assignment $assignment, string $format, DocumentExportService $exporter)
    {
        abort_unless(in_array($format, ['word', 'pdf'], true), 404);

        $includeAnswerKey = (bool) auth()->user()?->isGuru();

        return $exporter->download($format, $exporter->assignmentDocument($assignment, $includeAnswerKey));
    }
}
