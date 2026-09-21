@php
    $reportTitles = [
        'group_dossier'   => 'Major Project Group-Wise Breakdown (Filing Register)',
        'group_breakdown' => 'Major Project Group-Wise Breakdown (Filing Register)',
        'cia_register'    => 'Continuous Internal Assessment (CIA) Register (75M)',
        'sbte_submission' => 'SBTE Final Mark Entry Statement (125M)',
        'ese_rubrics'     => 'ESE 8-Rubric Assessment Sheet (Clause 11.3.4)',
        'consolidated'    => 'Consolidated Major Project Evaluation Register (125M)'
    ];
    $title = ($reportTitles[$reportType] ?? $reportTitles['consolidated']) . ' - ' . $subject->subject_name;
    $orientation = in_array($reportType, ['consolidated', 'ese_rubrics', 'cia_register', 'group_breakdown', 'group_dossier']) ? 'landscape' : 'landscape';
@endphp

<x-layouts.report-layout :title="$title" :orientation="$orientation" :documentNo="'SBTE-R21-' . $subject->subject_code . '-' . ($classroom->current_semester ?? 'S6')">

    @if($reportType === 'group_dossier' || $reportType === 'group_breakdown')
        @include('r21_project.partials.print.group-breakdown')
    @elseif($reportType === 'cia_register')
        @include('r21_project.partials.print.cia-register')
    @elseif($reportType === 'sbte_submission')
        @include('r21_project.partials.print.sbte-submission')
    @elseif($reportType === 'ese_rubrics')
        @include('r21_project.partials.print.ese-rubrics')
    @else
        @include('r21_project.partials.print.consolidated')
    @endif

</x-layouts.report-layout>
