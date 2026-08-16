@props([
    'headers' => []
])

<div class="w-full bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            @if(count($headers) > 0)
                <thead>
                    <tr class="bg-slate-50/90 border-b border-slate-200">
                        @foreach($headers as $header)
                            <th class="py-3.5 px-6 text-xs font-semibold text-slate-700 uppercase tracking-wider">{{ $header }}</th>
                        @endforeach
                    </tr>
                </thead>
            @endif
            <tbody class="divide-y divide-slate-100 text-sm text-slate-800">
                {{ $slot }}
            </tbody>
        </table>
    </div>
</div>
