
        </div>

        <!-- Bottom Mobile Navigation Bar -->
        <div class="bottom-nav">
            <a href="#" class="nav-link-mobile active" onclick="switchTab(event, 'tab-attendance')">
                <i class="fa-solid fa-clock"></i>
                <span>Attendance</span>
            </a>
            <a href="#" class="nav-link-mobile" onclick="switchTab(event, 'tab-academic')">
                <i class="fa-solid fa-graduation-cap"></i>
                <span>Academic</span>
            </a>
            <a href="#" class="nav-link-mobile" onclick="switchTab(event, 'tab-tasks')">
                <i class="fa-solid fa-list-check"></i>
                <span>Tasks & Tests</span>
            </a>
            <a href="#" class="nav-link-mobile" onclick="switchTab(event, 'tab-remarks')">
                <i class="fa-solid fa-comments"></i>
                <span>Remarks</span>
            </a>
        </div>

    </div>

    <!-- Script for Tab Switching & Theme Toggle -->
    <script>
        function switchTab(e, tabId) {
            e.preventDefault();
            document.querySelectorAll('.tab-pane').forEach(el => el.classList.add('d-none'));
            document.querySelectorAll('.nav-link-mobile').forEach(el => el.classList.remove('active'));

            document.getElementById(tabId).classList.remove('d-none');
            e.currentTarget.classList.add('active');
        }

        function initTheme() {
            const savedTheme = localStorage.getItem('carmel_theme') || 'dark';
            document.documentElement.setAttribute('data-theme', savedTheme);
            updateThemeIcon(savedTheme);
        }

        function toggleTheme() {
            const currentTheme = document.documentElement.getAttribute('data-theme') === 'light' ? 'dark' : 'light';
            document.documentElement.setAttribute('data-theme', currentTheme);
            localStorage.setItem('carmel_theme', currentTheme);
            updateThemeIcon(currentTheme);
        }

        function updateThemeIcon(theme) {
            const icon = document.getElementById('themeIcon');
            if (icon) {
                if (theme === 'light') {
                    icon.className = 'fa-solid fa-moon text-primary';
                } else {
                    icon.className = 'fa-solid fa-sun text-warning';
                }
            }
        }

        document.addEventListener('DOMContentLoaded', initTheme);
    </script>
</body>
</html>
