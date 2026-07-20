<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mid-Semester Feedback Survey — Carmel Linx</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
  <style>
    body { font-family: 'Inter', system-ui, sans-serif; }
    .transition-premium { transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1); }
    .rating-btn { transition: all 0.2s ease; cursor: pointer; }
    .rating-btn:hover { transform: scale(1.02); }
    .rating-btn.selected { transform: scale(1.04); box-shadow: 0 0 0 2px rgba(255,255,255,0.15); }
    @keyframes fadeUp {
      from { opacity: 0; transform: translateY(16px); }
      to   { opacity: 1; transform: translateY(0); }
    }
    .fade-up { animation: fadeUp 0.5s ease both; }
  </style>
</head>
<body class="bg-slate-900 text-slate-100 min-h-screen flex items-center justify-center p-4">

  <div class="w-full max-w-2xl fade-up my-6">

    <!-- Header -->
    <div class="bg-slate-950/70 border border-slate-800/80 rounded-3xl p-6 mb-6">
      <div class="flex items-center gap-4 mb-4">
        <div class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-amber-500/10 border border-amber-500/20">
          <span class="material-symbols-rounded text-amber-400 text-2xl">rate_review</span>
        </div>
        <div>
          <h1 class="text-xl font-black text-white tracking-tight">Mid-Semester Feedback Survey</h1>
          <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider">SAR Criterion 2 Evaluation</p>
        </div>
      </div>
      
      <p class="text-xs text-slate-400 leading-relaxed border-t border-slate-800/60 pt-3">
        <strong>Purpose:</strong> This anonymous survey captures your feedback on course delivery and mapping to target educational outcomes. Your honest inputs help us improve teaching quality and take early corrective actions. Identity remains strictly confidential.
      </p>

      <div class="grid grid-cols-2 md:grid-cols-3 gap-3 mt-4 text-xs font-semibold bg-slate-900/60 p-4 rounded-xl border border-slate-800/60">
        <div><span class="text-slate-500 block uppercase text-[10px]">Course & Code</span><span class="text-slate-200 font-bold font-mono">{{ $survey->subject_code }} — {{ $survey->subject_name }}</span></div>
        <div><span class="text-slate-500 block uppercase text-[10px]">Faculty Member</span><span class="text-slate-200 font-bold">{{ $survey->faculty_name ?? 'Faculty Member' }}</span></div>
        <div><span class="text-slate-500 block uppercase text-[10px]">Semester / Batch</span><span class="text-teal-400 font-bold">Sem {{ $survey->semester }} / {{ $survey->batch_year ?? 'N/A' }}</span></div>
      </div>
    </div>

    <!-- Form -->
    <form id="surveyForm" class="space-y-4" onsubmit="submitSurvey(event)">
      <input type="hidden" id="survey_id_val" value="{{ $survey->id }}">

      <div class="px-2 py-1 text-xs text-amber-400 font-bold uppercase tracking-wider">Section 1: Course Delivery & Assessment (Required)</div>

      @php
        $defaultQuestions = [
          'q5'  => ['icon' => 'co_present',     'label' => '1. Course Outcomes Communication', 'desc' => 'The teacher clearly communicates the Course Outcomes (COs) and learning goals at the start of new topics.'],
          'q6'  => ['icon' => 'speed',          'label' => '2. Syllabus Delivery Pace',        'desc' => 'The pace, speed, and coverage of the syllabus completed so far is appropriate.'],
          'q7'  => ['icon' => 'psychology',     'label' => '3. Concept Clarity & Application', 'desc' => 'The teacher explains complex concepts clearly and links classroom theory to real-world industrial or field applications.'],
          'q8'  => ['icon' => 'slideshow',      'label' => '4. Effectiveness of ICT/PPT Tools','desc' => 'The use of teaching tools, animations, PPTs, model demonstrations, or ICT tools is effective.'],
          'q9'  => ['icon' => 'question_answer', 'label' => '5. Doubt Clearing & Interaction',  'desc' => 'The teacher encourages student questions, manages classroom discussions well, and clears doubts patiently.'],
          'q10' => ['icon' => 'quiz',           'label' => '6. Test & Assignment Relevance',  'desc' => 'Internal assessment test questions and assignments match the topics taught in class.'],
          'q11' => ['icon' => 'assignment_turned_in', 'label' => '7. Fairness in Evaluation',  'desc' => 'Evaluation of mid-semester tests or submissions is fair, timely, and transparent.'],
          'q12' => ['icon' => 'support_agent',  'label' => '8. Guidance for Slow Learners',   'desc' => 'The teacher provides extra guidance, remedial tips, or support to slow learners.'],
        ];

        $custom = json_decode($survey->custom_questions, true) ?: [];
        $questions = [];
        foreach ($defaultQuestions as $key => $val) {
            $questions[$key] = $val;
            if (isset($custom[$key]) && !empty(trim($custom[$key]))) {
                $questions[$key]['desc'] = trim($custom[$key]);
            }
        }
      @endphp

      @foreach($questions as $key => $q)
      <div class="bg-slate-950/50 border border-slate-800/60 rounded-2xl p-5 hover:border-slate-700/80 transition-premium">
        <div class="flex items-start gap-3 mb-4">
          <span class="material-symbols-rounded text-amber-500 text-xl mt-0.5">{{ $q['icon'] }}</span>
          <div>
            <h3 class="font-bold text-slate-100 text-base">{{ $q['label'] }}</h3>
            <p class="text-sm text-slate-350 mt-1.5 leading-relaxed">{{ $q['desc'] }}</p>
          </div>
        </div>
        <div class="grid grid-cols-3 gap-3" id="rg_{{ $key }}">
          <div class="rating-btn border rounded-xl p-3 text-center border-rose-500/30 bg-rose-500/10" id="btn_{{ $key }}_1" onclick="selectRating('{{ $key }}', 1)">
            <div class="text-lg font-black text-slate-200">1</div>
            <div class="text-[10px] font-bold text-rose-400 mt-0.5">Poor</div>
          </div>
          <div class="rating-btn border rounded-xl p-3 text-center border-amber-500/30 bg-amber-500/10" id="btn_{{ $key }}_2" onclick="selectRating('{{ $key }}', 2)">
            <div class="text-lg font-black text-slate-200">2</div>
            <div class="text-[10px] font-bold text-amber-400 mt-0.5">Average</div>
          </div>
          <div class="rating-btn border rounded-xl p-3 text-center border-teal-500/30 bg-teal-500/10" id="btn_{{ $key }}_3" onclick="selectRating('{{ $key }}', 3)">
            <div class="text-lg font-black text-slate-200">3</div>
            <div class="text-[10px] font-bold text-teal-400 mt-0.5">Good</div>
          </div>
        </div>
      </div>
      @endforeach

      <!-- Section 3: Branch-Specific Application & Labs -->
      <div class="px-2 py-1 pt-3 text-xs text-amber-400 font-bold uppercase tracking-wider">Section 2: Branch-Specific Lab / Practical Evaluation (Optional)</div>
      
      @php
        $branchGroup = 'Other';
        $bLower = strtolower($branch);
        if (str_contains($bLower, 'computer') || str_contains($bLower, 'it') || str_contains($bLower, 'ct')) {
            $branchGroup = 'Computer';
            $branchLabel = '9. Laboratory / Practical Session Evaluation';
            $branchDesc = 'The instructor effectively demonstrates coding logic, syntax debugging, and software tool operations during laboratory hours.';
        } elseif (str_contains($bLower, 'mechanical') || str_contains($bLower, 'auto') || str_contains($bLower, 'me') || str_contains($bLower, 'au')) {
            $branchGroup = 'Mechanical';
            $branchLabel = '9. Laboratory / Practical Session Evaluation';
            $branchDesc = 'The instructor effectively relates theory to working machinery, workshop tools, engines, or industrial sub-systems.';
        } elseif (str_contains($bLower, 'electrical') || str_contains($bLower, 'electronics') || str_contains($bLower, 'el') || str_contains($bLower, 'la')) {
            $branchGroup = 'Electrical';
            $branchLabel = '9. Laboratory / Practical Session Evaluation';
            $branchDesc = 'The teacher provides clear safety rules and functional demonstrations for handling active circuits, meters, and electronics.';
        } elseif (str_contains($bLower, 'civil') || str_contains($bLower, 'ce')) {
            $branchGroup = 'Civil';
            $branchLabel = '9. Laboratory / Practical Session Evaluation';
            $branchDesc = 'The course effectively covers hands-on field applications, surveying practices, material tests, or structural drafting tool logic.';
        }
      @endphp

      @if($branchGroup !== 'Other')
      <div class="bg-slate-950/50 border border-slate-800/60 rounded-2xl p-5 hover:border-slate-700/80 transition-premium">
        <div class="flex items-start gap-3 mb-4">
          <span class="material-symbols-rounded text-purple-400 text-xl mt-0.5">science</span>
          <div>
            <h3 class="font-black text-slate-100 text-sm">{{ $branchLabel }}</h3>
            <p class="text-xs text-slate-400 mt-0.5 leading-relaxed">{{ $branchDesc }}</p>
          </div>
        </div>
        <div class="grid grid-cols-3 gap-3">
          <div class="rating-btn border rounded-xl p-3 text-center border-rose-500/30 bg-rose-500/10" id="btn_q13_1" onclick="selectRating('q13', 1)">
            <div class="text-lg font-black text-slate-200">1</div>
            <div class="text-[10px] font-bold text-rose-400 mt-0.5">Poor</div>
          </div>
          <div class="rating-btn border rounded-xl p-3 text-center border-amber-500/30 bg-amber-500/10" id="btn_q13_2" onclick="selectRating('q13', 2)">
            <div class="text-lg font-black text-slate-200">2</div>
            <div class="text-[10px] font-bold text-amber-400 mt-0.5">Average</div>
          </div>
          <div class="rating-btn border rounded-xl p-3 text-center border-teal-500/30 bg-teal-500/10" id="btn_q13_3" onclick="selectRating('q13', 3)">
            <div class="text-lg font-black text-slate-200">3</div>
            <div class="text-[10px] font-bold text-teal-400 mt-0.5">Good</div>
          </div>
        </div>
      </div>
      @endif

      <!-- Section 4: Open-Ended Feedback -->
      <div class="px-2 py-1 pt-3 text-xs text-amber-400 font-bold uppercase tracking-wider">Section 3: Open-Ended Feedback (Optional)</div>
      
      <div class="bg-slate-950/50 border border-slate-800/60 rounded-2xl p-5 hover:border-slate-700/80 transition-premium space-y-3">
        <div>
          <label class="block text-xs font-bold text-slate-400 uppercase mb-1.5">14. What specific topics or chapters in this course did you find most difficult to understand so far?</label>
          <textarea id="q17_val" rows="3" placeholder="Share specific concept names, formulas, or chapters..." class="w-full bg-slate-900 border border-slate-700/60 rounded-xl px-3 py-2.5 text-xs text-white focus:border-amber-500 outline-none"></textarea>
        </div>
        <div>
          <label class="block text-xs font-bold text-slate-400 uppercase mb-1.5">15. Share your constructive suggestions to improve course delivery, practical lab sessions, or overall student engagement:</label>
          <textarea id="q18_val" rows="3" placeholder="Suggestions regarding slides, speed, coding practice, lab experiments, extra classes..." class="w-full bg-slate-900 border border-slate-700/60 rounded-xl px-3 py-2.5 text-xs text-white focus:border-amber-500 outline-none"></textarea>
        </div>
      </div>

      <div id="submitAlert" class="hidden p-4 rounded-xl text-sm font-bold border"></div>

      <button type="submit" id="submitBtn" class="w-full py-4 bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-400 hover:to-orange-400 text-slate-900 rounded-2xl font-black text-base transition-premium shadow-xl shadow-amber-500/20 flex items-center justify-center gap-2 cursor-pointer">
        <span class="material-symbols-rounded text-xl">send</span>
        Submit Feedback
      </button>
    </form>

    <!-- Success State -->
    <div id="successState" class="hidden text-center py-12 bg-slate-950/70 border border-slate-800/80 rounded-3xl p-8 fade-up">
      <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-teal-500/15 border border-teal-500/30 mb-4">
        <span class="material-symbols-rounded text-teal-400" style="font-size:2.5rem">check_circle</span>
      </div>
      <h2 class="text-2xl font-black text-white">Thank You!</h2>
      <p class="text-slate-400 font-semibold mt-2 text-base">Your feedback has been submitted successfully.</p>
      <p class="text-sm text-slate-500 mt-1">Your identity remains strictly confidential.</p>
      <a href="/student/dashboard" class="mt-6 inline-flex items-center gap-2 px-6 py-3 bg-slate-800 hover:bg-slate-700 text-white rounded-xl font-bold text-sm transition-premium no-underline">
        <span class="material-symbols-rounded text-base">arrow_back</span> Return to Dashboard
      </a>
    </div>
  </div>

  <script>
    const selectedRatings = {};

    function selectRating(field, score) {
      selectedRatings[field] = score;

      const ringColors = {
        1: { ring: 'ring-2 ring-rose-400', text: 'text-rose-300' },
        2: { ring: 'ring-2 ring-amber-400', text: 'text-amber-300' },
        3: { ring: 'ring-2 ring-teal-400', text: 'text-teal-300' }
      };

      [1, 2, 3].forEach(s => {
        const el = document.getElementById(`btn_${field}_${s}`);
        if (!el) return;
        el.classList.remove('ring-2', 'ring-rose-400', 'ring-amber-400', 'ring-teal-400', 'selected', 'opacity-40');
      });

      // Dim non-selected
      [1, 2, 3].forEach(s => {
        const el = document.getElementById(`btn_${field}_${s}`);
        if (s !== score) el.classList.add('opacity-40');
      });

      const selected = document.getElementById(`btn_${field}_${score}`);
      if (selected) {
        selected.classList.remove('opacity-40');
        selected.classList.add(...ringColors[score].ring.split(' '), 'selected');
      }
    }

    async function submitSurvey(e) {
      e.preventDefault();
      const alertEl = document.getElementById('submitAlert');
      const btn = document.getElementById('submitBtn');

      const coreFields = ['q5', 'q6', 'q7', 'q8', 'q9', 'q10', 'q11', 'q12'];
      for (const f of coreFields) {
        if (!selectedRatings[f]) {
          alertEl.className = 'p-4 rounded-xl text-sm font-bold border bg-rose-950/30 border-rose-500/30 text-rose-300';
          alertEl.textContent = 'Please rate all 8 questions in Section 1 before submitting.';
          alertEl.classList.remove('hidden');
          return;
        }
      }

      btn.disabled = true;
      btn.innerHTML = '<span class="material-symbols-rounded text-xl animate-spin">progress_activity</span> Submitting…';

      try {
        const payload = {
          survey_id: parseInt(document.getElementById('survey_id_val').value),
          q5: selectedRatings.q5,
          q6: selectedRatings.q6,
          q7: selectedRatings.q7,
          q8: selectedRatings.q8,
          q9: selectedRatings.q9,
          q10: selectedRatings.q10,
          q11: selectedRatings.q11,
          q12: selectedRatings.q12,
          q13: selectedRatings.q13 || null,
          q17: document.getElementById('q17_val').value || null,
          q18: document.getElementById('q18_val').value || null
        };

        const res = await fetch('/api/student/survey/submit', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
          },
          body: JSON.stringify(payload)
        });

        const data = await res.json();

        if (data.status === 'SUCCESS') {
          document.getElementById('surveyForm').classList.add('hidden');
          document.getElementById('successState').classList.remove('hidden');
        } else {
          alertEl.className = 'p-4 rounded-xl text-sm font-bold border bg-rose-950/30 border-rose-500/30 text-rose-300';
          alertEl.textContent = data.message || 'Submission failed. Please try again.';
          alertEl.classList.remove('hidden');
          btn.disabled = false;
          btn.innerHTML = '<span class="material-symbols-rounded text-xl">send</span> Submit Feedback';
        }
      } catch (err) {
        alertEl.className = 'p-4 rounded-xl text-sm font-bold border bg-rose-950/30 border-rose-500/30 text-rose-300';
        alertEl.textContent = 'Network error. Please check your connection and try again.';
        alertEl.classList.remove('hidden');
        btn.disabled = false;
        btn.innerHTML = '<span class="material-symbols-rounded text-xl">send</span> Submit Feedback';
      }
    }
  </script>
</body>
</html>
