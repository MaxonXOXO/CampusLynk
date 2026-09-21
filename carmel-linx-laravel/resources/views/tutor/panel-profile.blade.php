<div id="panelProfile" class="{{ $activeTab === 'profile' ? '' : 'hidden' }} space-y-6">
          @include('partials.staff_profile_panel', ['hideAuditLog' => true])
        </div>
