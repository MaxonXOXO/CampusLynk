<div id="panelActivity" class="hidden space-y-6">
  <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm space-y-6">
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 border-b border-slate-100 pb-6">
      <div class="md:col-span-2 space-y-3">
        <h3 class="text-sm font-bold text-slate-900">Activity Points Goal Tracker</h3>
        <p class="text-xs text-slate-500">Required graduation quota: {{ $activityGoal ?? 100 }} points under SBTE regulations.</p>
        <div class="w-full h-3 bg-slate-100 rounded-full overflow-hidden border border-slate-200">
          <div id="activityProgressBar" class="h-full bg-blue-600 transition-all duration-1000 ease-out" style="width: 0%"></div>
        </div>
        <div class="flex justify-between text-xs font-semibold text-slate-400">
          <span>0</span>
          <span>Target: {{ $activityGoal ?? 100 }} Points</span>
        </div>
      </div>

      <div class="bg-slate-50 rounded-xl p-4 border border-slate-200/60 flex flex-col justify-between">
        <div>
          <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Verified Total</span>
          <span class="text-2xl font-bold text-slate-900 block mt-1" id="verifiedActivityTotal">0</span>
        </div>
        <div class="mt-3 border-t border-slate-200/60 pt-2" id="activitySplitList"></div>
      </div>
    </div>

    <!-- Submit New Claim Form -->
    <div class="space-y-4">
      <h3 class="text-sm font-bold text-slate-900">Submit New Extracurricular Claim</h3>
      
      <form id="activityClaimForm" onsubmit="submitActivityClaim(event)" class="bg-slate-50 border border-slate-200/80 p-4 rounded-xl grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-3">
        <div class="lg:col-span-1">
          <x-ui.select name="semester" label="Semester" :options="['1'=>'Sem 1', '2'=>'Sem 2', '3'=>'Sem 3', '4'=>'Sem 4', '5'=>'Sem 5', '6'=>'Sem 6']" value="1" />
        </div>
        <div class="lg:col-span-1">
          <x-ui.select name="activity_segment" label="Segment" placeholder="Select..." :options="['NCC'=>'NCC', 'NSS'=>'NSS', 'Sports & Games'=>'Sports & Games', 'Cultural Activities'=>'Cultural Activities', 'Professional Self Initiatives'=>'Prof. Self Initiatives', 'Entrepreneurship and Innovation'=>'Innovation', 'Leadership & Management'=>'Leadership', 'Disaster Management'=>'Disaster Mgmt']" />
        </div>
        <div class="lg:col-span-1">
          <label class="block text-xs font-medium text-slate-700 mb-1">Activity Name</label>
          <input type="text" name="activity_name" required placeholder="e.g. Arts 1st Prize" class="w-full min-h-[44px] px-3.5 py-2 bg-white border border-slate-200 rounded-xl text-sm text-slate-900 outline-none">
        </div>
        <div class="lg:col-span-1">
          <x-ui.select name="level" label="Level" placeholder="Level..." :options="['Level I - College'=>'Level I - College', 'Level II - Zonal'=>'Level II - Zonal', 'Level III - State/Univ'=>'Level III - State', 'Level IV - National'=>'Level IV - National', 'Level V - International'=>'Level V - International']" />
        </div>
        <div class="lg:col-span-1">
          <label class="block text-xs font-medium text-slate-700 mb-1">Points Claimed</label>
          <input type="number" name="points_claimed" required min="1" max="50" class="w-full min-h-[44px] px-3.5 py-2 bg-white border border-slate-200 rounded-xl text-sm text-slate-900 outline-none">
        </div>
        <div class="lg:col-span-1 flex flex-col justify-end">
          <button type="submit" class="w-full min-h-[44px] bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-semibold text-xs transition-all shadow-sm">Submit Claim</button>
        </div>
      </form>
      <div id="activityClaimAlert" class="hidden p-3 rounded-xl text-xs font-semibold border"></div>
    </div>

    <!-- Student Claims History Table -->
    <div class="space-y-3">
      <h3 class="text-sm font-bold text-slate-900">My Activity Claims</h3>
      <div class="overflow-x-auto border border-slate-200 rounded-xl">
        <table class="w-full text-left text-xs">
          <thead class="bg-slate-50 text-slate-600 uppercase font-semibold border-b border-slate-200">
            <tr>
              <th class="p-3">Semester</th>
              <th class="p-3">Segment</th>
              <th class="p-3">Activity</th>
              <th class="p-3">Level</th>
              <th class="p-3 text-center">Claimed</th>
              <th class="p-3 text-center">Awarded</th>
              <th class="p-3 text-center">Status</th>
            </tr>
          </thead>
          <tbody id="studentActivityTableBody" class="divide-y divide-slate-100 text-slate-800">
            <tr><td colspan="7" class="p-4 text-center text-slate-400">Loading claims...</td></tr>
          </tbody>
        </table>
      </div>
    </div>

  </div>
</div>
