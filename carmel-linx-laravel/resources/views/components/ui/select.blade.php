@props([
    'label' => null,
    'name' => null,
    'id' => null,
    'value' => '',
    'placeholder' => 'Select an option...',
    'options' => [], // array of key => label or list of values
    'error' => null,
    'disabled' => false
])

@php
    $selectId = $id ?? $name ?? 'select-' . uniqid();
    $selectedValue = old($name, $value);
    
    // Normalize options into associative array
    $normalizedOptions = [];
    foreach ($options as $k => $v) {
        if (is_numeric($k)) {
            $normalizedOptions[$v] = $v;
        } else {
            $normalizedOptions[$k] = $v;
        }
    }
    
    $selectedLabel = $normalizedOptions[$selectedValue] ?? $placeholder;
    if (empty($selectedValue) && empty($normalizedOptions)) {
        $selectedLabel = $placeholder;
    }
@endphp

<div class="w-full relative custom-dropdown-container" id="container-{{ $selectId }}">
    @if($label)
        <label class="block text-xs font-semibold text-slate-700 mb-1">
            {{ $label }}
        </label>
    @endif

    <!-- Hidden input for standard HTML form submission -->
    <input type="hidden" name="{{ $name }}" id="{{ $selectId }}" value="{{ $selectedValue }}">

    <!-- Dropdown Trigger Button -->
    <button 
        type="button" 
        id="trigger-{{ $selectId }}"
        @disabled($disabled)
        onclick="toggleCampusLynkDropdown('{{ $selectId }}')"
        class="w-full min-h-[44px] px-3.5 py-2 bg-white border {{ $error ? 'border-rose-400 focus:border-rose-500' : 'border-slate-200 hover:border-slate-300 focus:border-blue-500' }} text-slate-900 rounded-xl focus:ring-2 focus:ring-blue-500/10 outline-none text-sm transition-all flex items-center justify-between text-left disabled:bg-slate-100 disabled:text-slate-400 disabled:cursor-not-allowed select-none shadow-sm"
    >
        <span id="label-{{ $selectId }}" class="{{ empty($selectedValue) ? 'text-slate-400' : 'text-slate-900 font-medium' }} truncate">
            {{ $selectedLabel }}
        </span>
        <i data-lucide="chevron-down" id="chevron-{{ $selectId }}" class="w-4 h-4 text-slate-400 transition-transform duration-200 shrink-0 ml-2"></i>
    </button>

    <!-- Floating Options Menu -->
    <div 
        id="menu-{{ $selectId }}" 
        class="hidden absolute z-50 mt-1.5 w-full bg-white border border-slate-200 rounded-xl shadow-lg shadow-slate-900/5 py-1 max-h-60 overflow-y-auto outline-none transition-all"
    >
        @if(count($normalizedOptions) > 0)
            @foreach($normalizedOptions as $optValue => $optLabel)
                @php $isSelected = (string)$selectedValue === (string)$optValue; @endphp
                <div 
                    onclick="selectCampusLynkOption('{{ $selectId }}', '{{ $optValue }}', '{{ addslashes($optLabel) }}')"
                    class="px-4 py-2.5 text-sm cursor-pointer flex items-center justify-between transition-colors {{ $isSelected ? 'bg-blue-50/80 text-blue-700 font-semibold' : 'text-slate-700 hover:bg-slate-50 hover:text-slate-900' }}"
                >
                    <span class="truncate">{{ $optLabel }}</span>
                    @if($isSelected)
                        <i data-lucide="check" class="w-4 h-4 text-blue-600 shrink-0 ml-2"></i>
                    @endif
                </div>
            @endforeach
        @else
            {{ $slot }}
        @endif
    </div>

    @if($error)
        <p class="mt-1 text-xs text-rose-600 font-medium">{{ $error }}</p>
    @endif
</div>

<script>
    if (typeof window.toggleCampusLynkDropdown === 'undefined') {
        window.toggleCampusLynkDropdown = function(id) {
            const menu = document.getElementById('menu-' + id);
            const chevron = document.getElementById('chevron-' + id);
            if (!menu) return;

            const isClosed = menu.classList.contains('hidden');
            
            // Close all other open dropdowns
            document.querySelectorAll('[id^="menu-"]').forEach(el => {
                if (el !== menu) el.classList.add('hidden');
            });
            document.querySelectorAll('[id^="chevron-"]').forEach(el => {
                if (el !== chevron) el.classList.remove('rotate-180');
            });

            if (isClosed) {
                menu.classList.remove('hidden');
                if (chevron) chevron.classList.add('rotate-180');
            } else {
                menu.classList.add('hidden');
                if (chevron) chevron.classList.remove('rotate-180');
            }
        };

        window.selectCampusLynkOption = function(id, value, label) {
            const input = document.getElementById(id);
            const labelSpan = document.getElementById('label-' + id);
            const menu = document.getElementById('menu-' + id);
            const chevron = document.getElementById('chevron-' + id);

            if (input) input.value = value;
            if (labelSpan) {
                labelSpan.innerText = label;
                labelSpan.classList.remove('text-slate-400');
                labelSpan.classList.add('text-slate-900', 'font-medium');
            }

            if (menu) menu.classList.add('hidden');
            if (chevron) chevron.classList.remove('rotate-180');

            // Refresh Lucide icons in menu if any
            if (window.initLucide) window.initLucide();
            
            // Dispatch standard change event on input
            if (input) input.dispatchEvent(new Event('change', { bubbles: true }));
        };

        // Close on click outside
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.custom-dropdown-container')) {
                document.querySelectorAll('[id^="menu-"]').forEach(el => el.classList.add('hidden'));
                document.querySelectorAll('[id^="chevron-"]').forEach(el => el.classList.remove('rotate-180'));
            }
        });
    }
</script>
