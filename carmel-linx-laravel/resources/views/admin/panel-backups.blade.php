<div id="panelBackups" class="hidden space-y-6">
          <div class="flex items-center gap-3 bg-white border border-slate-200 p-5 rounded-2xl shadow-sm">
            <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
              <span class="material-symbols-rounded text-2xl">cloud_sync</span>
            </div>
            <div>
              <h3 class="font-bold text-slate-900 text-lg">Google Drive Sync Desk</h3>
              <p class="text-xs text-slate-500 mt-0.5">Compile a complete .sql schema and table rows database dump to save locally and sync immediately to your institutional Google Drive backup folder.</p>
            </div>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white border border-slate-200 rounded-2xl p-6 space-y-4 shadow-sm">
              <div class="flex items-center gap-3 border-b border-slate-100 pb-4">
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                  <span class="material-symbols-rounded text-2xl">cloud</span>
                </div>
                <div>
                  <h4 class="font-bold text-slate-900 text-base">Google Drive Cloud Sync</h4>
                  <p class="text-xs text-slate-500">Automated Institutional Cloud Backup</p>
                </div>
              </div>
              <p class="text-xs text-slate-600 leading-relaxed">
                Connect and sync the entire Carmel Linx database dump directly into the official Google Drive archive with version tracking.
              </p>
              <button onclick="triggerDriveBackup()" id="btnSyncDrive" class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl text-sm transition-all flex items-center justify-center gap-2 shadow-sm cursor-pointer">
                <span class="material-symbols-rounded text-base">sync</span>
                <span>Backup to Google Drive Now</span>
              </button>
              <div id="driveBackupAlert" class="hidden p-3 rounded-xl text-xs font-semibold border"></div>
            </div>

            <div class="bg-white border border-slate-200 rounded-2xl p-6 space-y-4 shadow-sm">
              <div class="flex items-center gap-3 border-b border-slate-100 pb-4">
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                  <span class="material-symbols-rounded text-2xl">download</span>
                </div>
                <div>
                  <h4 class="font-bold text-slate-900 text-base">Direct SQL File Download</h4>
                  <p class="text-xs text-slate-500">Local MySQL Dump (.sql)</p>
                </div>
              </div>
              <p class="text-xs text-slate-600 leading-relaxed">
                Download an immediate plain-text SQL schema and data export for offline retention, emergency recovery, or local test staging.
              </p>
              <a href="/api/system/backup/download" class="w-full py-3 bg-slate-900 hover:bg-slate-800 text-white font-semibold rounded-xl text-sm transition-all flex items-center justify-center gap-2 shadow-sm text-center no-underline">
                <span class="material-symbols-rounded text-base">file_download</span>
                <span>Download SQL File</span>
              </a>
            </div>
          </div>
        </div>
