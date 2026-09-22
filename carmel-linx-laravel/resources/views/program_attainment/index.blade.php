<x-layouts.dashboard-layout title="Program Attainment — {{ $classroomId }}">
    <div class="space-y-6 pb-12">
        {{-- Header & Stat Cards --}}
        @include('program_attainment.partials.header-stats')

        {{-- Navigation Tabs --}}
        <div class="border-b border-white/10">
            <nav class="flex space-x-2" aria-label="Tabs">
                <button type="button" onclick="switchAttainmentTab('direct')" id="tab-btn-direct" class="tab-btn border-b-2 border-indigo-500 px-4 py-2.5 text-xs font-bold text-white transition">
                    Direct Course Matrix (80%)
                </button>
                <button type="button" onclick="switchAttainmentTab('indirect')" id="tab-btn-indirect" class="tab-btn border-b-2 border-transparent px-4 py-2.5 text-xs font-bold text-slate-400 hover:text-white transition">
                    Indirect Surveys (20%)
                </button>
                <button type="button" onclick="switchAttainmentTab('po')" id="tab-btn-po" class="tab-btn border-b-2 border-transparent px-4 py-2.5 text-xs font-bold text-slate-400 hover:text-white transition">
                    Program Outcomes (PO1–11)
                </button>
                <button type="button" onclick="switchAttainmentTab('pso')" id="tab-btn-pso" class="tab-btn border-b-2 border-transparent px-4 py-2.5 text-xs font-bold text-slate-400 hover:text-white transition">
                    Specific Outcomes (PSO1–3)
                </button>
                <button type="button" onclick="switchAttainmentTab('summary')" id="tab-btn-summary" class="tab-btn border-b-2 border-transparent px-4 py-2.5 text-xs font-bold text-slate-400 hover:text-white transition">
                    Summary & Action Plan
                </button>
            </nav>
        </div>

        {{-- Tab Panes --}}
        <div id="tab-pane-direct" class="tab-pane space-y-4">
            @include('program_attainment.partials.tab-direct-attainment')
        </div>

        <div id="tab-pane-indirect" class="tab-pane hidden space-y-4">
            @include('program_attainment.partials.tab-indirect-attainment')
        </div>

        <div id="tab-pane-po" class="tab-pane hidden space-y-4">
            @include('program_attainment.partials.tab-po-attainment')
        </div>

        <div id="tab-pane-pso" class="tab-pane hidden space-y-4">
            @include('program_attainment.partials.tab-pso-attainment')
        </div>

        <div id="tab-pane-summary" class="tab-pane hidden space-y-4">
            @include('program_attainment.partials.tab-summary')
        </div>
    </div>

    {{-- Notification Toast --}}
    <div id="attainment-toast" class="fixed bottom-6 right-6 z-50 hidden rounded-2xl border border-emerald-500/30 bg-slate-900/95 p-4 shadow-2xl backdrop-blur-xl text-xs text-emerald-300">
        <div class="flex items-center gap-2">
            <span class="material-symbols-rounded text-emerald-400">check_circle</span>
            <span id="attainment-toast-msg">Settings saved successfully!</span>
        </div>
    </div>

    <script>
    function switchAttainmentTab(tabKey) {
        document.querySelectorAll('.tab-pane').forEach(p => p.classList.add('hidden'));
        document.querySelectorAll('.tab-btn').forEach(b => {
            b.classList.remove('border-indigo-500', 'text-white');
            b.classList.add('border-transparent', 'text-slate-400');
        });

        const activePane = document.getElementById(`tab-pane-${tabKey}`);
        const activeBtn = document.getElementById(`tab-btn-${tabKey}`);
        if (activePane) activePane.classList.remove('hidden');
        if (activeBtn) {
            activeBtn.classList.remove('border-transparent', 'text-slate-400');
            activeBtn.classList.add('border-indigo-500', 'text-white');
        }
    }

    function saveProgramAttainmentConfig() {
        const classroomId = '{{ $classroomId }}';
        const poTargets = {};
        document.querySelectorAll('.po-target-input').forEach(input => {
            const key = input.name.match(/\[(.*?)\]/)[1];
            poTargets[key] = parseFloat(input.value) || 2.0;
        });

        const indirectSurveys = {};
        document.querySelectorAll('.indirect-survey-input').forEach(input => {
            const key = input.name.match(/\[(.*?)\]/)[1];
            indirectSurveys[key] = parseFloat(input.value) || 2.5;
        });

        const actionPlans = document.getElementById('program-action-plans')?.value || '';

        fetch(`/hod/program-attainment/${classroomId}/save`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            },
            body: JSON.stringify({
                po_targets: poTargets,
                indirect_surveys: indirectSurveys,
                action_plans: actionPlans
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'SUCCESS') {
                showToast(data.message);
                setTimeout(() => location.reload(), 1200);
            } else {
                alert(data.message || 'Failed to save configuration.');
            }
        })
        .catch(err => {
            alert('Error saving configuration: ' + err.message);
        });
    }

    function showToast(msg) {
        const toast = document.getElementById('attainment-toast');
        const toastMsg = document.getElementById('attainment-toast-msg');
        if (toast && toastMsg) {
            toastMsg.innerText = msg;
            toast.classList.remove('hidden');
            setTimeout(() => toast.classList.add('hidden'), 3000);
        }
    }
    </script>
</x-layouts.dashboard-layout>
