<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class R26QuestionBank extends Model
{
    protected $table = 'r26_question_bank';

    protected $fillable = [
        'subject_code',
        'batch_subject_id',
        'series_no',
        'pattern_type',
        'part',
        'q_no',
        'question_text',
        'marks',
        'co_tag',
        'bloom_level',
        'choice_group',
        'scheme_key',
        'answer_key',
        'is_active',
        'created_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'marks' => 'integer',
    ];

    /**
     * Get all questions for a subject code and CO tag, grouped by part.
     */
    public static function getForSubjectCo(string $subjectCode, string $coTag, string $patternType): array
    {
        $rows = static::where('subject_code', $subjectCode)
            ->where('co_tag', $coTag)
            ->where('pattern_type', $patternType)
            ->where('is_active', true)
            ->orderBy('part')
            ->orderBy('id')
            ->get();

        $grouped = ['part_a' => [], 'part_b' => [], 'part_c' => []];
        foreach ($rows as $row) {
            $grouped[$row->part][] = [
                'q_no'         => $row->q_no,
                'text'         => $row->question_text,
                'marks'        => $row->marks,
                'co'           => $row->co_tag,
                'bloom'        => $row->bloom_level,
                'choice_group' => $row->choice_group,
                'scheme_key'   => $row->scheme_key,
                'answer_key'   => $row->answer_key,
            ];
        }

        return $grouped;
    }
}
