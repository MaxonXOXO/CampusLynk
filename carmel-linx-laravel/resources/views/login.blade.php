<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Carmel Linx - Exam Portal</title>
  <!-- Tailwind CSS CDN -->
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <!-- Google Icons -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">
  
  <style>
    .transition-premium {
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .slide-up {
      animation: slideUp 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
    @keyframes slideUp {
      from { transform: translateY(20px); opacity: 0; }
      to { transform: translateY(0); opacity: 1; }
    }
    .loader-spinner {
      width: 18px;
      height: 18px;
      border: 2px solid rgba(255,255,255,0.3);
      border-radius: 50%;
      border-top-color: white;
      animation: spin 0.8s linear infinite;
    }
    @keyframes spin {
      to { transform: rotate(360deg); }
    }
  </style>
</head>
<body class="bg-gradient-to-tr from-slate-950 via-[#0B1329] to-[#162547] min-h-screen flex items-center justify-center p-4">



  <!-- Main Container -->
  <div class="w-full max-w-lg bg-slate-900/80 backdrop-blur-xl shadow-2xl rounded-3xl overflow-hidden border border-slate-800/80 slide-up p-6 md:p-8 text-slate-100">
    
    <!-- Branding Header -->
    <div class="text-center mb-8">
      <div class="inline-flex items-center justify-center bg-gradient-to-br from-blue-500 to-sky-600 text-white w-16 h-16 rounded-2xl shadow-lg shadow-blue-500/25 font-black text-3xl mb-3 tracking-wider select-none">CL</div>
      <h1 class="text-3xl font-black text-white tracking-tight">Carmel Linx</h1>
      <p class="text-slate-400 font-semibold text-sm mt-1">Outcome-Based Education Exam Portal</p>
    </div>

    <!-- Screen Toggle: Login vs Register -->
    <div id="authGate">
      
      <!-- Login Section -->
      <div id="loginSection">
        <!-- Login Role Tabs -->
        <div class="flex bg-slate-950/60 p-1.5 rounded-2xl mb-6 border border-slate-800/60">
          <button id="tabStudent" onclick="toggleRoleTab('student')" class="flex-1 py-2.5 rounded-xl font-bold text-sm text-white bg-gradient-to-r from-blue-500 to-sky-600 shadow-md shadow-blue-500/15 transition-premium flex items-center justify-center gap-1.5 cursor-pointer">
            <span class="material-symbols-rounded text-lg">school</span> Student
          </button>
          <button id="tabStaff" onclick="toggleRoleTab('staff')" class="flex-1 py-2.5 rounded-xl font-bold text-sm text-slate-400 hover:text-slate-200 hover:bg-slate-900/30 transition-premium flex items-center justify-center gap-1.5 cursor-pointer">
            <span class="material-symbols-rounded text-lg">badge</span> Staff Portal
          </button>
        </div>

        <form onsubmit="handleLogin(event)" class="space-y-4">
          <!-- Student Login Fields -->
          <div id="studentLoginFields" class="space-y-4">
            <div>
              <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Register / Admission Number</label>
              <input type="text" id="loginUserId" class="w-full px-4 py-3 rounded-xl border border-slate-800 bg-slate-950/80 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none text-white font-medium transition-premium" placeholder="e.g. REG24EC01">
            </div>
          </div>

          <!-- Staff Login Fields -->
          <div id="staffLoginFields" class="space-y-4 hidden">
            <div>
              <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Mobile Number (ID)</label>
              <input type="text" id="loginMobileId" class="w-full px-4 py-3 rounded-xl border border-slate-800 bg-slate-950/80 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none text-white font-medium transition-premium" placeholder="e.g. 9845000001">
            </div>
          </div>

          <div>
            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Password</label>
            <input type="password" id="loginPassword" class="w-full px-4 py-3 rounded-xl border border-slate-800 bg-slate-950/80 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none text-white font-medium transition-premium" placeholder="••••••••">
          </div>

          <!-- Alert Container -->
          <div id="loginAlert" class="hidden p-4 rounded-xl text-sm font-semibold"></div>

          <!-- Submit -->
          <button type="submit" class="w-full py-3.5 bg-gradient-to-r from-blue-500 to-sky-600 hover:from-blue-600 hover:to-sky-700 text-white rounded-xl font-bold shadow-lg shadow-blue-500/20 transition-premium flex items-center justify-center gap-2 cursor-pointer">
            <span id="loginBtnText">Access Portal</span>
            <div id="loginSpinner" class="loader-spinner border-t-white hidden"></div>
          </button>
        </form>

        <div class="text-center mt-6 space-y-2">
          <p class="text-slate-400 text-sm">Don't have an account?</p>
          <div class="flex justify-center gap-4 text-xs font-bold">
            <a href="#" onclick="showRegister('student')" class="text-blue-400 hover:text-blue-300">Register as Student</a>
            <span class="text-slate-700">|</span>
            <a href="#" onclick="showRegister('staff')" class="text-blue-400 hover:text-blue-300">Register as Staff</a>
          </div>
        </div>
      </div>

      <!-- Registration Section (Student & Staff) -->
      <div id="registerSection" class="hidden">
        <h2 id="registerTitle" class="text-xl font-extrabold text-white mb-6 text-center border-b border-slate-800 pb-3">Register Student</h2>
        
        <form id="registerForm" onsubmit="handleRegistration(event)" class="space-y-4 max-h-[420px] overflow-y-auto pr-2">
          <!-- Shared Fields -->
          <div>
            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Full Name</label>
            <input type="text" id="regName" required class="w-full px-4 py-3 rounded-xl border border-slate-800 bg-slate-950/80 focus:ring-2 focus:ring-blue-500/20 outline-none text-white font-medium transition-premium" placeholder="Enter Full Name">
          </div>
          <div>
            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Email Address</label>
            <input type="email" id="regEmail" required class="w-full px-4 py-3 rounded-xl border border-slate-800 bg-slate-950/80 focus:ring-2 focus:ring-blue-500/20 outline-none text-white font-medium transition-premium" placeholder="name@carmelpoly.edu.in">
          </div>

          <!-- Student-Only Fields -->
          <div id="regStudentFields" class="space-y-4">
            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Register No</label>
                <input type="text" id="regStudentId" class="w-full px-4 py-3 rounded-xl border border-slate-800 bg-slate-950/80 focus:ring-2 focus:ring-blue-500/20 outline-none text-white font-medium transition-premium" placeholder="REG24EC01">
              </div>
              <div>
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Admission No</label>
                <input type="text" id="regStudentAdm" class="w-full px-4 py-3 rounded-xl border border-slate-800 bg-slate-950/80 focus:ring-2 focus:ring-blue-500/20 outline-none text-white font-medium transition-premium" placeholder="ADM24EC01">
              </div>
            </div>
            
            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Branch</label>
                <select id="regStudentBranch" class="w-full px-4 py-3 rounded-xl border border-slate-800 bg-slate-950 text-white focus:ring-2 focus:ring-blue-500/20 outline-none font-medium transition-premium">
                  <option value="EL">Electronics Engineering (EL)</option>
                  <option value="ME">Mechanical Engineering (ME)</option>
                  <option value="CE">Civil Engineering (CE)</option>
                  <option value="EEE">Electrical & Electronics Engineering (EEE)</option>
                  <option value="CT">Computer Engineering (CT)</option>
                  <option value="AU">Automobile Engineering (AU)</option>
                </select>
              </div>
              <div>
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Admission Year</label>
                <input type="number" id="regStudentYear" class="w-full px-4 py-3 rounded-xl border border-slate-800 bg-slate-950/80 focus:ring-2 focus:ring-blue-500/20 outline-none text-white font-medium transition-premium" placeholder="2024" value="2024">
              </div>
            </div>

            <div>
              <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Current Semester</label>
              <select id="regStudentSem" class="w-full px-4 py-3 rounded-xl border border-slate-800 bg-slate-950 text-white focus:ring-2 focus:ring-blue-500/20 outline-none font-medium transition-premium">
                <option value="S1">S1</option>
                <option value="S2">S2</option>
                <option value="S3" selected>S3</option>
                <option value="S4">S4</option>
                <option value="S5">S5</option>
                <option value="S6">S6</option>
              </select>
            </div>
          </div>

          <!-- Staff-Only Fields -->
          <div id="regStaffFields" class="space-y-4 hidden">
            <div>
              <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Mobile No (Login ID)</label>
              <input type="text" id="regStaffMobile" class="w-full px-4 py-3 rounded-xl border border-slate-800 bg-slate-955/80 focus:ring-2 focus:ring-blue-500/20 outline-none text-white font-medium transition-premium" placeholder="10-digit Mobile Number">
            </div>
            
            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Branch</label>
                <select id="regStaffBranch" class="w-full px-4 py-3 rounded-xl border border-slate-800 bg-slate-950 text-white focus:ring-2 focus:ring-blue-500/20 outline-none font-medium transition-premium">
                  <option value="EL">Electronics Engineering (EL)</option>
                  <option value="ME">Mechanical Engineering (ME)</option>
                  <option value="CE">Civil Engineering (CE)</option>
                  <option value="EEE">Electrical & Electronics Engineering (EEE)</option>
                  <option value="CT">Computer Engineering (CT)</option>
                  <option value="AU">Automobile Engineering (AU)</option>
                  <option value="Admin">Administration</option>
                </select>
              </div>
              <div>
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Designation</label>
                <select id="regStaffDesig" class="w-full px-4 py-3 rounded-xl border border-slate-800 bg-slate-950 text-white focus:ring-2 focus:ring-blue-500/20 outline-none font-medium transition-premium">
                  <option value="HOD">Head of the Department (HOD)</option>
                  <option value="Faculty">Faculty</option>
                  <option value="Demonstrator">Demonstrator</option>
                  <option value="Trade_Instructor">Trade Instructor</option>
                  <option value="Workshop_Superintendent">Workshop Superintendent</option>
                  <option value="Principal">Principal</option>
                </select>
              </div>
            </div>
          </div>

          <!-- Password & Photo -->
          <div>
            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Password</label>
            <input type="password" id="regPassword" required class="w-full px-4 py-3 rounded-xl border border-slate-800 bg-slate-950/80 focus:ring-2 focus:ring-blue-500/20 outline-none text-white font-medium transition-premium" placeholder="••••••••">
          </div>

          <div>
            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Passport Photo</label>
            <input type="file" id="regPhoto" accept="image/*" class="w-full text-sm text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-500/10 file:text-blue-400 hover:file:bg-blue-500/20 transition-premium">
          </div>

          <!-- Alert Container -->
          <div id="regAlert" class="hidden p-4 rounded-xl text-sm font-semibold"></div>

          <!-- Submit & Back -->
          <div class="flex gap-3 pt-2">
            <button type="button" onclick="showLogin()" class="flex-1 py-3 border border-slate-800 hover:bg-slate-800/60 rounded-xl font-bold transition-premium text-slate-300 text-sm cursor-pointer">
              Back to Login
            </button>
            <button type="submit" class="flex-1 py-3 bg-gradient-to-r from-blue-500 to-sky-600 hover:from-blue-600 hover:to-sky-700 text-white rounded-xl font-bold shadow-lg transition-premium flex items-center justify-center gap-2 text-sm cursor-pointer">
              <span id="regBtnText">Register</span>
              <div id="regSpinner" class="loader-spinner border-t-white hidden"></div>
            </button>
          </div>
        </form>
      </div>

    </div>

  </div>

  <script>
    let activeRole = "student";

    function toggleRoleTab(role) {
      activeRole = role;
      const tabStudent = document.getElementById('tabStudent');
      const tabStaff = document.getElementById('tabStaff');
      const sFields = document.getElementById('studentLoginFields');
      const fFields = document.getElementById('staffLoginFields');

      if (role === 'student') {
        tabStudent.className = "flex-1 py-2.5 rounded-xl font-bold text-sm text-white bg-gradient-to-r from-blue-500 to-sky-600 shadow-md shadow-blue-500/15 transition-premium flex items-center justify-center gap-1.5 cursor-pointer";
        tabStaff.className = "flex-1 py-2.5 rounded-xl font-bold text-sm text-slate-400 hover:text-slate-200 hover:bg-slate-900/30 transition-premium flex items-center justify-center gap-1.5 cursor-pointer";
        sFields.classList.remove('hidden');
        fFields.classList.add('hidden');
      } else {
        tabStaff.className = "flex-1 py-2.5 rounded-xl font-bold text-sm text-white bg-gradient-to-r from-blue-500 to-sky-600 shadow-md shadow-blue-500/15 transition-premium flex items-center justify-center gap-1.5 cursor-pointer";
        tabStudent.className = "flex-1 py-2.5 rounded-xl font-bold text-sm text-slate-400 hover:text-slate-200 hover:bg-slate-900/30 transition-premium flex items-center justify-center gap-1.5 cursor-pointer";
        fFields.classList.remove('hidden');
        sFields.classList.add('hidden');
      }
    }

    function showRegister(type) {
      document.getElementById('loginSection').classList.add('hidden');
      document.getElementById('registerSection').classList.remove('hidden');
      const rTitle = document.getElementById('registerTitle');
      const regS = document.getElementById('regStudentFields');
      const regF = document.getElementById('regStaffFields');
      
      document.getElementById('registerForm').reset();
      document.getElementById('regAlert').classList.add('hidden');

      if (type === 'student') {
        activeRole = "student";
        rTitle.innerText = "Register Student Profile";
        regS.classList.remove('hidden');
        regF.classList.add('hidden');
      } else {
        activeRole = "staff";
        rTitle.innerText = "Register Academic Staff";
        regF.classList.remove('hidden');
        regS.classList.add('hidden');
      }
    }

    function showLogin() {
      document.getElementById('registerSection').classList.add('hidden');
      document.getElementById('loginSection').classList.remove('hidden');
      toggleRoleTab(activeRole);
    }

    // Helper: get standard Laravel CSRF token header
    function getHeaders() {
      return {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      };
    }

    function handleLogin(e) {
      e.preventDefault();
      
      const loginAlert = document.getElementById('loginAlert');
      const spinner = document.getElementById('loginSpinner');
      const btnText = document.getElementById('loginBtnText');
      
      loginAlert.classList.add('hidden');
      spinner.classList.remove('hidden');
      btnText.innerText = "Verifying...";
      
      let userId = activeRole === 'student' 
        ? document.getElementById('loginUserId').value.trim()
        : document.getElementById('loginMobileId').value.trim();
      let password = document.getElementById('loginPassword').value.trim();
      
      if (!userId || !password) {
        showError(loginAlert, spinner, btnText, "Please fill in all credentials.");
        return;
      }

      fetch('/login', {
        method: 'POST',
        headers: getHeaders(),
        body: JSON.stringify({ userId, password, roleType: activeRole })
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === "SUCCESS") {
          loginAlert.className = "p-4 rounded-xl text-sm font-semibold bg-green-950/40 text-green-400 border border-green-900/60 block";
          loginAlert.innerText = "Access granted! Redirecting...";
          window.location.href = data.route;
        } else {
          showError(loginAlert, spinner, btnText, data.message);
        }
      })
      .catch(err => {
        showError(loginAlert, spinner, btnText, "Server communication failed.");
      });
    }

    function handleRegistration(e) {
      e.preventDefault();
      
      const regAlert = document.getElementById('regAlert');
      const spinner = document.getElementById('regSpinner');
      const btnText = document.getElementById('regBtnText');
      
      regAlert.classList.add('hidden');
      spinner.classList.remove('hidden');
      btnText.innerText = "Submitting...";

      const formData = new FormData();
      formData.append('name', document.getElementById('regName').value);
      formData.append('email', document.getElementById('regEmail').value);
      formData.append('password', document.getElementById('regPassword').value);
      
      const photoFile = document.getElementById('regPhoto').files[0];
      if (photoFile) {
        formData.append('photo', photoFile);
      }

      let url = '/register/student';
      if (activeRole === 'student') {
        formData.append('regNo', document.getElementById('regStudentId').value);
        formData.append('admNo', document.getElementById('regStudentAdm').value);
        formData.append('branch', document.getElementById('regStudentBranch').value);
        formData.append('admissionYear', document.getElementById('regStudentYear').value);
        formData.append('admissionType', 'Regular');
      } else {
        url = '/register/staff';
        formData.append('mobileNo', document.getElementById('regStaffMobile').value);
        formData.append('branch', document.getElementById('regStaffBranch').value);
        formData.append('designation', document.getElementById('regStaffDesig').value);
      }

      fetch(url, {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: formData
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === "SUCCESS") {
          regAlert.className = "p-4 rounded-xl text-sm font-semibold bg-green-950/40 text-green-400 border border-green-900/60 block";
          regAlert.innerText = data.message;
          spinner.classList.add('hidden');
          btnText.innerText = "Register";
          setTimeout(() => showLogin(), 2000);
        } else {
          showRegError(regAlert, spinner, btnText, data.message);
        }
      })
      .catch(err => {
        showRegError(regAlert, spinner, btnText, "Registration request failed.");
      });
    }

    function showError(alertEl, spinner, btnText, msg) {
      alertEl.className = "p-4 rounded-xl text-sm font-semibold bg-red-950/40 text-red-400 border border-red-900/60 block";
      alertEl.innerText = msg;
      spinner.classList.add('hidden');
      btnText.innerText = "Access Portal";
    }

    function showRegError(alertEl, spinner, btnText, msg) {
      alertEl.className = "p-4 rounded-xl text-sm font-semibold bg-red-950/40 text-red-400 border border-red-900/60 block";
      alertEl.innerText = msg;
      spinner.classList.add('hidden');
      btnText.innerText = "Register";
    }
  </script>
</body>
</html>
