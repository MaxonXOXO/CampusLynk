# CampusLynk Component Library Specification
**Version:** 1.0.0  
**Library Status:** Approved Standard UI Elements & Micro-Interactions

---

## 1. Component Taxonomy & State Matrix

Every component in the CampusLynk library conforms to explicit state handling:
* **Default (Idle)**: Clean, high-contrast, accessible.
* **Hover**: Subtle translation (`-1px`), color shift, soft shadow enhancement.
* **Active / Pressed**: Subtle scale (`0.98`), darker tone.
* **Focus-Visible**: Explicit `2px solid #2563EB` ring with `2px` offset.
* **Disabled**: Opacity `0.5`, cursor `not-allowed`, interactions suppressed.
* **Loading**: Spinner icon replacement, disabled state active.
* **Error**: Red border (`#EF4444`), alert label, error focus ring (`#FEE2E2`).

---

## 2. Component Directory

### 2.1 Buttons (`Button.v1`)
All buttons have a minimum height of `44px` (touch-target compliant), `12px (rounded-xl)` corners, and `14px (text-sm)` medium typography.

| Variant | Tailwind Recipe | Description & States |
|:---|:---|:---|
| **Primary** | `bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white font-medium px-5 py-2.5 rounded-xl shadow-sm hover:shadow transition-all duration-200 flex items-center justify-center gap-2 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2` | Main page actions (e.g. Save, Access Portal, Submit Marks). |
| **With Icon** | Same as Primary with Lucide/Material icon prefix (e.g. Download, Plus). | High-visibility primary action with visual cue. |
| **Secondary** | `bg-white hover:bg-blue-50/50 active:bg-blue-100 text-blue-600 border border-blue-600 font-medium px-5 py-2.5 rounded-xl transition-all duration-200 flex items-center justify-center gap-2 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2` | Alternate actions (e.g. Export, View Details, Cancel). |
| **Tertiary (Ghost)** | `bg-transparent hover:bg-slate-100 text-blue-600 font-medium px-4 py-2 rounded-xl transition-colors duration-200 focus:ring-2 focus:ring-blue-500` | Low-priority inline actions, table row menus, secondary cancel. |
| **Icon Button** | `w-11 h-11 bg-white hover:bg-slate-50 border border-slate-200 text-slate-700 hover:text-slate-900 rounded-xl flex items-center justify-center shadow-sm transition-all focus:ring-2 focus:ring-blue-500` | Square action buttons (+ Add, Search, More Options, Date). |
| **Disabled** | `bg-slate-100 text-slate-400 border border-slate-200 cursor-not-allowed pointer-events-none px-5 py-2.5 rounded-xl` | Inactive or unauthorized action state. |

---

### 2.2 Input Fields (`Input.v1`)
All inputs have height `44px`, `12px (rounded-xl)` radius, `14px (text-sm)` font, and crisp `#E5E7EB` neutral borders.

| Variant | Tailwind Recipe & Structure | Features |
|:---|:---|:---|
| **Default Text** | `w-full px-4 py-2.5 bg-white border border-slate-200 text-slate-900 placeholder:text-slate-400 rounded-xl focus:border-blue-600 focus:ring-2 focus:ring-blue-100 outline-none text-sm transition-all` | Standard single-line input. |
| **Search Input** | Relative container with prefix search icon: `<span class="absolute left-3.5 top-3 text-slate-400">🔍</span><input class="pl-10 ...">` | Global search and table filter search. |
| **Password Input** | Input container with right eye toggle button: `<input type="password" class="pr-10 ..."><button class="absolute right-3.5 top-3">👁️</button>` | Secure password entry with visibility toggle. |
| **Select Dropdown** | `w-full px-4 py-2.5 bg-white border border-slate-200 text-slate-900 rounded-xl focus:border-blue-600 focus:ring-2 focus:ring-blue-100 outline-none text-sm transition-all appearance-none bg-[url('chevron-down.svg')]` | Role selection, semester selector, branch dropdowns. |
| **Date Picker** | Relative container with calendar icon prefix and native date picker format. | Attendance logs, exam dates, timeline filters. |
| **Disabled Input** | `w-full px-4 py-2.5 bg-slate-100 border border-slate-200 text-slate-400 rounded-xl cursor-not-allowed text-sm` | Read-only fields (e.g. Student ID, Auto-calculated SGPA). |

---

### 2.3 Badges & Chips (`Badge.v1`, `Chip.v1`)
Pill-shaped rounded-full indicators with high-contrast text and solid background tints.

```html
<!-- Approved Status Badges -->
<span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">
  <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Active
</span>

<span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-800">
  <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Pending
</span>

<span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
  <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span> Completed
</span>

<span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-700">
  <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span> On Hold
</span>

<span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-rose-100 text-rose-800">
  <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> Cancelled
</span>

<!-- Filter Chips -->
<button class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-medium bg-white border border-slate-200 text-slate-700 hover:border-slate-300 shadow-sm transition-all">
  B.Tech CSE <span class="text-slate-400 hover:text-slate-600">✕</span>
</button>
```

---

### 2.4 Metric Cards (`Card.Metric.v1`)
Designed for clean operational scanning in 4-column dashboard grids.

```html
<div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm hover:shadow-md transition-all duration-200">
  <div class="flex items-center justify-between mb-3">
    <span class="text-sm font-medium text-slate-500">Total Students</span>
    <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
      <span class="icon">👥</span>
    </div>
  </div>
  <div class="text-3xl font-bold text-slate-900 mb-2">2,348</div>
  <div class="flex items-center gap-1.5 text-xs font-semibold text-emerald-600">
    <span>↑ 5.4%</span>
    <span class="text-slate-400 font-normal">vs last month</span>
  </div>
</div>
```

---

### 2.5 Data Table (`DataTable.v1`)
Sticky header row, crisp borders, generous cell padding, and hover elevation.

```html
<div class="w-full bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
  <div class="overflow-x-auto">
    <table class="w-full text-left border-collapse">
      <thead>
        <tr class="bg-slate-50 border-b border-slate-200">
          <th class="py-3.5 px-6 text-xs font-semibold text-slate-500 uppercase tracking-wider">ID</th>
          <th class="py-3.5 px-6 text-xs font-semibold text-slate-500 uppercase tracking-wider">Student Name</th>
          <th class="py-3.5 px-6 text-xs font-semibold text-slate-500 uppercase tracking-wider">Course</th>
          <th class="py-3.5 px-6 text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
          <th class="py-3.5 px-6 text-xs font-semibold text-slate-500 uppercase tracking-wider">Attendance</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-slate-100 text-sm text-slate-800">
        <tr class="hover:bg-blue-50/40 transition-colors">
          <td class="py-4 px-6 font-medium text-slate-600">STU001</td>
          <td class="py-4 px-6 font-semibold text-slate-900">Alex Johnson</td>
          <td class="py-4 px-6 text-slate-600">B.Tech CSE</td>
          <td class="py-4 px-6"><span class="badge-active">Active</span></td>
          <td class="py-4 px-6 font-medium">92%</td>
        </tr>
      </tbody>
    </table>
  </div>
</div>
```

---

### 2.6 Feedback & Alerts (`Alert.v1`)
Clean horizontal alert bars with semantic icons and optional dismiss action.

* **Success Alert**: `bg-emerald-50 border border-emerald-200 text-emerald-900 rounded-xl p-4 flex items-center justify-between text-sm`
* **Warning Alert**: `bg-amber-50 border border-amber-200 text-amber-900 rounded-xl p-4 flex items-center justify-between text-sm`
* **Error Alert**: `bg-rose-50 border border-rose-200 text-rose-900 rounded-xl p-4 flex items-center justify-between text-sm`
* **Info Alert**: `bg-blue-50 border border-blue-200 text-blue-900 rounded-xl p-4 flex items-center justify-between text-sm`

---

### 2.7 Navigation Tabs & Progress Bars (`Tabs.v1`, `Progress.v1`)
* **Underline Tabs**: Active tab: `text-blue-600 font-semibold border-b-2 border-blue-600 py-3 px-4 text-sm`. Inactive tab: `text-slate-500 hover:text-slate-700 font-medium py-3 px-4 text-sm`.
* **Progress Bar**: Outer track `w-full h-2.5 bg-slate-100 rounded-full overflow-hidden`. Inner fill `h-full bg-blue-600 rounded-full transition-all duration-300`.
