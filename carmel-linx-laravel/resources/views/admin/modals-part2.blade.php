            <select id="peCategory" name="event_category" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-900 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
              <option value="Academic">Academic Schedule</option>
              <option value="Exam">Examinations</option>
              <option value="Cultural">Cultural / Fest</option>
              <option value="Sports">Sports &amp; Athletics</option>
              <option value="Workshop">Workshop / Seminar</option>
              <option value="Holiday">Holiday / Campus Event</option>
              <option value="Meeting">Institutional Meeting / Ceremony</option>
              <option value="Other">Other Special Event</option>
            </select>
          </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
          <div>
            <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Event Date <span class="text-rose-500">*</span></label>
            <input type="date" id="peDate" name="event_date" required class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-900 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
          </div>
          <div>
            <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Start Time</label>
            <input type="time" id="peStartTime" name="start_time" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-900 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
          </div>
          <div>
            <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">End Time</label>
            <input type="time" id="peEndTime" name="end_time" class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-900 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
          </div>
        </div>

        <div class="p-3.5 bg-slate-50 border border-slate-200 rounded-xl space-y-3">
          <span class="block text-slate-700 font-bold text-xs uppercase tracking-wider flex items-center gap-1.5">
            <x-ui.icon name="users" class="w-4 h-4 text-emerald-600" /> Target Scope &amp; Audience
          </span>
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <div>
              <label class="block text-slate-600 mb-1 font-semibold">Target Audience</label>
              <select id="peTargetAudience" name="target_audience" onchange="togglePrincipalEventTargetFields()" class="w-full bg-white border border-slate-200 rounded-xl px-2.5 py-2 text-sm text-slate-800 outline-none focus:border-blue-500">
                <option value="ALL_CAMPUS">🌐 College Wide (All Staff &amp; Students)</option>
                <option value="DEPT_SPECIFIC">🏫 Department Specific</option>
                <option value="STAFF_ONLY">👨‍🏫 Staff Only</option>
                <option value="STUDENTS_ONLY">🎓 Students Only</option>
                <option value="SPECIAL_GROUP">⭐ Special Group</option>
              </select>
            </div>
            <div id="peDeptWrapper" style="display:none;">
              <label class="block text-slate-600 mb-1 font-semibold">Target Department</label>
              <select id="peTargetDepartment" name="target_department" class="w-full bg-white border border-slate-200 rounded-xl px-2.5 py-2 text-sm text-slate-800 outline-none focus:border-blue-500">
                <option value="ALL">All Departments</option>
                <option value="EL">Electronics Engg (EL)</option>
                <option value="ME">Mechanical Engg (ME)</option>
                <option value="CE">Civil Engg (CE)</option>
                <option value="EEE">Electrical Engg (EEE)</option>
                <option value="CT">Computer Engg (CT)</option>
                <option value="AU">Automobile Engg (AU)</option>
                <option value="GEN_AIDED">General Aided</option>
                <option value="GEN_SF">General SF</option>
              </select>
            </div>
            <div id="peSemWrapper" style="display:none;">
              <label class="block text-slate-600 mb-1 font-semibold">Semester Level</label>
              <select id="peTargetSemester" name="target_semester" class="w-full bg-white border border-slate-200 rounded-xl px-2.5 py-2 text-sm text-slate-800 outline-none focus:border-blue-500">
                <option value="ALL">All Semesters (S1 to S6)</option>
                <option value="S1">Semester 1 (S1)</option>
                <option value="S2">Semester 2 (S2)</option>
                <option value="S3">Semester 3 (S3)</option>
                <option value="S4">Semester 4 (S4)</option>
                <option value="S5">Semester 5 (S5)</option>
                <option value="S6">Semester 6 (S6)</option>
              </select>
            </div>
            <div id="peSpecialGroupWrapper" style="display:none;">
              <label class="block text-slate-600 mb-1 font-semibold">Special Group</label>
              <select id="peSpecialGroupName" name="special_group_name" class="w-full bg-white border border-slate-200 rounded-xl px-2.5 py-2 text-sm text-slate-800 outline-none focus:border-blue-500">
                <option value="Placement Cell">Placement &amp; Training Cell</option>
                <option value="NSS / NCC">NSS / NCC Units</option>
                <option value="Sports Council">Sports &amp; Athletics Council</option>
                <option value="Student Council">Student Council</option>
                <option value="IEDC Cell">IEDC Incubation Cell</option>
              </select>
            </div>
          </div>
        </div>

        <div>
          <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Venue / Location</label>
          <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4">
            <input type="text" id="peVenue" name="venue" placeholder="e.g., Main Auditorium / Seminar Hall" class="flex-1 w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-900 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
            <div class="flex items-center gap-4 shrink-0 pt-1 sm:pt-0">
              <label class="inline-flex items-center gap-1.5 cursor-pointer select-none text-xs font-semibold text-slate-700">
                <input type="checkbox" id="peIsFullDay" name="is_full_day" value="1" class="w-4 h-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                <span>Full Day Event</span>
              </label>
              <label class="inline-flex items-center gap-1.5 cursor-pointer select-none text-xs font-bold text-amber-600">
                <input type="checkbox" id="peRequiresRsvp" name="requires_rsvp" value="1" class="w-4 h-4 rounded border-amber-300 text-amber-600 focus:ring-amber-500">
                <span>RSVP / Attendance Required</span>
              </label>
            </div>
          </div>
        </div>

        <div>
          <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Event Description &amp; Details</label>
          <textarea id="peDescription" name="description" rows="2" placeholder="Enter details about event objectives, schedule, guest speakers, instructions..." class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-900 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100 resize-none leading-relaxed"></textarea>
        </div>

        <div class="p-3 bg-slate-50 border border-slate-200 rounded-xl space-y-1.5">
          <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider flex items-center gap-1.5">
            <x-ui.icon name="link" class="w-4 h-4 text-emerald-600" />
            <span>Attach Flyer / Document <span class="text-slate-400 font-normal lowercase">(optional PDF or image)</span></span>
          </label>
          <input type="file" id="peAttachment" name="attachment" accept=".pdf,.png,.jpg,.jpeg,.webp" class="w-full text-xs text-slate-600 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 cursor-pointer">
        </div>

        <div class="flex gap-3 pt-2">
          <button type="button" onclick="closePrincipalScheduleEventModal()" class="flex-1 py-2.5 border border-slate-200 hover:bg-slate-100 rounded-xl font-semibold text-slate-700 transition-all text-sm">Cancel</button>
          <button type="submit" id="peSubmitBtn" class="flex-1 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-semibold transition-all text-sm flex items-center justify-center gap-1.5 shadow-sm">
            <x-ui.icon name="event_available" class="w-4 h-4" />
            <span>Schedule &amp; Broadcast Event</span>
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- 8. PRINCIPAL SCHEDULED EVENTS HISTORY MODAL -->
  <div id="principalScheduleEventHistoryModal" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
    <div class="bg-white border border-slate-200 rounded-3xl w-full max-w-4xl p-6 sm:p-8 shadow-2xl space-y-4 max-h-[90vh] overflow-y-auto">
      <div class="flex justify-between items-center border-b border-slate-100 pb-3">
        <h3 class="font-bold text-slate-900 text-base flex items-center gap-2">
          <x-ui.icon name="event_available" class="w-5 h-5 text-emerald-600" />
          <span>Scheduled Events Audit Log</span>
        </h3>
        <button onclick="closePrincipalScheduleEventHistoryModal()" class="p-1.5 text-slate-400 hover:text-slate-700 rounded-lg"><x-ui.icon name="x" class="w-4 h-4" /></button>
      </div>

      <div class="overflow-x-auto custom-scrollbar border border-slate-100 rounded-xl">
        <table class="w-full text-left text-xs">
          <thead class="bg-slate-50 text-slate-700 font-semibold">
            <tr>
              <th class="p-3">Date &amp; Time</th>
              <th class="p-3">Title &amp; Category</th>
              <th class="p-3">Target Scope</th>
              <th class="p-3 text-right">Actions</th>
            </tr>
          </thead>
          <tbody id="principalEventHistoryBody" class="divide-y divide-slate-100 text-slate-800">
            <tr><td colspan="4" class="p-4 text-center text-slate-400">Loading events...</td></tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- 9. TODAY'S EVENTS LIST MODAL BY CATEGORIES (FROM PREVIOUS DESIGN) -->
  <div id="todayEventsModal" class="fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-sm hidden items-center justify-center p-4 md:p-6 overflow-y-auto">
    <div class="bg-white border border-slate-200 rounded-3xl max-w-5xl w-full shadow-2xl overflow-hidden flex flex-col max-h-[90vh]">
      <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
        <div class="flex items-center gap-3">
          <div class="p-2.5 bg-sky-50 text-sky-600 rounded-xl flex items-center justify-center">
            <x-ui.icon name="event_available" class="w-6 h-6" />
          </div>
          <div>
            <h3 class="text-base font-bold text-slate-900 flex items-center gap-2">
              Today's Campus &amp; Academic Events
              <span id="modalEventsTotalBadge" class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-sky-50 text-sky-700 border border-sky-200">0 Total</span>
            </h3>
            <p class="text-xs text-slate-500 mt-0.5">Categorized by Departments, College, NSS, NCC, IEDC, Placement Cell &amp; Others</p>
          </div>
        </div>
        <button onclick="closeTodayEventsModal()" class="p-2 rounded-xl text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition cursor-pointer">
          <span class="material-symbols-rounded text-xl">close</span>
        </button>
      </div>

      <!-- Category Filter Tabs Bar -->
      <div class="p-3.5 bg-slate-50/80 border-b border-slate-100 overflow-x-auto custom-scrollbar flex items-center gap-2">
        <button onclick="filterEventsByCategory('ALL')" id="evtCatTab_ALL" class="evt-cat-tab px-3 py-1.5 rounded-xl text-xs font-bold transition flex items-center gap-1.5 shrink-0 bg-sky-50 text-sky-700 border border-sky-200 shadow-2xs">
          <span>All Events</span>
          <span id="evtCnt_ALL" class="px-1.5 py-0.2 text-[10px] font-bold rounded-full bg-sky-100 text-sky-800">0</span>
        </button>
        <button onclick="filterEventsByCategory('Departments')" id="evtCatTab_Departments" class="evt-cat-tab px-3 py-1.5 rounded-xl text-xs font-bold transition flex items-center gap-1.5 shrink-0 bg-white text-slate-700 border border-slate-200 hover:border-amber-400">
          <span class="w-2 h-2 rounded-full bg-amber-500"></span>
          <span>Departments</span>
          <span id="evtCnt_Departments" class="px-1.5 py-0.2 text-[10px] font-bold rounded-full bg-slate-100 text-slate-700">0</span>
        </button>
        <button onclick="filterEventsByCategory('College')" id="evtCatTab_College" class="evt-cat-tab px-3 py-1.5 rounded-xl text-xs font-bold transition flex items-center gap-1.5 shrink-0 bg-white text-slate-700 border border-slate-200 hover:border-sky-400">
          <span class="w-2 h-2 rounded-full bg-sky-500"></span>
          <span>College / Academic</span>
          <span id="evtCnt_College" class="px-1.5 py-0.2 text-[10px] font-bold rounded-full bg-slate-100 text-slate-700">0</span>
        </button>
        <button onclick="filterEventsByCategory('NSS')" id="evtCatTab_NSS" class="evt-cat-tab px-3 py-1.5 rounded-xl text-xs font-bold transition flex items-center gap-1.5 shrink-0 bg-white text-slate-700 border border-slate-200 hover:border-emerald-400">
          <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
          <span>NSS</span>
          <span id="evtCnt_NSS" class="px-1.5 py-0.2 text-[10px] font-bold rounded-full bg-slate-100 text-slate-700">0</span>
        </button>
        <button onclick="filterEventsByCategory('NCC')" id="evtCatTab_NCC" class="evt-cat-tab px-3 py-1.5 rounded-xl text-xs font-bold transition flex items-center gap-1.5 shrink-0 bg-white text-slate-700 border border-slate-200 hover:border-rose-400">
          <span class="w-2 h-2 rounded-full bg-rose-500"></span>
          <span>NCC</span>
          <span id="evtCnt_NCC" class="px-1.5 py-0.2 text-[10px] font-bold rounded-full bg-slate-100 text-slate-700">0</span>
        </button>
        <button onclick="filterEventsByCategory('Placement Cell')" id="evtCatTab_Placement Cell" class="evt-cat-tab px-3 py-1.5 rounded-xl text-xs font-bold transition flex items-center gap-1.5 shrink-0 bg-white text-slate-700 border border-slate-200 hover:border-teal-400">
          <span class="w-2 h-2 rounded-full bg-teal-500"></span>
          <span>Placement Cell</span>
          <span id="evtCnt_Placement Cell" class="px-1.5 py-0.2 text-[10px] font-bold rounded-full bg-slate-100 text-slate-700">0</span>
        </button>
      </div>

      <div class="p-5 overflow-y-auto flex-grow space-y-3 custom-scrollbar" id="modalEventsListContainer">
        <!-- Events injected via JS -->
      </div>

      <div class="p-4 border-t border-slate-100 bg-slate-50 flex items-center justify-between text-xs">
        <span class="text-slate-500">Displaying <strong id="modalShowingCount" class="text-slate-900">0</strong> scheduled event(s)</span>
        <button onclick="closeTodayEventsModal()" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-800 font-semibold rounded-xl text-xs transition">Close Window</button>
      </div>
    </div>
  </div>

  <!-- 10. CAMPUS GEOFENCE SETUP MODAL (PORTED INTERACTIVE MAP & CONTROLS) -->
  <div id="geofenceModal" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 hidden items-center justify-center p-4 md:p-6 overflow-y-auto">
    <div class="bg-white border border-slate-200 rounded-3xl w-full max-w-5xl shadow-2xl overflow-hidden flex flex-col max-h-[90vh]">
      
      <!-- Header -->
      <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
        <div class="flex items-center gap-3">
          <div class="p-2.5 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center">
            <span class="material-symbols-rounded text-2xl">location_on</span>
          </div>
          <div>
            <h3 class="text-base font-bold text-slate-900 flex items-center gap-2">
              Campus GPS &amp; Google Map Setup
            </h3>
            <p class="text-xs text-slate-500 mt-0.5">Define centroid coordinates, geofence radius circle, and device GPS accuracy limit.</p>
          </div>
        </div>
        <button onclick="closeGeofenceModal()" class="p-2 rounded-xl text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition cursor-pointer">
          <span class="material-symbols-rounded text-xl">close</span>
        </button>
      </div>

      <!-- 2-Column Responsive Body -->
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 p-6 overflow-y-auto custom-scrollbar flex-grow">
        
        <!-- Left Column: Parameters & Config -->
        <div class="lg:col-span-5 space-y-4">
          <div class="flex items-center gap-2.5 pb-2 border-b border-slate-100">
            <span class="material-symbols-rounded text-emerald-600 text-lg">tune</span>
            <div>
              <h4 class="font-bold text-slate-900 text-sm">Campus Geofence Config</h4>
              <p class="text-[11px] text-slate-500">Define centroid coordinates and radius for staff punching.</p>
            </div>
          </div>

          <!-- Capture Current Location Button -->
          <button type="button" onclick="captureCurrentGPS()" class="w-full py-2.5 px-4 bg-blue-50 hover:bg-blue-100 text-blue-700 border border-blue-200 rounded-xl font-bold text-xs flex items-center justify-center gap-2 transition cursor-pointer shadow-2xs">
            <span class="material-symbols-rounded text-base">my_location</span>
            <span>Capture My Current Location as Centroid</span>
          </button>

          <form id="geofenceForm" onsubmit="submitGeofenceSetup(event)" class="space-y-3.5 text-xs">
            <div>
              <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1 flex items-center gap-1.5">
                <span class="material-symbols-rounded text-xs text-slate-400">account_balance</span>
                <span>Campus Name <span class="text-rose-500">*</span></span>
              </label>
              <input type="text" id="geoCampusName" name="campus_name" value="Carmel polytechnic College Campus punapra" required class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-900 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
            </div>

            <div>
              <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1 flex items-center gap-1.5">
                <span class="material-symbols-rounded text-xs text-slate-400">north</span>
                <span>Centroid Latitude (°N) <span class="text-rose-500">*</span></span>
              </label>
              <input type="number" step="any" id="geoLat" name="centroid_lat" value="9.43727187" onchange="updateMapFromInputs()" required class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-900 font-mono outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
            </div>

            <div>
              <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1 flex items-center gap-1.5">
                <span class="material-symbols-rounded text-xs text-slate-400">east</span>
                <span>Centroid Longitude (°E) <span class="text-rose-500">*</span></span>
              </label>
              <input type="number" step="any" id="geoLng" name="centroid_lng" value="76.34358649" onchange="updateMapFromInputs()" required class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-900 font-mono outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
              <div>
                <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1 flex items-center gap-1">
                  <span class="material-symbols-rounded text-xs text-slate-400">straighten</span>
                  <span>Radius (Meters) <span class="text-rose-500">*</span></span>
                </label>
                <input type="number" id="geoRadius" name="radius_meters" value="110" min="10" max="5000" oninput="updateCircleRadius()" required class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-900 font-bold outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
              </div>
              <div>
                <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1 flex items-center gap-1">
                  <span class="material-symbols-rounded text-xs text-slate-400">adjust</span>
                  <span>Max Accuracy <span class="text-rose-500">*</span></span>
                </label>
                <input type="number" id="geoAccuracy" name="max_accuracy_meters" value="100" min="5" max="500" required class="w-full bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-900 font-bold outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
              </div>
            </div>

            <div id="geofenceAlert" class="hidden p-3 rounded-xl font-semibold border text-xs"></div>

            <button type="submit" id="geoSaveBtn" class="w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-semibold text-sm transition-all flex items-center justify-center gap-2 cursor-pointer shadow-sm">
              <span class="material-symbols-rounded text-base">save</span>
              <span>Save GPS Location Setup</span>
            </button>
          </form>
        </div>

        <!-- Right Column: Interactive Map Preview & Pinpoint -->
        <div class="lg:col-span-7 flex flex-col space-y-3">
          <div class="flex items-center gap-2.5 pb-2 border-b border-slate-100">
            <span class="material-symbols-rounded text-blue-600 text-lg">public</span>
            <div>
              <h4 class="font-bold text-slate-900 text-sm">Interactive Map Preview &amp; Pinpoint</h4>
              <p class="text-[11px] text-slate-500">Drag the marker or click on map to position campus center.</p>
            </div>
          </div>

          <!-- Instructions & Coords display -->
          <div class="flex items-center justify-between gap-2 p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs flex-wrap">
            <span class="text-slate-600 flex items-center gap-1.5 font-medium">
              <span class="material-symbols-rounded text-sm text-blue-600">touch_app</span>
              <span>Drag pin or click map to move pin</span>
            </span>
            <span id="geoCoordDisplay" class="font-mono font-bold text-blue-700 bg-blue-50 px-2.5 py-1 rounded-lg border border-blue-200">
              9.43727187, 76.34358649
            </span>
          </div>

          <!-- Leaflet Map Canvas Container -->
          <div id="geofenceMapContainer" class="w-full h-80 sm:h-96 rounded-2xl border border-slate-200 overflow-hidden shadow-inner relative z-0 bg-slate-100"></div>

          <!-- Open in Google Maps External Link -->
          <a id="geoBtnGmapsLink" href="https://www.google.com/maps?q=9.43727187,76.34358649" target="_blank" class="w-full py-2.5 px-4 bg-slate-50 hover:bg-slate-100 text-slate-700 border border-slate-200 rounded-xl font-semibold text-xs flex items-center justify-center gap-2 transition cursor-pointer">
            <span class="material-symbols-rounded text-base text-rose-500">map</span>
            <span>Open Coordinates in Google Maps</span>
            <span class="material-symbols-rounded text-xs text-slate-400">open_in_new</span>
          </a>
        </div>
      </div>
    </div>
  </div>

  <!-- ========================================================================= -->
  <!-- MAIN JAVASCRIPT CONTROLLERS -->
  <!-- ========================================================================= -->
