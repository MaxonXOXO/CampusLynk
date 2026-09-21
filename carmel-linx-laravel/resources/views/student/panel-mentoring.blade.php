<div id="panelMentoring" class="hidden space-y-6">
          <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm space-y-6">
            
            <!-- Mentoring Header Banner -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-100 pb-4">
              <div>
                <h2 class="text-base font-bold text-slate-900 flex items-center gap-2">
                  <i data-lucide="book-open" class="w-4 h-4 text-blue-600"></i>
                  <span>Student Mentoring Diary</span>
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">360° student portfolio verified and monitored by faculty mentors.</p>
              </div>
              <div class="flex items-center gap-2">
                <button type="button" onclick="downloadMentoringPdf()" class="px-3.5 py-2 bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 rounded-xl text-xs font-semibold transition-all flex items-center gap-1.5">
                  <i data-lucide="download" class="w-3.5 h-3.5"></i>
                  <span>Export PDF</span>
                </button>
                <button type="button" onclick="saveStudentMentoringData()" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-semibold transition-all shadow-sm flex items-center gap-1.5">
                  <i data-lucide="save" class="w-3.5 h-3.5"></i>
                  <span>Save Changes</span>
                </button>
              </div>
            </div>

            <!-- Mentoring Sub-tabs Navigation -->
            <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-7 gap-1.5 p-1.5 bg-slate-100/90 rounded-2xl border border-slate-200/60" id="smdSubTabs">
              <button type="button" onclick="switchStudentMentoringTab('smdProfile')" id="tabBtn_smdProfile" class="smd-tab py-2 px-2.5 text-xs font-semibold rounded-xl bg-blue-600 text-white shadow-sm transition-all text-center">
                Personal
              </button>
              <button type="button" onclick="switchStudentMentoringTab('smdFamily')" id="tabBtn_smdFamily" class="smd-tab py-2 px-2.5 text-xs font-medium rounded-xl text-slate-600 hover:text-slate-900 transition-all text-center">
                Family
              </button>
              <button type="button" onclick="switchStudentMentoringTab('smdEducation')" id="tabBtn_smdEducation" class="smd-tab py-2 px-2.5 text-xs font-medium rounded-xl text-slate-600 hover:text-slate-900 transition-all text-center">
                Education
              </button>
              <button type="button" onclick="switchStudentMentoringTab('smdAcademic')" id="tabBtn_smdAcademic" class="smd-tab py-2 px-2.5 text-xs font-medium rounded-xl text-slate-600 hover:text-slate-900 transition-all text-center">
                Academics
              </button>
              <button type="button" onclick="switchStudentMentoringTab('smdBoard')" id="tabBtn_smdBoard" class="smd-tab py-2 px-2.5 text-xs font-medium rounded-xl text-slate-600 hover:text-slate-900 transition-all text-center">
                Board Exams
              </button>
              <button type="button" onclick="switchStudentMentoringTab('smdExtra')" id="tabBtn_smdExtra" class="smd-tab py-2 px-2.5 text-xs font-medium rounded-xl text-slate-600 hover:text-slate-900 transition-all text-center">
                Activities
              </button>
              <button type="button" onclick="switchStudentMentoringTab('smdMeetings')" id="tabBtn_smdMeetings" class="smd-tab py-2 px-2.5 text-xs font-medium rounded-xl text-slate-600 hover:text-slate-900 transition-all text-center">
                Meetings
              </button>
            </div>

            <!-- Mentoring Content Panes -->
            <div class="pt-2">
              
              <!-- 1. Personal & Guardian Details Pane -->
              <div id="smdProfile" class="smd-content-pane space-y-6">
                <div>
                  <h3 class="text-xs font-semibold text-slate-700 uppercase tracking-wider mb-3">Socio-Economic & Residential Profile</h3>
                  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 items-start">
                    <div>
                      <label class="block text-xs font-semibold text-slate-700 mb-1">Annual Family Income</label>
                      <input type="text" id="smd_annual_income" placeholder="e.g. ₹2,50,000" class="w-full min-h-[44px] px-3.5 py-2 bg-white border border-slate-200 rounded-xl text-sm text-slate-900 focus:border-blue-500 outline-none shadow-sm">
                    </div>
                    <div>
                      <x-ui.select id="smd_residential_status" name="residential_status" label="Residential Status" :options="['Day Scholar'=>'Day Scholar', 'Hosteller'=>'Hosteller']" value="Day Scholar" />
                    </div>
                    <div>
                      <label class="block text-xs font-semibold text-slate-700 mb-1">Scholarships Received</label>
                      <input type="text" id="smd_scholarships" placeholder="e.g. E-Grantz / NSP" class="w-full min-h-[44px] px-3.5 py-2 bg-white border border-slate-200 rounded-xl text-sm text-slate-900 focus:border-blue-500 outline-none shadow-sm">
                    </div>
                    <div>
                      <span class="block text-xs font-semibold text-slate-700 mb-1">Special Category</span>
                      <label for="smd_fee_waiver" class="flex items-center gap-2.5 min-h-[44px] px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl cursor-pointer hover:bg-slate-100/80 transition-colors select-none shadow-sm">
                        <input type="checkbox" id="smd_fee_waiver" class="w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                        <span class="text-xs font-medium text-slate-800">Fee Waiver Beneficiary</span>
                      </label>
                    </div>
                  </div>
                </div>

                <div class="border-t border-slate-100 pt-5">
                  <h3 class="text-xs font-semibold text-slate-700 uppercase tracking-wider mb-3">Guardian Information</h3>
                  <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                      <label class="block text-xs font-medium text-slate-700 mb-1">Guardian Name</label>
                      <input type="text" id="smd_guardian_name" class="w-full min-h-[44px] px-3.5 py-2 bg-white border border-slate-200 rounded-xl text-sm text-slate-900 focus:border-blue-500 outline-none">
                    </div>
                    <div>
                      <label class="block text-xs font-medium text-slate-700 mb-1">Relationship</label>
                      <input type="text" id="smd_guardian_relationship" class="w-full min-h-[44px] px-3.5 py-2 bg-white border border-slate-200 rounded-xl text-sm text-slate-900 focus:border-blue-500 outline-none">
                    </div>
                    <div>
                      <label class="block text-xs font-medium text-slate-700 mb-1">Contact Mobile</label>
                      <input type="text" id="smd_guardian_mobile" class="w-full min-h-[44px] px-3.5 py-2 bg-white border border-slate-200 rounded-xl text-sm text-slate-900 focus:border-blue-500 outline-none">
                    </div>
                    <div class="sm:col-span-3">
                      <label class="block text-xs font-medium text-slate-700 mb-1">Permanent Residential Address</label>
                      <textarea id="smd_guardian_address" rows="2" class="w-full p-3 bg-white border border-slate-200 rounded-xl text-sm text-slate-900 focus:border-blue-500 outline-none resize-none"></textarea>
                    </div>
                  </div>
                </div>
              </div>

              <!-- 2. Family Details Pane -->
              <div id="smdFamily" class="smd-content-pane hidden space-y-4">
                <div class="flex items-center justify-between">
                  <h3 class="text-xs font-semibold text-slate-700 uppercase tracking-wider">Family Members Roster</h3>
                  <button type="button" onclick="addFamilyRow()" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-semibold transition-all flex items-center gap-1">
                    <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                    <span>Add Member</span>
                  </button>
                </div>
                <div class="overflow-x-auto rounded-xl border border-slate-200">
                  <table class="w-full text-left text-xs border-collapse">
                    <thead>
                      <tr class="bg-slate-50 border-b border-slate-200 text-slate-700 font-semibold uppercase tracking-wider">
                        <th class="py-3 px-4">Member Name</th>
                        <th class="py-3 px-4">Relationship</th>
                        <th class="py-3 px-4">Education</th>
                        <th class="py-3 px-4">Occupation</th>
                        <th class="py-3 px-4">Contact No</th>
                        <th class="py-3 px-4 text-center">Action</th>
                      </tr>
                    </thead>
                    <tbody id="smdFamilyList" class="divide-y divide-slate-100 text-slate-800">
                      <tr><td colspan="6" class="p-6 text-center text-slate-400">Loading family records...</td></tr>
                    </tbody>
                  </table>
                </div>
              </div>

              <!-- 3. Prior Education Pane -->
              <div id="smdEducation" class="smd-content-pane hidden space-y-4">
                <div class="flex items-center justify-between">
                  <h3 class="text-xs font-semibold text-slate-700 uppercase tracking-wider">Prior Academic Qualifications</h3>
                  <button type="button" onclick="addEducationRow()" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-semibold transition-all flex items-center gap-1">
                    <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                    <span>Add Qualification</span>
                  </button>
                </div>
                <div class="overflow-x-auto rounded-xl border border-slate-200">
                  <table class="w-full text-left text-xs border-collapse">
                    <thead>
                      <tr class="bg-slate-50 border-b border-slate-200 text-slate-700 font-semibold uppercase tracking-wider">
                        <th class="py-3 px-4">Examination / Course</th>
                        <th class="py-3 px-4">Institution / Board</th>
                        <th class="py-3 px-4">Year of Passing</th>
                        <th class="py-3 px-4">Percentage / CGPA</th>
                        <th class="py-3 px-4 text-center">Action</th>
                      </tr>
                    </thead>
                    <tbody id="smdEducationList" class="divide-y divide-slate-100 text-slate-800">
                      <tr><td colspan="5" class="p-6 text-center text-slate-400">Loading qualification records...</td></tr>
                    </tbody>
                  </table>
                </div>
              </div>

              <!-- 4. Academic Progress Pane -->
              <div id="smdAcademic" class="smd-content-pane hidden space-y-4">
                <h3 class="text-xs font-semibold text-slate-700 uppercase tracking-wider">Internal Academic Records & Attendance</h3>
                <div id="smdAcademicReport" class="space-y-4">
                  <div class="p-6 text-center text-slate-400 text-xs font-medium">Loading semester academic trajectory...</div>
                </div>
              </div>

              <!-- 5. Board Exams Pane -->
              <div id="smdBoard" class="smd-content-pane hidden space-y-4">
                <div class="flex items-center justify-between">
                  <h3 class="text-xs font-semibold text-slate-700 uppercase tracking-wider">SBTE Board Examination Results</h3>
                  <button type="button" onclick="addBoardRow()" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-semibold transition-all flex items-center gap-1">
                    <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                    <span>Add Result</span>
                  </button>
                </div>
                <div class="overflow-x-auto rounded-xl border border-slate-200">
                  <table class="w-full text-left text-xs border-collapse">
                    <thead>
                      <tr class="bg-slate-50 border-b border-slate-200 text-slate-700 font-semibold uppercase tracking-wider">
                        <th class="py-3 px-4">Semester</th>
                        <th class="py-3 px-4 text-center">SGPA</th>
                        <th class="py-3 px-4 text-center">CGPA</th>
                        <th class="py-3 px-4 text-center">Activity Points</th>
                        <th class="py-3 px-4 text-center">Action</th>
                      </tr>
                    </thead>
                    <tbody id="smdBoardList" class="divide-y divide-slate-100 text-slate-800">
                      <tr><td colspan="5" class="p-6 text-center text-slate-400">Loading board exam records...</td></tr>
                    </tbody>
                  </table>
                </div>
              </div>

              <!-- 6. Extracurricular Pane -->
              <div id="smdExtra" class="smd-content-pane hidden space-y-4">
                <h3 class="text-xs font-semibold text-slate-700 uppercase tracking-wider">Extracurricular Activity Claims</h3>
                <div class="overflow-x-auto rounded-xl border border-slate-200">
                  <table class="w-full text-left text-xs border-collapse">
                    <thead>
                      <tr class="bg-slate-50 border-b border-slate-200 text-slate-700 font-semibold uppercase tracking-wider">
                        <th class="py-3 px-4">Event Name</th>
                        <th class="py-3 px-4">Level</th>
                        <th class="py-3 px-4">Prize / Participation</th>
                        <th class="py-3 px-4 text-center">Awarded Points</th>
                        <th class="py-3 px-4 text-right">Status</th>
                      </tr>
                    </thead>
                    <tbody id="smdExtraList" class="divide-y divide-slate-100 text-slate-800">
                      <tr><td colspan="5" class="p-6 text-center text-slate-400">Loading extracurricular records...</td></tr>
                    </tbody>
                  </table>
                </div>
              </div>

              <!-- 7. Mentor Meetings Pane -->
              <div id="smdMeetings" class="smd-content-pane hidden space-y-4">
                <h3 class="text-xs font-semibold text-slate-700 uppercase tracking-wider">Mentor-Mentee Interaction Logs</h3>
                <div class="overflow-x-auto rounded-xl border border-slate-200">
                  <table class="w-full text-left text-xs border-collapse">
                    <thead>
                      <tr class="bg-slate-50 border-b border-slate-200 text-slate-700 font-semibold uppercase tracking-wider">
                        <th class="py-3 px-4">Meeting Date</th>
                        <th class="py-3 px-4">Discussion Summary</th>
                        <th class="py-3 px-4">Faculty Mentor Remarks</th>
                        <th class="py-3 px-4">Action Taken</th>
                      </tr>
                    </thead>
                    <tbody id="smdMeetingsList" class="divide-y divide-slate-100 text-slate-800">
                      <tr><td colspan="4" class="p-6 text-center text-slate-400">Loading meeting records...</td></tr>
                    </tbody>
                  </table>
                </div>
              </div>

            </div>

          </div>
        </div>
