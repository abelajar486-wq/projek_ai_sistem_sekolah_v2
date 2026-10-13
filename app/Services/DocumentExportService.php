<?php

namespace App\Services;

use App\Models\Assignment;
use App\Models\Material;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Support\Str;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Shared\Html;
use Symfony\Component\HttpFoundation\Response;

class DocumentExportService
{
    /**
     * Simbol/perintah LaTeX umum -> karakter Unicode.
     *
     * @var array<string, string>
     */
    private const MATH_SYMBOLS = [
        '\\times' => '×', '\\div' => '÷', '\\pm' => '±', '\\mp' => '∓',
        '\\neq' => '≠', '\\ne' => '≠', '\\leq' => '≤', '\\le' => '≤',
        '\\geq' => '≥', '\\ge' => '≥', '\\approx' => '≈', '\\equiv' => '≡',
        '\\cdot' => '·', '\\ast' => '∗', '\\cdots' => '⋯', '\\ldots' => '…',
        '\\dots' => '…', '\\to' => '→', '\\rightarrow' => '→', '\\leftarrow' => '←',
        '\\Rightarrow' => '⇒', '\\Leftarrow' => '⇐', '\\Leftrightarrow' => '⇔',
        '\\infty' => '∞', '\\int' => '∫', '\\sum' => '∑', '\\prod' => '∏',
        '\\angle' => '∠', '\\triangle' => '△', '\\perp' => '⊥', '\\parallel' => '∥',
        '\\therefore' => '∴', '\\because' => '∵', '\\notin' => '∉', '\\subset' => '⊂',
        '\\subseteq' => '⊆', '\\cup' => '∪', '\\cap' => '∩', '\\in' => '∈',
        '\\partial' => '∂', '\\nabla' => '∇', '\\propto' => '∝', '\\degree' => '°',
        '\\circ' => '∘', '\\alpha' => 'α', '\\beta' => 'β', '\\gamma' => 'γ',
        '\\delta' => 'δ', '\\epsilon' => 'ε', '\\varepsilon' => 'ε', '\\zeta' => 'ζ',
        '\\eta' => 'η', '\\theta' => 'θ', '\\vartheta' => 'ϑ', '\\iota' => 'ι',
        '\\kappa' => 'κ', '\\lambda' => 'λ', '\\mu' => 'μ', '\\nu' => 'ν',
        '\\xi' => 'ξ', '\\rho' => 'ρ', '\\sigma' => 'σ', '\\tau' => 'τ',
        '\\upsilon' => 'υ', '\\phi' => 'φ', '\\varphi' => 'φ', '\\chi' => 'χ',
        '\\psi' => 'ψ', '\\omega' => 'ω', '\\Delta' => 'Δ', '\\Gamma' => 'Γ',
        '\\Theta' => 'Θ', '\\Lambda' => 'Λ', '\\Xi' => 'Ξ', '\\Pi' => 'Π',
        '\\Sigma' => 'Σ', '\\Phi' => 'Φ', '\\Psi' => 'Ψ', '\\Omega' => 'Ω',
    ];

    /**
     * @var array<string, string>
     */
    private const SUPERSCRIPTS = [
        '0' => '⁰', '1' => '¹', '2' => '²', '3' => '³', '4' => '⁴',
        '5' => '⁵', '6' => '⁶', '7' => '⁷', '8' => '⁸', '9' => '⁹',
        '+' => '⁺', '-' => '⁻', '=' => '⁼', '(' => '⁽', ')' => '⁾',
        'n' => 'ⁿ', 'i' => 'ⁱ',
    ];

    /**
     * @var array<string, string>
     */
    private const SUBSCRIPTS = [
        '0' => '₀', '1' => '₁', '2' => '₂', '3' => '₃', '4' => '₄',
        '5' => '₅', '6' => '₆', '7' => '₇', '8' => '₈', '9' => '₉',
        '+' => '₊', '-' => '₋', '=' => '₌', '(' => '₍', ')' => '₎',
        'a' => 'ₐ', 'e' => 'ₑ', 'h' => 'ₕ', 'i' => 'ᵢ', 'j' => 'ⱼ',
        'k' => 'ₖ', 'l' => 'ₗ', 'm' => 'ₘ', 'n' => 'ₙ', 'o' => 'ₒ',
        'p' => 'ₚ', 'r' => 'ᵣ', 's' => 'ₛ', 't' => 'ₜ', 'u' => 'ᵤ',
        'v' => 'ᵥ', 'x' => 'ₓ',
    ];

    /**
     * Bangun struktur dokumen dari sebuah materi.
     *
     * @return array<string, mixed>
     */
    public function materialDocument(Material $material): array
    {
        $material->loadMissing(['classroom', 'teacher']);

        return [
            'title' => (string) $material->title,
            'subtitle' => 'Materi Pembelajaran',
            'meta' => array_filter([
                'Topik' => $material->topic,
                'Kelas' => $material->classroom?->name,
                'Guru' => $material->teacher?->name,
            ], fn ($value) => filled($value)),
            'sections' => [
                ['heading' => null, 'body' => (string) $material->content],
            ],
            'filename' => 'Materi-'.$material->title,
        ];
    }

    /**
     * Bangun struktur dokumen dari sebuah tugas. Kunci jawaban hanya disertakan
     * bila diminta (mis. saat diunduh oleh guru).
     *
     * @return array<string, mixed>
     */
    public function assignmentDocument(Assignment $assignment, bool $includeAnswerKey = false): array
    {
        $assignment->loadMissing(['classroom', 'teacher']);

        $sections = [];

        if (filled($assignment->instructions)) {
            $sections[] = ['heading' => 'Petunjuk Pengerjaan', 'body' => (string) $assignment->instructions];
        }

        $sections[] = ['heading' => 'Butir Soal', 'body' => (string) $assignment->questions_content];

        if ($includeAnswerKey && filled($assignment->answer_key)) {
            $sections[] = ['heading' => 'Kunci Jawaban & Rubrik Penilaian', 'body' => (string) $assignment->answer_key];
        }

        return [
            'title' => (string) $assignment->title,
            'subtitle' => 'Lembar Tugas',
            'meta' => array_filter([
                'Topik' => $assignment->topic,
                'Kelas' => $assignment->classroom?->name,
                'Guru' => $assignment->teacher?->name,
                'Tenggat' => $assignment->due_date?->format('d-m-Y'),
            ], fn ($value) => filled($value)),
            'sections' => $sections,
            'filename' => 'Tugas-'.$assignment->title,
        ];
    }

    /**
     * Kembalikan response unduhan sesuai format ('word' atau 'pdf').
     *
     * @param  array<string, mixed>  $document
     */
    public function download(string $format, array $document): Response
    {
        return strtolower($format) === 'pdf'
            ? $this->toPdf($document)
            : $this->toWord($document);
    }

    /**
     * @param  array<string, mixed>  $document
     */
    private function toWord(array $document): Response
    {
        $phpWord = new PhpWord;
        $phpWord->setDefaultFontName('Times New Roman');
        $phpWord->setDefaultFontSize(12);

        $section = $phpWord->addSection([
            'marginTop' => 1134,
            'marginBottom' => 1134,
            'marginLeft' => 1134,
            'marginRight' => 1134,
        ]);

        $section->addText(
            (string) $document['title'],
            ['bold' => true, 'size' => 18],
            ['alignment' => 'center', 'spaceAfter' => 80]
        );

        if (filled($document['subtitle'] ?? null)) {
            $section->addText(
                (string) $document['subtitle'],
                ['size' => 12, 'color' => '555555'],
                ['alignment' => 'center', 'spaceAfter' => 200]
            );
        }

        foreach ($document['meta'] ?? [] as $label => $value) {
            $section->addText($label.': '.$value, ['size' => 11]);
        }

        if (! empty($document['meta'])) {
            $section->addText('', [], [
                'borderBottomSize' => 6,
                'borderBottomColor' => '999999',
                'spaceAfter' => 160,
            ]);
        }

        foreach ($document['sections'] ?? [] as $sec) {
            if (filled($sec['heading'] ?? null)) {
                $section->addText(
                    (string) $sec['heading'],
                    ['bold' => true, 'size' => 14],
                    ['spaceBefore' => 140, 'spaceAfter' => 60]
                );
            }

            $this->addMarkdownBody($section, (string) ($sec['body'] ?? ''));
        }

        $section->addText(
            'Dokumen dibuat otomatis oleh EduBot AI.',
            ['size' => 9, 'italic' => true, 'color' => '888888'],
            ['alignment' => 'center', 'spaceBefore' => 240]
        );

        $tempPath = tempnam(sys_get_temp_dir(), 'edudoc_').'.docx';
        IOFactory::createWriter($phpWord, 'Word2007')->save($tempPath);

        return response()->download($tempPath, $this->filename($document).'.docx')
            ->deleteFileAfterSend(true);
    }

    /**
     * @param  array<string, mixed>  $document
     */
    private function toPdf(array $document): Response
    {
        $options = new Options;
        $options->set('isRemoteEnabled', false);
        $options->set('isHtml5ParserEnabled', true);
        $options->set('defaultFont', 'DejaVu Sans');

        $dompdf = new Dompdf($options);

        $html = view('documents.pdf', [
            'document' => $document,
            'sections' => array_map(fn ($sec) => [
                'heading' => $sec['heading'] ?? null,
                'html' => $this->markdownToHtml((string) ($sec['body'] ?? '')),
            ], $document['sections'] ?? []),
            'generatedAt' => now(),
        ])->render();

        $dompdf->loadHtml($html, 'UTF-8');
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return response($dompdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="'.$this->filename($document).'.pdf"',
        ]);
    }

    private function addMarkdownBody(object $section, string $markdown): void
    {
        if (trim($markdown) === '') {
            return;
        }

        $html = $this->markdownToHtml($markdown);

        try {
            Html::addHtml($section, $html, false, false);
        } catch (\Throwable $e) {
            $section->addText(html_entity_decode(strip_tags($html)), ['size' => 12]);
        }
    }

    private function markdownToHtml(string $markdown): string
    {
        return (string) Str::markdown($this->normalizeMath($markdown), [
            'html_input' => 'strip',
            'allow_unsafe_links' => false,
        ]);
    }

    /**
     * Ubah notasi LaTeX (mis. $x^2$, \frac{a}{b}, \times) menjadi teks Unicode
     * yang dapat dibaca pada dokumen Word maupun PDF.
     */
    public function normalizeMath(string $text): string
    {
        if ($text === '' || ! str_contains($text, '$') && ! str_contains($text, '\\')) {
            return $text;
        }

        $text = (string) preg_replace_callback(
            '/\$\$(.+?)\$\$/s',
            fn ($m) => "\n\n".$this->convertMathExpression($m[1])."\n\n",
            $text
        );

        $text = (string) preg_replace_callback(
            '/\$(.+?)\$/s',
            fn ($m) => $this->convertMathExpression($m[1]),
            $text
        );

        $text = (string) preg_replace_callback(
            '/\\\\\[(.+?)\\\\\]/s',
            fn ($m) => "\n\n".$this->convertMathExpression($m[1])."\n\n",
            $text
        );

        return (string) preg_replace_callback(
            '/\\\\\((.+?)\\\\\)/s',
            fn ($m) => $this->convertMathExpression($m[1]),
            $text
        );
    }

    private function convertMathExpression(string $expr): string
    {
        $expr = trim($expr);

        $expr = (string) preg_replace_callback(
            '/\\\\(?:d|t)?frac\s*\{([^{}]*)\}\s*\{([^{}]*)\}/',
            fn ($m) => $m[1].'/'.$m[2],
            $expr
        );

        $expr = (string) preg_replace_callback(
            '/\\\\sqrt\s*(?:\[([^\]]*)\])?\s*\{([^{}]*)\}/',
            function ($m) {
                $root = trim($m[1] ?? '');

                return $root === '' ? '√('.$m[2].')' : $root.'√('.$m[2].')';
            },
            $expr
        );

        $expr = (string) preg_replace(
            '/\\\\(?:text|mathrm|mathbf|mathit|mathsf|texttt|mbox|operatorname)\s*\{([^{}]*)\}/',
            '$1',
            $expr
        );

        $expr = (string) preg_replace('/\\\\vec\s*\{([^{}]*)\}/', '$1', $expr);

        $expr = str_replace(['\\left', '\\right'], '', $expr);
        $expr = (string) preg_replace('/\\\\(?:big|Big|bigg|Bigg)[lr]?/', '', $expr);
        $expr = (string) preg_replace('/\\\\(?:quad|qquad)/', ' ', $expr);
        $expr = str_replace('\\ ', ' ', $expr);
        $expr = (string) preg_replace('/\\\\[,;:!]/', '', $expr);

        $keys = array_keys(self::MATH_SYMBOLS);
        usort($keys, fn ($a, $b) => strlen($b) <=> strlen($a));
        foreach ($keys as $key) {
            $expr = str_replace($key, self::MATH_SYMBOLS[$key], $expr);
        }

        $expr = str_replace('^\\circ', '°', $expr);
        $expr = $this->convertScripts($expr);
        $expr = (string) preg_replace('/\\\\([%_&#{}\$])/', '$1', $expr);
        $expr = str_replace(['{', '}'], '', $expr);
        $expr = (string) preg_replace('/\\\\([a-zA-Z]+)/', '$1', $expr);
        $expr = (string) preg_replace('/\s+/', ' ', $expr);

        return trim($expr);
    }

    private function convertScripts(string $expr): string
    {
        $expr = (string) preg_replace_callback(
            '/(\^|_)\{([^{}]*)\}/',
            fn ($m) => $this->mapScript($m[2], $m[1] === '^'),
            $expr
        );

        return (string) preg_replace_callback(
            '/(\^|_)([0-9A-Za-z]+)/',
            fn ($m) => $this->mapScript($m[2], $m[1] === '^'),
            $expr
        );
    }

    private function mapScript(string $content, bool $superscript): string
    {
        $map = $superscript ? self::SUPERSCRIPTS : self::SUBSCRIPTS;
        $out = '';

        foreach (mb_str_split($content) as $char) {
            if (! isset($map[$char])) {
                return ($superscript ? '^(' : '_(').$content.')';
            }

            $out .= $map[$char];
        }

        return $out;
    }

    /**
     * @param  array<string, mixed>  $document
     */
    private function filename(array $document): string
    {
        $base = Str::slug((string) ($document['filename'] ?? $document['title'] ?? 'dokumen'));

        if ($base === '') {
            $base = 'dokumen';
        }

        return $base.'-'.now()->format('Ymd');
    }
}
