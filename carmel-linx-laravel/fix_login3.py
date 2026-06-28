import os
path = "resources/views/login.blade.php"
with open(path, "r", encoding="utf-8") as f:
    content = f.read()

# Fix the password placeholder properly
content = content.replace('placeholder="Ã¢Â€Â¢Ã¢Â€Â¢Ã¢Â€Â¢Ã¢Â€Â¢Ã¢Â€Â¢Ã¢Â€Â¢Ã¢Â€Â¢Ã¢Â€Â¢"', 'placeholder="********"')

# Rebuild regStudentFields
old_fields = """          <!-- Student-Only Fields -->
          <div id="regStudentFields" class="space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <div>
                  <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Register No</label>
                  <input type="text" id="regStudentId" class="w-full px-4 py-3 rounded-xl border border-slate-800 bg-slate-950/80 focus:ring-2 focus:ring-blue-500/20 outline-none text-white font-medium transition-premium" placeholder="REG24EC01">
                </div>
                <div>
                  <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Branch</label>
                  <select id="regStudentBranch" class="w-full px-4 py-3 rounded-xl border border-slate-800 bg-slate-950/80 focus:ring-2 focus:ring-blue-500/20 outline-none text-white font-medium transition-premium appearance-none">
                    <option value="CE">Civil Eng (CE)</option>
                    <option value="EL">Electronics Eng (EL)</option>
                    <option value="ME">Mechanical Eng (ME)</option>
                  </select>
                </div>
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Admission Year</label>
              <input type="number" id="regStudentYear" class="w-full px-4 py-3 rounded-xl border border-slate-800 bg-slate-950/80 focus:ring-2 focus:ring-blue-500/20 outline-none text-white font-medium transition-premium" min="2020" max="2030" value="{{ date('Y') }}">
            </div>
          </div>"""

new_fields = """          <!-- Student-Only Fields -->
          <div id="regStudentFields" class="space-y-4">
              <div class="grid grid-cols-2 gap-4">
                <div>
                  <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Admission Type</label>
                  <select id="regStudentAdmType" class="w-full px-4 py-3 rounded-xl border border-slate-800 bg-slate-950/80 focus:ring-2 focus:ring-blue-500/20 outline-none text-white font-medium transition-premium appearance-none">
                    <option value="Regular">Regular</option>
                    <option value="LET">Lateral Entry (LET)</option>
                  </select>
                </div>
                <div>
                  <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Admission No</label>
                  <input type="text" id="regStudentAdm" class="w-full px-4 py-3 rounded-xl border border-slate-800 bg-slate-950/80 focus:ring-2 focus:ring-blue-500/20 outline-none text-white font-medium transition-premium" placeholder="e.g. 1001">
                </div>
              </div>
              <div class="grid grid-cols-2 gap-4">
                <div>
                  <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Branch</label>
                  <select id="regStudentBranch" class="w-full px-4 py-3 rounded-xl border border-slate-800 bg-slate-950/80 focus:ring-2 focus:ring-blue-500/20 outline-none text-white font-medium transition-premium appearance-none">
                    <option value="CE">Civil Eng (CE)</option>
                    <option value="EL">Electronics Eng (EL)</option>
                    <option value="ME">Mechanical Eng (ME)</option>
                  </select>
                </div>
                <div>
                  <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Admission Year</label>
                  <input type="number" id="regStudentYear" class="w-full px-4 py-3 rounded-xl border border-slate-800 bg-slate-950/80 focus:ring-2 focus:ring-blue-500/20 outline-none text-white font-medium transition-premium" min="2020" max="2030" value="{{ date('Y') }}">
                </div>
              </div>
          </div>"""
content = content.replace(old_fields, new_fields)

# Also fix the JS handleRegister logic
old_js = """      let url = '/register/student';
      if (activeRole === 'student') {
        formData.append('regNo', document.getElementById('regStudentId').value);
        formData.append('branch', document.getElementById('regStudentBranch').value);
        formData.append('admissionYear', document.getElementById('regStudentYear').value);
        
        let photoFile = document.getElementById('regPhoto').files[0];
        if (photoFile) {
          formData.append('photo', photoFile);
        }
      }"""
new_js = """      let url = '/register/student';
      if (activeRole === 'student') {
        formData.append('admNo', document.getElementById('regStudentAdm').value);
        formData.append('branch', document.getElementById('regStudentBranch').value);
        formData.append('admissionYear', document.getElementById('regStudentYear').value);
        formData.append('admissionType', document.getElementById('regStudentAdmType').value);
        
        let photoFile = document.getElementById('regPhoto').files[0];
        if (photoFile) {
          formData.append('photo', photoFile);
        }
      }"""
content = content.replace(old_js, new_js)

# Fix the role buttons text size to text-base (like Access Portal button)
old_student_btn = 'class="flex-1 py-2.5 rounded-xl font-bold text-sm text-slate-400 hover:text-slate-200 hover:bg-slate-900/30 transition-premium flex items-center justify-center gap-1.5 cursor-pointer"'
new_student_btn = 'class="flex-1 py-2.5 rounded-xl font-bold text-base text-slate-400 hover:text-slate-200 hover:bg-slate-900/30 transition-premium flex items-center justify-center gap-1.5 cursor-pointer"'
old_staff_btn = 'class="flex-1 py-2.5 rounded-xl font-bold text-sm text-white bg-gradient-to-r from-blue-500 to-sky-600 shadow-md shadow-blue-500/15 transition-premium flex items-center justify-center gap-1.5 cursor-pointer"'
new_staff_btn = 'class="flex-1 py-2.5 rounded-xl font-bold text-base text-white bg-gradient-to-r from-blue-500 to-sky-600 shadow-md shadow-blue-500/15 transition-premium flex items-center justify-center gap-1.5 cursor-pointer"'
content = content.replace(old_student_btn, new_student_btn)
content = content.replace(old_staff_btn, new_staff_btn)

# JS toggle logic update for text-base
content = content.replace('text-sm text-white bg-gradient-to-r from-blue-500', 'text-base text-white bg-gradient-to-r from-blue-500')
content = content.replace('text-[10px] text-white bg-gradient-to-r from-blue-500', 'text-base text-white bg-gradient-to-r from-blue-500')
content = content.replace('text-[10px] text-slate-400', 'text-base text-slate-400')
content = content.replace('text-sm text-slate-400', 'text-base text-slate-400')

with open(path, "w", encoding="utf-8") as f:
    f.write(content)
print("Updated login blade")
