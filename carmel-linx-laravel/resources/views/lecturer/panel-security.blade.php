<div id="panelSecurity" class="{{ $isSecurityPanel ? '' : 'hidden' }} space-y-6 animate-fade-in">
        @include('partials.staff_profile_panel', ['hideAuditLog' => true])
      </div>
