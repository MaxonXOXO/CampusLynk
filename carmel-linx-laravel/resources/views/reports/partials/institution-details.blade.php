@props([
    'collegeName' => 'Carmel Polytechnic College',
    'location' => 'Punnapra, Alappuzha - 688004, Kerala',
    'affiliation' => 'Approved by AICTE, New Delhi & Affiliated to SBTE, Kerala',
    'iso' => 'An ISO 9001:2015 Certified Institution'
])

<div class="text-center pb-3 border-b border-slate-200 mb-4">
    <div class="text-xs font-bold uppercase tracking-wider text-slate-900">{{ $collegeName }}</div>
    <div class="text-[11px] text-slate-600">{{ $location }}</div>
    <div class="text-[10px] text-slate-500 font-medium">{{ $affiliation }} · {{ $iso }}</div>
</div>
