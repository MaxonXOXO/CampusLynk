import os

path = "resources/views/student_dashboard.blade.php"
with open(path, "r", encoding="utf-8") as f:
    content = f.read()

# Replace any junk characters
content = content.replace("â€”", "-")
content = content.replace("â€¢", "-")

# Fix sizes for Cumulative GPA & Activity Points
old_cgpa_block = """            <div>
              <h3 class="font-black text-slate-400 uppercase tracking-wider mb-1">Cumulative GPA</h3>
              <div class="text-base font-black text-blue-400" id="overallCgpa">-</div>
            </div>"""
new_cgpa_block = """            <div>
              <p class="font-black text-slate-500 uppercase tracking-widest text-[10px]">Cumulative GPA</p>
              <h3 class="font-black text-white text-xl text-blue-400" id="overallCgpa">-</h3>
            </div>"""
content = content.replace(old_cgpa_block, new_cgpa_block)

old_points_block = """            <div>
              <h3 class="font-black text-slate-400 uppercase tracking-wider mb-1">Total Activity Points</h3>
              <div class="text-base font-black text-amber-400" id="overallActivityPoints">-</div>
            </div>"""
new_points_block = """            <div>
              <p class="font-black text-slate-500 uppercase tracking-widest text-[10px]">Total Activity Points</p>
              <h3 class="font-black text-white text-xl text-amber-400" id="overallActivityPoints">-</h3>
            </div>"""
content = content.replace(old_points_block, new_points_block)

# Let's check for any JS assignments to overallCgpa or overallActivityPoints
# If JS is assigning â€” it will be replaced by the top line replace.

# Wait, there's also the Branch and Batch tags at the top
content = content.replace("Branch: <strong class=\"text-slate-200\">{{ session('userBranch', '-') }}</strong>", "Branch: <strong class=\"text-slate-200\">{{ session('userBranch', '-') }}</strong>")

with open(path, "w", encoding="utf-8") as f:
    f.write(content)

print("Fixed student marks dashboard blocks and junk")
