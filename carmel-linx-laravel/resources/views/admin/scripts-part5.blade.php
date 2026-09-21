
          geofenceMap.on('click', function(e) {
            geofenceMarker.setLatLng(e.latlng);
            updateGeofenceCoordinates(e.latlng.lat, e.latlng.lng);
          });
        } else {
          geofenceMap.setView([lat, lng], 16);
          geofenceMarker.setLatLng([lat, lng]);
          geofenceCircle.setLatLng([lat, lng]);
          geofenceCircle.setRadius(radius);
          geofenceMap.invalidateSize();
        }
      }, 150);
    }

    function updateGeofenceCoordinates(lat, lng) {
      const formattedLat = parseFloat(lat).toFixed(8);
      const formattedLng = parseFloat(lng).toFixed(8);

      document.getElementById('geoLat').value = formattedLat;
      document.getElementById('geoLng').value = formattedLng;
      document.getElementById('geoCoordDisplay').innerText = `${formattedLat}, ${formattedLng}`;

      if (geofenceCircle) geofenceCircle.setLatLng([lat, lng]);

      const gmapsBtn = document.getElementById('geoBtnGmapsLink');
      if (gmapsBtn) {
        gmapsBtn.href = `https://www.google.com/maps?q=${formattedLat},${formattedLng}`;
      }
    }

    function updateMapFromInputs() {
      const lat = parseFloat(document.getElementById('geoLat').value);
      const lng = parseFloat(document.getElementById('geoLng').value);

      if (!isNaN(lat) && !isNaN(lng)) {
        if (geofenceMarker) geofenceMarker.setLatLng([lat, lng]);
        if (geofenceCircle) geofenceCircle.setLatLng([lat, lng]);
        if (geofenceMap) geofenceMap.panTo([lat, lng]);
        document.getElementById('geoCoordDisplay').innerText = `${lat.toFixed(8)}, ${lng.toFixed(8)}`;
        document.getElementById('geoBtnGmapsLink').href = `https://www.google.com/maps?q=${lat},${lng}`;
      }
    }

    function updateCircleRadius() {
      const rad = parseInt(document.getElementById('geoRadius').value);
      if (geofenceCircle && !isNaN(rad) && rad > 0) {
        geofenceCircle.setRadius(rad);
      }
    }

    function captureCurrentGPS() {
      if ("geolocation" in navigator) {
        navigator.geolocation.getCurrentPosition((pos) => {
          const lat = pos.coords.latitude;
          const lng = pos.coords.longitude;
          updateGeofenceCoordinates(lat, lng);
          if (geofenceMap) geofenceMap.setView([lat, lng], 17);
        }, (err) => {
          alert("Unable to fetch current GPS coordinates. Please grant location access.");
        }, { enableHighAccuracy: true });
      } else {
        alert("Geolocation is not supported by your browser.");
      }
    }

    async function submitGeofenceSetup(e) {
      e.preventDefault();
      const campus_name = document.getElementById('geoCampusName').value;
      const centroid_lat = document.getElementById('geoLat').value;
      const centroid_lng = document.getElementById('geoLng').value;
      const radius_meters = document.getElementById('geoRadius').value;
      const max_accuracy_meters = document.getElementById('geoAccuracy').value;
      const alertEl = document.getElementById('geofenceAlert');
      const saveBtn = document.getElementById('geoSaveBtn');

      saveBtn.disabled = true;
      saveBtn.innerHTML = `<span class="material-symbols-rounded animate-spin text-base">sync</span><span>Saving...</span>`;

      try {
        const res = await fetch('/sf-attendance/geofence-setup', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
          body: JSON.stringify({ campus_name, centroid_lat, centroid_lng, radius_meters, max_accuracy_meters })
        });
        const data = await res.json();
        alertEl.classList.remove('hidden');
        if (data.status === 'SUCCESS' || res.ok) {
          alertEl.className = 'p-3 rounded-xl font-semibold border text-xs bg-emerald-50 text-emerald-700 border-emerald-200';
          alertEl.innerText = data.message || 'Campus GPS Geofence saved successfully!';
          setTimeout(() => closeGeofenceModal(), 1500);
        } else {
          alertEl.className = 'p-3 rounded-xl font-semibold border text-xs bg-rose-50 text-rose-700 border-rose-200';
          alertEl.innerText = data.message || 'Failed to update geofence.';
        }
      } catch (err) {
        alertEl.classList.remove('hidden');
        alertEl.className = 'p-3 rounded-xl font-semibold border text-xs bg-rose-50 text-rose-700 border-rose-200';
        alertEl.innerText = 'Failed to update geofence.';
      } finally {
        saveBtn.disabled = false;
        saveBtn.innerHTML = `<span class="material-symbols-rounded text-base">save</span><span>Save GPS Location Setup</span>`;
      }
    }

    async function triggerDriveBackup() {
      const btn = document.getElementById('btnSyncDrive');
      const alertEl = document.getElementById('driveBackupAlert');
      btn.disabled = true;
      btn.innerHTML = `<div class="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></div><span>Backing up to Google Drive...</span>`;

      try {
        const res = await fetch('/api/system/backup/google-drive', {
          method: 'POST',
          headers: { 'X-CSRF-TOKEN': csrfToken }
        });
        const data = await res.json();
        alertEl.classList.remove('hidden');
        if (data.status === 'SUCCESS') {
          alertEl.className = 'p-3 rounded-xl text-xs font-semibold border bg-emerald-50 text-emerald-700 border-emerald-200';
          alertEl.innerText = 'Google Drive database backup completed successfully!';
        } else {
          alertEl.className = 'p-3 rounded-xl text-xs font-semibold border bg-amber-50 text-amber-700 border-amber-200';
          alertEl.innerText = data.message || 'Backup synced with warnings.';
        }
      } catch (err) {
        alertEl.classList.remove('hidden');
        alertEl.className = 'p-3 rounded-xl text-xs font-semibold border bg-rose-50 text-rose-700 border-rose-200';
        alertEl.innerText = 'Network error during Google Drive backup.';
      } finally {
        btn.disabled = false;
        btn.innerHTML = `<span class="material-symbols-rounded text-base">sync</span><span>Backup to Google Drive Now</span>`;
      }
    }

    async function loadSystemSettings() {
      try {
        const res = await fetch('/api/admin/settings');
        const data = await res.json();
        if (data.status === 'SUCCESS' && data.settings) {
          const aiCheckbox = document.getElementById('settingAiEnabled');
          if (aiCheckbox) {
            aiCheckbox.checked = !!data.settings.ai_generation_enabled;
          }
        }
      } catch (err) {
        console.error('Failed to load system settings:', err);
      }
    }

    async function saveSystemSettings() {
      const checkbox = document.getElementById('settingAiEnabled');
      if (!checkbox) return;
      const enabled = checkbox.checked;
      const alertEl = document.getElementById('settingsSaveAlert');
      try {
        const res = await fetch('/api/admin/settings/ai-toggle', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
          body: JSON.stringify({ ai_generation_enabled: enabled, ai_enabled: enabled })
        });
        const data = await res.json();
        alertEl.classList.remove('hidden');
        if (data.status === 'SUCCESS' || res.ok) {
          alertEl.className = 'p-4 rounded-xl font-semibold border text-sm bg-emerald-50 text-emerald-700 border-emerald-200';
          alertEl.innerText = `Gemini AI Integration Engine ${enabled ? 'Enabled' : 'Disabled (Offline Mode Active)'}.`;
          setTimeout(() => alertEl.classList.add('hidden'), 3500);
        } else {
          alertEl.className = 'p-4 rounded-xl font-semibold border text-sm bg-rose-50 text-rose-700 border-rose-200';
          alertEl.innerText = data.message || 'Failed to update system setting.';
          checkbox.checked = !enabled; // Revert checkbox state
        }
      } catch (err) {
        alertEl.classList.remove('hidden');
        alertEl.className = 'p-4 rounded-xl font-semibold border text-sm bg-rose-50 text-rose-700 border-rose-200';
        alertEl.innerText = 'Failed to update system setting.';
        checkbox.checked = !enabled; // Revert checkbox state
      }
    }

    requestAnimationFrame(function() {
      document.body.classList.remove('sidebar-preload');
    });
  </script>
</body>
</html>
