<div id="tab-groups" class="tab-content hidden space-y-4">
    <!-- Groups Action Header -->
    <div class="bg-white/5 border border-slate-700/60 rounded-xl p-4 flex flex-wrap items-center justify-between gap-3">
        <div>
            <h2 class="text-sm font-bold text-white flex items-center gap-2">
                <x-ui.icon name="users" class="w-4 h-4 text-amber-400" />
                <span>Project Groups &amp; Faculty Guide Allocation</span>
            </h2>
            <div class="text-xs text-slate-400 mt-0.5">
                Organize students into project batches, assign project titles, and allocate department guides.
            </div>
        </div>
        <div class="flex items-center gap-2">
            <x-ui.button 
                variant="secondary" 
                size="sm" 
                icon="plus" 
                onclick="addGroupRow()">
                <span class="ml-1.5">Add Project Group</span>
            </x-ui.button>
            <x-ui.button 
                variant="primary" 
                size="sm" 
                icon="check" 
                id="btnSaveGroups"
                onclick="saveProjectGroups()">
                <span class="ml-1.5">Save Groups</span>
            </x-ui.button>
        </div>
    </div>

    <!-- Groups Container -->
    <div id="projectGroupsContainer" class="space-y-4">
        @forelse($projectGroups as $idx => $grp)
            <x-ui.card>
                <div class="space-y-3" data-group-card data-group-index="{{ $idx }}" data-group-id="{{ $grp['id'] ?? ($idx + 1) }}">
                    <!-- Group Top Row -->
                    <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-700/60 pb-3">
                        <div class="flex items-center gap-2">
                            <span class="px-2.5 py-1 bg-amber-500/10 border border-amber-500/30 text-amber-400 rounded-lg text-xs font-bold font-mono">
                                Group {{ $grp['id'] ?? ($idx + 1) }}
                            </span>
                            <span class="text-xs text-slate-400 font-medium">
                                {{ count($grp['members'] ?? []) }} Members Assigned
                            </span>
                        </div>
                        <x-ui.button 
                            variant="danger" 
                            size="sm" 
                            onclick="promptDeleteGroup({{ $idx }})"
                            title="Delete this project group">
                            <x-ui.icon name="trash" class="w-3.5 h-3.5" />
                        </x-ui.button>
                    </div>

                    <!-- Group Fields: Title & Guide -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-300 mb-1">Project Title</label>
                            <input 
                                type="text" 
                                name="groups[{{ $idx }}][title]" 
                                value="{{ $grp['title'] ?? '' }}"
                                placeholder="Enter approved project title..."
                                class="w-full bg-slate-900 border border-slate-700 rounded-lg px-3 py-1.5 text-xs text-slate-200 placeholder-slate-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                            >
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-300 mb-1">Allocated Faculty Guide</label>
                            <select 
                                name="groups[{{ $idx }}][guide_mobile]" 
                                class="w-full bg-slate-900 border border-slate-700 rounded-lg px-3 py-1.5 text-xs text-slate-200 focus:outline-none focus:ring-1 focus:ring-blue-500">
                                <option value="">-- Select Faculty Guide --</option>
                                @foreach($guides as $guide)
                                    <option value="{{ $guide->mobile_no }}" {{ ($grp['guide_mobile'] ?? '') == $guide->mobile_no ? 'selected' : '' }}>
                                        {{ $guide->name }} ({{ $guide->designation }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Group Members Selection -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1.5">Group Members</label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-2 max-h-48 overflow-y-auto p-2 bg-slate-900/60 border border-slate-800 rounded-xl">
                            @foreach($students as $st)
                                @php
                                    $isMember = in_array($st->reg_no, $grp['members'] ?? []);
                                @endphp
                                <label class="flex items-center gap-2 text-xs text-slate-300 p-1.5 rounded hover:bg-slate-800/60 cursor-pointer">
                                    <input 
                                        type="checkbox" 
                                        name="groups[{{ $idx }}][members][]" 
                                        value="{{ $st->reg_no }}"
                                        {{ $isMember ? 'checked' : '' }}
                                        class="rounded border-slate-700 text-blue-600 focus:ring-0 focus:ring-offset-0 bg-slate-950"
                                    >
                                    <span class="truncate" title="{{ $st->name }} ({{ $st->reg_no }})">
                                        {{ $st->name }}
                                        <span class="text-[10px] text-slate-500 block font-mono">{{ $st->reg_no }}</span>
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>
            </x-ui.card>
        @empty
            <div class="bg-slate-900/40 border border-slate-800 rounded-xl p-8 text-center">
                <x-ui.icon name="users" class="w-10 h-10 text-slate-500 mx-auto mb-2" />
                <h3 class="text-sm font-bold text-white mb-1">No Project Groups Configured</h3>
                <p class="text-xs text-slate-400 mb-4 max-w-md mx-auto">
                    Group students into teams, assign project titles, and allocate department guides to enable group-wise evaluations and statutory reports.
                </p>
                <x-ui.button variant="primary" size="sm" icon="plus" onclick="addGroupRow()">
                    <span class="ml-1.5">Add First Project Group</span>
                </x-ui.button>
            </div>
        @endforelse
    </div>
</div>
