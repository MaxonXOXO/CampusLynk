<!DOCTYPE html>
<html lang="en" class="h-full bg-[#FAFAFB]">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Practice Test & Assessment Hub | CampusLynk</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Google Fonts: Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />

    <!-- Pre-Paint Synchronous Sidebar State Hydration (Anti-FOUC) -->
    <script>
        (function() {
            try {
                var isCollapsed = localStorage.getItem('campuslynk_sidebar_collapsed') === 'true' || 
                                  document.cookie.indexOf('campuslynk_sidebar_collapsed=true') !== -1;
                if (isCollapsed && window.innerWidth >= 1024) {
                    document.documentElement.classList.add('sidebar-is-collapsed');
                }
            } catch(e) {}
        })();
    </script>

    <!-- Vite Asset Pipeline -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#FAFAFB] text-slate-800 flex flex-col antialiased font-['Poppins'] sidebar-preload">

    <div class="flex h-screen overflow-hidden bg-[#FAFAFB]">
        
        <!-- Master Sidebar Navigation (Student Role, active: mock_test) -->
        <x-layout.sidebar role="student" active="mock_test" />

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden bg-[#FAFAFB]">
            
            <!-- Master TopBar Component -->
            <x-layout.topbar title="Practice Test Engine" subtitle="Timed syllabus practice assessments and instant competency scoring." />

            <!-- Scrollable Main View Container -->
            <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8 space-y-6">

                <!-- 1. SETUP SECTION -->
                <section id="setupSection" class="max-w-4xl mx-auto space-y-6">
                    <div class="bg-white border border-slate-200 rounded-2xl p-6 sm:p-8 shadow-sm space-y-6">
                        
                        <div class="border-b border-slate-100 pb-4">
                            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-blue-50 border border-blue-200/60 text-blue-700 text-xs font-semibold uppercase tracking-wider mb-2">
                                <i data-lucide="sparkles" class="w-3.5 h-3.5"></i>
                                <span>Practice Session Setup</span>
                            </div>
                            <h2 class="text-xl font-bold text-slate-900">Select Subject & Test Scope</h2>
                            <p class="text-xs text-slate-500 mt-1">Take self-assessment quizzes generated from your syllabus. Daily quota: 1 attempt per subject.</p>
                        </div>

                        <!-- Setup Loader -->
                        <div id="setupLoader" class="flex flex-col items-center justify-center py-12 text-center text-slate-400">
                            <div class="w-8 h-8 border-2 border-slate-200 border-t-blue-600 rounded-full animate-spin mb-3"></div>
                            <p class="text-xs font-semibold text-slate-500">Loading semester subjects & syllabus modules...</p>
                        </div>

                        <!-- Setup Form -->
                        <form id="setupForm" class="space-y-6 hidden" onsubmit="event.preventDefault(); initiateTest();">
                            
                            <!-- Subject Selection Cards Grid -->
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-3">Available Semester Subjects</label>
                                <div id="subjectGrid" class="grid grid-cols-1 sm:grid-cols-2 gap-3.5"></div>
                                <input type="hidden" id="selectedSubject" required>
                            </div>

                            <!-- Test Parameters Grid -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 border-t border-slate-100 pt-5">
                                <div>
                                    <x-ui.select id="questionCount" name="question_count" label="Question Count" :options="['10'=>'10 Questions (Quick Practice ~ 10 mins)', '15'=>'15 Questions (Standard Evaluation ~ 15 mins)', '20'=>'20 Questions (Full Series Prep ~ 20 mins)']" value="15" />
                                </div>
                                <div>
                                    <x-ui.select id="moduleScope" name="module_scope" label="Syllabus Scope" :options="['all'=>'All Modules (Comprehensive Syllabus)', 'module1'=>'Module 1 & 2 Focus', 'module3'=>'Module 3 & 4 Focus']" value="all" />
                                </div>
                            </div>

                            <div class="pt-3 flex justify-end">
                                <button type="submit" id="btnStartTest" class="px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-semibold transition-all shadow-sm flex items-center gap-2">
                                    <i data-lucide="play" class="w-4 h-4"></i>
                                    <span>Launch Practice Test</span>
                                </button>
                            </div>

                        </form>

                    </div>
                </section>

                <!-- 2. ACTIVE TEST EXAMINATION SECTION -->
                <section id="testSection" class="hidden max-w-4xl mx-auto space-y-6">
                    <div class="bg-white border border-slate-200 rounded-2xl p-6 sm:p-8 shadow-sm space-y-6">
                        
                        <!-- Test Header with Live Timer -->
                        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                            <div>
                                <h3 id="activeSubjectTitle" class="text-base font-bold text-slate-900">Subject Practice Test</h3>
                                <p id="activeQuestionCounter" class="text-xs text-slate-500 mt-0.5">Question 1 of 15</p>
                            </div>
                            <div class="flex items-center gap-2 px-4 py-2 bg-blue-50 border border-blue-200/80 rounded-xl text-blue-700 font-mono text-sm font-bold shadow-2xs">
                                <i data-lucide="timer" class="w-4 h-4 text-blue-600"></i>
                                <span id="testTimer">15:00</span>
                            </div>
                        </div>

                        <!-- Question Navigator Dots -->
                        <div class="flex flex-wrap gap-2 p-3 bg-slate-50 rounded-xl border border-slate-200/60" id="questionNavigator"></div>

                        <!-- Current Question Container -->
                        <div id="currentQuestionBox" class="space-y-4 pt-2">
                            <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/80">
                                <span class="text-xs font-semibold text-blue-700 uppercase tracking-wider" id="qBadge">Question 1</span>
                                <p class="text-sm font-semibold text-slate-900 mt-1" id="qText">Question text loading...</p>
                            </div>

                            <!-- Options List -->
                            <div class="space-y-2.5" id="optionsContainer"></div>
                        </div>

                        <!-- Navigation Controls -->
                        <div class="flex items-center justify-between border-t border-slate-100 pt-5">
                            <button type="button" onclick="navigatePrevQuestion()" id="btnPrevQ" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-semibold transition-all disabled:opacity-40 disabled:cursor-not-allowed">
                                Previous
                            </button>
                            <div class="flex gap-2">
                                <button type="button" onclick="navigateNextQuestion()" id="btnNextQ" class="px-6 py-2.5 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-semibold transition-all">
                                    Next Question
                                </button>
                                <button type="button" onclick="submitFullTest()" id="btnSubmitTest" class="hidden px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-semibold transition-all shadow-sm">
                                    Submit Test
                                </button>
                            </div>
                        </div>

                    </div>
                </section>

                <!-- 3. SCORE REPORT SECTION -->
                <section id="resultSection" class="hidden max-w-4xl mx-auto space-y-6">
                    <div class="bg-white border border-slate-200 rounded-2xl p-6 sm:p-8 shadow-sm space-y-6 text-center">
                        
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-emerald-50 text-emerald-600 border border-emerald-200 mx-auto">
                            <i data-lucide="award" class="w-8 h-8"></i>
                        </div>

                        <div>
                            <h2 class="text-2xl font-bold text-slate-900">Practice Session Completed!</h2>
                            <p class="text-xs text-slate-500 mt-1" id="resultSubjectSubtitle">Assessment results and competency breakdown</p>
                        </div>

                        <!-- Score Pill -->
                        <div class="max-w-xs mx-auto p-5 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-1">
                            <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Final Test Score</span>
                            <p class="text-3xl font-bold text-blue-700" id="finalScoreText">0 / 15</p>
                            <p class="text-xs font-semibold text-emerald-700" id="finalPercentageText">0% Proficiency</p>
                        </div>

                        <!-- Detailed Answer Review List -->
                        <div class="text-left space-y-3 pt-4 border-t border-slate-100" id="detailedReviewList"></div>

                        <div class="pt-4 flex justify-center">
                            <button type="button" onclick="resetPracticeTest()" class="px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-semibold transition-all shadow-sm">
                                Back to Practice Hub
                            </button>
                        </div>

                    </div>
                </section>

            </main>

        </div>
    </div>

    <!-- Practice Test Controller Scripts -->
    <script>
        let testQuestions = [];
        let studentAnswers = {};
        let currentQuestionIdx = 0;
        let testTimerInterval = null;
        let remainingSeconds = 900;

        document.addEventListener('DOMContentLoaded', () => {
            loadMockSubjects();
        });

        function loadMockSubjects() {
            fetch('/api/student/mock-test/subjects')
                .then(res => res.json())
                .then(data => {
                    document.getElementById('setupLoader').classList.add('hidden');
                    document.getElementById('setupForm').classList.remove('hidden');

                    const grid = document.getElementById('subjectGrid');
                    if (data && data.subjects && data.subjects.length > 0) {
                        grid.innerHTML = data.subjects.map(s => `
                            <div onclick="selectSubjectCard('${s.subject_code}', this)" class="subject-card p-4 rounded-xl border border-slate-200 hover:border-blue-500 cursor-pointer transition-all bg-white hover:bg-blue-50/30 flex items-start justify-between">
                                <div>
                                    <p class="text-xs font-bold text-slate-900">${s.subject_code}</p>
                                    <p class="text-xs text-slate-600 mt-0.5 line-clamp-1">${s.subject_name}</p>
                                    <span class="inline-block mt-2 px-2 py-0.5 rounded-full text-[10px] font-semibold ${s.already_attempted ? 'bg-amber-50 text-amber-800' : 'bg-emerald-50 text-emerald-800'}">
                                        ${s.already_attempted ? 'Attempted Today' : 'Available'}
                                    </span>
                                </div>
                                <i data-lucide="circle" class="w-4 h-4 text-slate-300 card-check"></i>
                            </div>
                        `).join('');
                        if (window.initLucide) window.initLucide();
                    } else {
                        grid.innerHTML = '<div class="col-span-full py-8 text-center text-slate-400 text-xs font-medium">No active subjects registered for mock practice.</div>';
                    }
                })
                .catch(() => {
                    document.getElementById('setupLoader').innerHTML = '<p class="text-xs text-rose-600 font-semibold">Error loading subjects.</p>';
                });
        }

        function selectSubjectCard(code, el) {
            document.querySelectorAll('.subject-card').forEach(c => {
                c.classList.remove('border-blue-600', 'bg-blue-50/50');
                const ic = c.querySelector('.card-check');
                if (ic) ic.setAttribute('data-lucide', 'circle');
            });
            el.classList.add('border-blue-600', 'bg-blue-50/50');
            const ic = el.querySelector('.card-check');
            if (ic) ic.setAttribute('data-lucide', 'check-circle-2');
            document.getElementById('selectedSubject').value = code;
            if (window.initLucide) window.initLucide();
        }

        function initiateTest() {
            const subject = document.getElementById('selectedSubject').value;
            const count = parseInt(document.getElementById('questionCount').value) || 15;
            if (!subject) {
                alert('Please select a subject to start practice.');
                return;
            }

            const btn = document.getElementById('btnStartTest');
            btn.disabled = true;
            btn.innerHTML = '<div class="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></div> Generating test...';

            fetch('/api/student/mock-test/generate', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                body: JSON.stringify({ subject_code: subject, count: count })
            })
            .then(res => res.json())
            .then(data => {
                btn.disabled = false;
                btn.innerHTML = '<span>Launch Practice Test</span>';

                if (data.status === 'SUCCESS' && data.questions && data.questions.length > 0) {
                    testQuestions = data.questions;
                    studentAnswers = {};
                    currentQuestionIdx = 0;
                    remainingSeconds = count * 60;

                    document.getElementById('setupSection').classList.add('hidden');
                    document.getElementById('testSection').classList.remove('hidden');
                    document.getElementById('activeSubjectTitle').innerText = subject + ' Practice Assessment';

                    renderQuestionNavigator();
                    displayCurrentQuestion();
                    startTestTimer();
                } else {
                    alert(data.message || 'Unable to generate practice questions.');
                }
            })
            .catch(() => {
                btn.disabled = false;
                btn.innerHTML = '<span>Launch Practice Test</span>';
                alert('Server error generating test.');
            });
        }

        function renderQuestionNavigator() {
            const container = document.getElementById('questionNavigator');
            container.innerHTML = testQuestions.map((_, i) => `
                <button type="button" onclick="jumpToQuestion(${i})" id="navDot-${i}" class="w-8 h-8 rounded-lg text-xs font-semibold border transition-all ${i === 0 ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-slate-700 border-slate-200 hover:bg-slate-100'}">
                    ${i + 1}
                </button>
            `).join('');
        }

        function displayCurrentQuestion() {
            const q = testQuestions[currentQuestionIdx];
            if (!q) return;

            document.getElementById('activeQuestionCounter').innerText = `Question ${currentQuestionIdx + 1} of ${testQuestions.length}`;
            document.getElementById('qBadge').innerText = `Question ${currentQuestionIdx + 1} (${q.module || 'Syllabus Topic'})`;
            document.getElementById('qText').innerText = q.question;

            const optsBox = document.getElementById('optionsContainer');
            const selectedOpt = studentAnswers[currentQuestionIdx];

            optsBox.innerHTML = (q.options || []).map((opt, optIdx) => `
                <label onclick="recordAnswer(${currentQuestionIdx}, ${optIdx})" class="flex items-center gap-3 p-3.5 rounded-xl border cursor-pointer transition-all ${selectedOpt === optIdx ? 'bg-blue-50 border-blue-600 text-blue-900 font-semibold' : 'bg-white border-slate-200 text-slate-700 hover:bg-slate-50'}">
                    <input type="radio" name="optRadio" value="${optIdx}" ${selectedOpt === optIdx ? 'checked' : ''} class="w-4 h-4 text-blue-600 border-slate-300">
                    <span class="text-xs">${opt}</span>
                </label>
            `).join('');

            document.getElementById('btnPrevQ').disabled = (currentQuestionIdx === 0);
            if (currentQuestionIdx === testQuestions.length - 1) {
                document.getElementById('btnNextQ').classList.add('hidden');
                document.getElementById('btnSubmitTest').classList.remove('hidden');
            } else {
                document.getElementById('btnNextQ').classList.remove('hidden');
                document.getElementById('btnSubmitTest').classList.add('hidden');
            }

            // Update dot colors
            testQuestions.forEach((_, i) => {
                const dot = document.getElementById(`navDot-${i}`);
                if (!dot) return;
                if (i === currentQuestionIdx) {
                    dot.className = "w-8 h-8 rounded-lg text-xs font-semibold border bg-blue-600 text-white border-blue-600 shadow-xs";
                } else if (studentAnswers[i] !== undefined) {
                    dot.className = "w-8 h-8 rounded-lg text-xs font-semibold border bg-emerald-50 text-emerald-800 border-emerald-300";
                } else {
                    dot.className = "w-8 h-8 rounded-lg text-xs font-semibold border bg-white text-slate-700 border-slate-200 hover:bg-slate-100";
                }
            });
        }

        function recordAnswer(qIdx, optIdx) {
            studentAnswers[qIdx] = optIdx;
            displayCurrentQuestion();
        }

        function jumpToQuestion(idx) {
            currentQuestionIdx = idx;
            displayCurrentQuestion();
        }

        function navigateNextQuestion() {
            if (currentQuestionIdx < testQuestions.length - 1) {
                currentQuestionIdx++;
                displayCurrentQuestion();
            }
        }

        function navigatePrevQuestion() {
            if (currentQuestionIdx > 0) {
                currentQuestionIdx--;
                displayCurrentQuestion();
            }
        }

        function startTestTimer() {
            clearInterval(testTimerInterval);
            testTimerInterval = setInterval(() => {
                remainingSeconds--;
                const mins = Math.floor(remainingSeconds / 60);
                const secs = remainingSeconds % 60;
                document.getElementById('testTimer').innerText = `${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
                if (remainingSeconds <= 0) {
                    clearInterval(testTimerInterval);
                    alert('Time up! Submitting test automatically.');
                    submitFullTest();
                }
            }, 1000);
        }

        function submitFullTest() {
            if (!confirm('Are you sure you want to submit your test for scoring?')) return;
            clearInterval(testTimerInterval);

            const payload = {
                subject_code: document.getElementById('selectedSubject').value,
                answers: studentAnswers,
                questions: testQuestions
            };

            fetch('/api/student/mock-test/submit', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                body: JSON.stringify(payload)
            })
            .then(res => res.json())
            .then(data => {
                document.getElementById('testSection').classList.add('hidden');
                document.getElementById('resultSection').classList.remove('hidden');

                const score = data.score || 0;
                const total = testQuestions.length;
                const pct = Math.round((score / total) * 100);

                document.getElementById('finalScoreText').innerText = `${score} / ${total}`;
                document.getElementById('finalPercentageText').innerText = `${pct}% Competency`;

                // Render review
                const reviewBox = document.getElementById('detailedReviewList');
                reviewBox.innerHTML = testQuestions.map((q, idx) => {
                    const ans = studentAnswers[idx];
                    const isCorrect = (ans === q.correct_option);
                    return `
                        <div class="p-4 rounded-xl border ${isCorrect ? 'bg-emerald-50/40 border-emerald-200' : 'bg-rose-50/40 border-rose-200'} space-y-2">
                            <p class="text-xs font-bold text-slate-900">Q${idx + 1}: ${q.question}</p>
                            <p class="text-[11px] text-slate-600">Your Answer: <strong class="${isCorrect ? 'text-emerald-700' : 'text-rose-700'}">${q.options[ans] || 'Not Answered'}</strong></p>
                            ${!isCorrect ? `<p class="text-[11px] text-emerald-700">Correct Answer: <strong>${q.options[q.correct_option]}</strong></p>` : ''}
                            ${q.explanation ? `<p class="text-[11px] text-slate-500 italic mt-1">${q.explanation}</p>` : ''}
                        </div>
                    `;
                }).join('');
            })
            .catch(() => alert('Error submitting test.'));
        }

        function resetPracticeTest() {
            document.getElementById('resultSection').classList.add('hidden');
            document.getElementById('setupSection').classList.remove('hidden');
            loadMockSubjects();
        }

        requestAnimationFrame(function() {
            document.body.classList.remove('sidebar-preload');
        });
    </script>
</body>
</html>
