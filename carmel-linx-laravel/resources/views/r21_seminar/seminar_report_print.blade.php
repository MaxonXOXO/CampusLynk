@php
    $reportTitles = [
        'consolidated'   => 'Consolidated 6-Rubric Seminar Evaluation Register (Clause 11.2.6)',
        'cia_submission' => 'Official SBTE Final CIA Mark Entry Statement (75M)',
        'schedule'       => 'Seminar Presentation Schedule & Topic Log'
    ];
    $title = ($reportTitles[$reportType] ?? $reportTitles['consolidated']) . ' - ' . $subject->subject_name;
    $orientation = 'landscape';
@endphp

<x-layouts.report-layout :title="$title" :orientation="$orientation" :documentNo="'SBTE-R21-' . $subject->subject_code . '-' . ($classroom->current_semester ?? 'S6')">

    @if($reportType === 'cia_submission')
        @include('r21_seminar.partials.print.cia-submission')
    @elseif($reportType === 'schedule')
        @include('r21_seminar.partials.print.schedule')
    @else
        @include('r21_seminar.partials.print.consolidated')
    @endif

</x-layouts.report-layout>
