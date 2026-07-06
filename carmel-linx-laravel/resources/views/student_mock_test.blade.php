<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-950 text-slate-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>MCQ Mock Practice Test - Carmel Linx</title>
    <!-- Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
    <!-- Tailwind CSS CDN (v4 Play CDN) -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <style>
        body {
            font-family: "Plus Jakarta Sans", sans-serif;
        }
        .font-mono {
            font-family: "JetBrains Mono", monospace !important;
        }
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: rgba(15, 23, 42, 0.3);
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(51, 65, 85, 0.5);
            border-radius: 9999px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: rgba(71, 85, 105, 0.7);
        }
        @media print {
            .no-print { display: none !important; }
        }
    </style>
</head>
<body class="h-full flex flex-col font-sans antialiased overflow-x-hidden selection:bg-blue-500/30 selection:text-blue-200">

    <!-- Top Navigation Header -->
    <header class="border-b border-slate-900 bg-slate-950/80 backdrop-blur-md sticky top-0 z-50 px-4 py-4">
        <div class="max-w-4xl mx-auto flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="h-10 w-10 rounded-xl bg-gradient-to-tr from-blue-600 to-indigo-500 flex items-center justify-center shadow-lg shadow-blue-500/20">
                    <span class="material-symbols-rounded text-white text-xl">architecture</span>
                </div>
                <div>
                    <h1 class="text-base font-extrabold text-slate-100 tracking-tight">Carmel Linx</h1>
                    <p class="text-xs text-slate-400 font-bold uppercase tracking-widest">Mock Practice Engine</p>
                </div>
            </div>
            <button onclick="window.close();" class="px-3.5 py-2 bg-slate-900 hover:bg-slate-850 text-slate-300 rounded-xl text-sm font-bold border border-slate-800 transition-all flex items-center gap-2 cursor-pointer no-print">
                <span class="material-symbols-rounded text-sm">close</span> Exit Practice
            </button>
        </div>
    </header>

    <!-- Main Container -->
    <main class="flex-1 max-w-4xl w-full mx-auto p-4 md:py-8">

        <!-- 1. Setup Mode Container -->
        <section id="setupSection" class="bg-slate-900/40 border border-slate-900 rounded-3xl p-6 md:p-8 shadow-2xl relative overflow-hidden">
            <div class="absolute top-0 right-0 h-40 w-40 bg-blue-500/5 rounded-full blur-3xl pointer-events-none"></div>
            
            <div class="mb-6">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-500/10 border border-blue-500/20 text-blue-400 text-xs font-bold uppercase tracking-widest mb-3">
                    <span class="material-symbols-rounded text-xs">tune</span> Configuration
                </div>
                <h2 class="text-2xl font-black text-slate-100">Setup Practice Session</h2>
                <p class="text-sm text-slate-400 mt-1 leading-relaxed">Customize your MCQ practice test. Attempts are limited to 1 per subject daily to encourage focused learning.</p>
            </div>

            <!-- Loading Spinner inside Setup -->
            <div id="setupLoader" class="flex flex-col items-center justify-center py-12 text-center text-slate-500">
                <div class="w-8 h-8 border-3 border-slate-800 border-t-blue-500 rounded-full animate-spin mb-4"></div>
                <p class="text-sm font-bold text-slate-400">Loading subjects & student profile...</p>
            </div>

            <!-- Setup Form -->
            <form id="setupForm" class="space-y-6 hidden" onsubmit="event.preventDefault(); initiateTest();">
                <!-- Profile Summary Card -->
                <div class="bg-slate-950/40 border border-slate-900 rounded-2xl p-4 grid grid-cols-2 md:grid-cols-4 gap-4 text-sm font-medium">
                    <div>
                        <span class="text-slate-500 block text-xs font-bold uppercase tracking-wider">Student Name</span>
                        <span id="profName" class="text-slate-200 font-bold">Loading...</span>
                    </div>
                    <div>
                        <span class="text-slate-500 block text-xs font-bold uppercase tracking-wider">SBTE Number</span>
                        <span id="profSbte" class="text-slate-200 font-bold font-mono">Loading...</span>
                    </div>
                    <div>
                        <span class="text-slate-500 block text-xs font-bold uppercase tracking-wider">Class</span>
                        <span id="profClass" class="text-slate-200 font-bold">Loading...</span>
                    </div>
                    <div>
                        <span class="text-slate-500 block text-xs font-bold uppercase tracking-wider">Batch</span>
                        <span id="profBatch" class="text-slate-200 font-bold font-mono">Loading...</span>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Subject Selection -->
                    <div>
                        <label for="subjectSelect" class="block text-sm font-bold text-slate-300 mb-2">Select Subject</label>
                        <select id="subjectSelect" onchange="checkSubjectLimit();" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-slate-100 text-sm font-bold focus:outline-none focus:border-blue-500 transition-all cursor-pointer">
                            <!-- Populated dynamically -->
                        </select>
                    </div>

                    <!-- Course Outcome (CO) filter -->
                    <div>
                        <label for="coSelect" class="block text-sm font-bold text-slate-300 mb-2">Course Outcome (CO)</label>
                        <select id="coSelect" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-slate-100 text-sm font-bold focus:outline-none focus:border-blue-500 transition-all cursor-pointer">
                            <option value="All">All Course Outcomes</option>
                            <option value="CO1">CO1</option>
                            <option value="CO2">CO2</option>
                            <option value="CO3">CO3</option>
                            <option value="CO4">CO4</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Number of Questions -->
                    <div>
                        <label for="questionCount" class="block text-sm font-bold text-slate-300 mb-2">Total Questions</label>
                        <select id="questionCount" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-slate-100 text-sm font-bold focus:outline-none focus:border-blue-500 transition-all cursor-pointer">
                            <option value="5">5 Questions</option>
                            <option value="10" selected>10 Questions</option>
                            <option value="15">15 Questions</option>
                            <option value="20">20 Questions</option>
                        </select>
                    </div>

                    <!-- Timer Description (ReadOnly) -->
                    <div>
                        <label class="block text-sm font-bold text-slate-300 mb-2">Test Duration</label>
                        <div class="w-full bg-slate-950/50 border border-slate-900/60 text-slate-500 rounded-xl px-4 py-3 text-sm font-bold flex items-center gap-2">
                            <span class="material-symbols-rounded text-sm">schedule</span> 30 Minutes (Fixed)
                        </div>
                    </div>
                </div>

                <!-- Daily limit alert warning -->
                <div id="limitAlert" class="hidden p-4 rounded-2xl bg-rose-950/20 border border-rose-500/20 text-rose-400 text-sm flex gap-3 items-start">
                    <span class="material-symbols-rounded text-base mt-0.5">warning</span>
                    <div>
                        <p class="font-bold">Daily Attempt Limit Reached</p>
                        <p class="text-xs text-rose-500 font-medium mt-0.5">You have already taken a mock test for this subject today. Please select another subject or try again tomorrow.</p>
                    </div>
                </div>

                <!-- Start Button -->
                <button type="submit" id="startBtn" class="w-full py-4 bg-gradient-to-r from-blue-600 to-indigo-500 hover:from-blue-500 hover:to-indigo-400 text-white rounded-xl text-sm font-bold transition-all flex items-center justify-center gap-2 shadow-lg shadow-blue-500/10 cursor-pointer">
                    <span class="material-symbols-rounded text-sm">rocket_launch</span> Generate Practice Test
                </button>
            </form>
        </section>

        <!-- 2. Test Execution Mode -->
        <section id="testSection" class="hidden space-y-6">
            <!-- Active Test Stats Bar -->
            <div class="bg-slate-900/30 border border-slate-900 rounded-2xl p-4 flex items-center justify-between text-sm font-bold">
                <div class="flex items-center gap-2">
                    <span class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span id="activeSubjectLabel" class="text-slate-300">Subject Name</span>
                </div>
                <div class="flex items-center gap-4">
                    <!-- Timer -->
                    <div class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-rose-500/10 border border-rose-500/20 text-rose-400 font-mono">
                        <span class="material-symbols-rounded text-sm">timer</span>
                        <span id="timerDisplay">30:00</span>
                    </div>
                </div>
            </div>

            <!-- Main Question Area -->
            <div class="bg-slate-900/40 border border-slate-900 rounded-3xl p-6 md:p-8 shadow-2xl relative">
                <!-- Progress Indicator -->
                <div class="flex justify-between items-center mb-6">
                    <span class="text-xs text-slate-400 font-bold uppercase tracking-wider">Question <span id="currentQNo" class="text-slate-200">1</span> of <span id="totalQs" class="text-slate-200">10</span></span>
                    <span id="activeCoBadge" class="text-[10px] font-black px-2 py-0.5 rounded bg-blue-900/30 border border-blue-500/30 text-blue-400 uppercase tracking-widest">CO1</span>
                </div>

                <!-- Question Text -->
                <h3 id="questionText" class="text-lg font-extrabold text-slate-100 leading-relaxed mb-6">Loading question...</h3>

                <!-- Options -->
                <div id="optionsContainer" class="space-y-3">
                    <!-- Options buttons rendered dynamically -->
                </div>

                <!-- Navigation controls -->
                <div class="flex justify-between items-center mt-8 pt-6 border-t border-slate-900">
                    <button onclick="prevQuestion();" id="prevBtn" class="px-4 py-2.5 bg-slate-900 hover:bg-slate-850 text-slate-300 rounded-xl text-sm font-bold border border-slate-800 transition-all flex items-center gap-1.5 cursor-pointer disabled:opacity-30 disabled:pointer-events-none">
                        <span class="material-symbols-rounded text-sm">arrow_back</span> Back
                    </button>
                    
                    <button onclick="nextQuestion();" id="nextBtn" class="px-4 py-2.5 bg-blue-600 hover:bg-blue-500 text-white rounded-xl text-sm font-bold border border-blue-500/30 transition-all flex items-center gap-1.5 cursor-pointer">
                        Next <span class="material-symbols-rounded text-sm">arrow_forward</span>
                    </button>
                </div>
            </div>

            <!-- Question Grid Tracker -->
            <div class="bg-slate-900/20 border border-slate-900 rounded-2xl p-4">
                <span class="text-xs text-slate-400 font-bold uppercase tracking-wider block mb-3">Question Tracker</span>
                <div id="trackerGrid" class="flex flex-wrap gap-2">
                    <!-- Trackers rendered dynamically -->
                </div>
            </div>

            <!-- Submit Practice Button -->
            <button onclick="submitTest();" class="w-full py-4 bg-emerald-600 hover:bg-emerald-500 text-white rounded-xl text-sm font-bold border border-emerald-500/30 transition-all flex items-center justify-center gap-2 shadow-lg shadow-emerald-500/10 cursor-pointer">
                <span class="material-symbols-rounded text-sm">check_circle</span> Submit and View Score
            </button>
        </section>

        <!-- 3. Results / Score Card Mode -->
        <section id="resultsSection" class="hidden space-y-6">
            <!-- Score Card (Screenshot Area) -->
            <div id="screenshotArea" class="bg-slate-900/60 border-2 border-blue-500/30 rounded-3xl p-6 md:p-8 shadow-2xl relative overflow-hidden bg-gradient-to-b from-slate-900/70 to-slate-950">
                <div class="absolute -top-10 -left-10 h-40 w-40 bg-emerald-500/5 rounded-full blur-3xl pointer-events-none"></div>

                <div class="text-center mb-8 border-b border-slate-800/60 pb-6">
                    <h2 class="text-xl font-black text-slate-100 tracking-tight">Carmel Linx — Mock Practice Evidence</h2>
                    <p class="text-xs text-emerald-400 font-bold uppercase tracking-widest mt-1">Successfully Completed Practice Test</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-center">
                    <!-- Circular Score Display -->
                    <div class="flex flex-col items-center justify-center py-4">
                        <div class="h-32 w-32 rounded-full border-4 border-emerald-500/30 bg-emerald-500/5 flex flex-col items-center justify-center shadow-lg shadow-emerald-500/5">
                            <span id="scoreText" class="text-4xl font-extrabold text-emerald-400">8</span>
                            <span id="scoreTotalText" class="text-xs text-slate-500 font-bold border-t border-slate-800 pt-1 mt-1 w-12 text-center uppercase tracking-widest">Of 10</span>
                        </div>
                        <span id="performanceMessage" class="text-sm font-bold text-slate-300 mt-4">Great practice session!</span>
                    </div>

                    <!-- Meta Data Block (Evidence) -->
                    <div class="bg-slate-950/60 border border-slate-900 rounded-2xl p-6 space-y-4 text-sm font-medium">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <span class="text-slate-500 block text-xs font-bold uppercase tracking-wider">Student Name</span>
                                <span id="resName" class="text-slate-200 font-bold">Name</span>
                            </div>
                            <div>
                                <span class="text-slate-500 block text-xs font-bold uppercase tracking-wider">SBTE Number</span>
                                <span id="resSbte" class="text-slate-200 font-bold font-mono">12345</span>
                            </div>
                            <div>
                                <span class="text-slate-500 block text-xs font-bold uppercase tracking-wider">Class & Batch</span>
                                <span id="resClassBatch" class="text-slate-200 font-bold">Class / Batch</span>
                            </div>
                            <div>
                                <span class="text-slate-500 block text-xs font-bold uppercase tracking-wider">Practice Date & Time</span>
                                <span id="resDateTime" class="text-slate-200 font-bold font-mono">Date</span>
                            </div>
                        </div>
                        <div class="border-t border-slate-900 pt-3">
                            <span class="text-slate-500 block text-xs font-bold uppercase tracking-wider">Subject</span>
                            <span id="resSubject" class="text-slate-200 font-bold">Subject (Code)</span>
                        </div>
                    </div>
                </div>

                <div class="mt-8 p-4 rounded-xl bg-blue-950/20 border border-blue-500/20 text-blue-400 text-sm flex gap-3 items-center no-print">
                    <span class="material-symbols-rounded text-lg">photo_camera</span>
                    <span class="font-bold">Please take a screenshot of this score card as evidence of your practice test.</span>
                </div>
            </div>

            <!-- Detailed Answer Review List -->
            <div class="bg-slate-900/20 border border-slate-900 rounded-2xl p-6 space-y-6">
                <h3 class="text-base font-extrabold text-slate-200 flex items-center gap-2">
                    <span class="material-symbols-rounded text-slate-400 text-sm">rule</span> Practice Review (Answer Key)
                </h3>
                <div id="reviewList" class="space-y-4">
                    <!-- Reviews rendered dynamically -->
                </div>
            </div>

            <!-- Finish Button -->
            <button onclick="location.reload();" class="w-full py-4 bg-slate-900 hover:bg-slate-850 text-slate-300 rounded-xl text-sm font-bold border border-slate-800 transition-all flex items-center justify-center gap-2 cursor-pointer no-print">
                <span class="material-symbols-rounded text-sm">restart_alt</span> Back to Start
            </button>
        </section>

    </main>

    <!-- Isolated Operations JS -->
    <script>
        // Global variables
        let studentProfile = null;
        let subjects = [];
        let activeQuestions = [];
        let studentAnswers = []; // indexes matching questions
        let currentQuestionIdx = 0;
        let timerInterval = null;
        let timeLeft = 1800; // 30 mins in seconds

        document.addEventListener("DOMContentLoaded", () => {
            loadSetupData();
        });

        // Load subjects and user profile
        function loadSetupData() {
            fetch('/api/student/mock-test/subjects')
                .then(res => res.json())
                .then(res => {
                    if (res.status === 'SUCCESS') {
                        studentProfile = {
                            name: res.data.student_name,
                            sbte_reg_no: res.data.sbte_reg_no,
                            roll_no: res.data.roll_no,
                            classroom_name: res.data.classroom_name,
                            batch: res.data.batch
                        };

                        // Populate profile fields
                        document.getElementById('profName').innerText = studentProfile.name;
                        document.getElementById('profSbte').innerText = studentProfile.sbte_reg_no;
                        document.getElementById('profClass').innerText = studentProfile.classroom_name;
                        document.getElementById('profBatch').innerText = studentProfile.batch;

                        subjects = res.data.subjects || [];
                        const select = document.getElementById('subjectSelect');
                        select.innerHTML = '';
                        
                        if (subjects.length === 0) {
                            select.innerHTML = '<option value="">No current subjects found</option>';
                            document.getElementById('startBtn').disabled = true;
                        } else {
                            subjects.forEach(s => {
                                const option = document.createElement('option');
                                option.value = s.subject_code;
                                option.innerText = `${s.subject_code} - ${s.subject_name}`;
                                select.appendChild(option);
                            });
                            checkSubjectLimit();
                        }

                        // Toggle visibility
                        document.getElementById('setupLoader').classList.add('hidden');
                        document.getElementById('setupForm').classList.remove('hidden');
                    } else {
                        alert("Failed to load setup data: " + res.message);
                    }
                })
                .catch(err => {
                    console.error(err);
                    alert("Network error. Failed to load mock test setup.");
                });
        }

        // Check if subject is limited today
        function checkSubjectLimit() {
            const code = document.getElementById('subjectSelect').value;
            const subj = subjects.find(s => s.subject_code === code);
            const alertBox = document.getElementById('limitAlert');
            const startBtn = document.getElementById('startBtn');

            if (subj && subj.already_attempted_today) {
                alertBox.classList.remove('hidden');
                startBtn.disabled = true;
                startBtn.classList.add('opacity-40', 'cursor-not-allowed');
            } else {
                alertBox.classList.add('hidden');
                startBtn.disabled = false;
                startBtn.classList.remove('opacity-40', 'cursor-not-allowed');
            }
        }

        // Start Practice Test
        function initiateTest() {
            const code = document.getElementById('subjectSelect').value;
            const co = document.getElementById('coSelect').value;
            const count = document.getElementById('questionCount').value;
            const startBtn = document.getElementById('startBtn');

            startBtn.disabled = true;
            startBtn.innerText = "Generating practice questions (using AI if needed)...";

            fetch('/api/student/mock-test/start', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    subject_code: code,
                    co_tag: co,
                    num_questions: count
                })
            })
            .then(res => res.json())
            .then(res => {
                if (res.status === 'SUCCESS') {
                    activeQuestions = res.data.questions || [];
                    studentAnswers = new Array(activeQuestions.length).fill(null);
                    currentQuestionIdx = 0;
                    
                    // Setup UI Labels
                    const subj = subjects.find(s => s.subject_code === code);
                    document.getElementById('activeSubjectLabel').innerText = `${code} - ${subj ? subj.subject_name : ''}`;
                    document.getElementById('totalQs').innerText = activeQuestions.length;

                    // Transition Views
                    document.getElementById('setupSection').classList.add('hidden');
                    document.getElementById('testSection').classList.remove('hidden');

                    // Start Timer & Render
                    startTimer();
                    renderQuestion();
                    renderTracker();
                } else {
                    alert(res.message || "Failed to start test.");
                    startBtn.disabled = false;
                    startBtn.innerHTML = '<span class="material-symbols-rounded text-sm">rocket_launch</span> Generate Practice Test';
                }
            })
            .catch(err => {
                console.error(err);
                alert("An error occurred starting the test.");
                startBtn.disabled = false;
                startBtn.innerHTML = '<span class="material-symbols-rounded text-sm">rocket_launch</span> Generate Practice Test';
            });
        }

        // Timer controller
        function startTimer() {
            timeLeft = 1800; // 30 minutes
            updateTimerDisplay();

            timerInterval = setInterval(() => {
                timeLeft--;
                updateTimerDisplay();
                if (timeLeft <= 0) {
                    clearInterval(timerInterval);
                    alert("Time's up! Your mock test is being auto-submitted.");
                    submitTest();
                }
            }, 1000);
        }

        function updateTimerDisplay() {
            const mins = Math.floor(timeLeft / 60);
            const secs = timeLeft % 60;
            document.getElementById('timerDisplay').innerText = 
                `${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
        }

        // Render Current Question
        function renderQuestion() {
            if (activeQuestions.length === 0) return;
            const q = activeQuestions[currentQuestionIdx];

            document.getElementById('currentQNo').innerText = currentQuestionIdx + 1;
            document.getElementById('activeCoBadge').innerText = q.co_tag || 'MCQ';
            document.getElementById('questionText').innerText = q.question_text;

            // Render Options
            const container = document.getElementById('optionsContainer');
            container.innerHTML = '';

            const selectedAns = studentAnswers[currentQuestionIdx];

            q.options.forEach((opt) => {
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.onclick = () => selectOption(opt);
                
                const isSelected = selectedAns === opt;
                btn.className = `w-full text-left p-4 rounded-xl text-sm font-bold border transition-all cursor-pointer ${
                    isSelected 
                        ? 'bg-blue-600/20 border-blue-500 text-blue-200' 
                        : 'bg-slate-950/40 border-slate-900 hover:bg-slate-950/80 hover:border-slate-800 text-slate-300'
                }`;
                btn.innerText = opt;
                container.appendChild(btn);
            });

            // Adjust navigation buttons
            document.getElementById('prevBtn').disabled = (currentQuestionIdx === 0);
            
            const nextBtn = document.getElementById('nextBtn');
            if (currentQuestionIdx === activeQuestions.length - 1) {
                nextBtn.innerHTML = 'Review <span class="material-symbols-rounded text-sm">visibility</span>';
            } else {
                nextBtn.innerHTML = 'Next <span class="material-symbols-rounded text-sm">arrow_forward</span>';
            }
        }

        // Select Option
        function selectOption(option) {
            studentAnswers[currentQuestionIdx] = option;
            renderQuestion();
            renderTracker();
        }

        // Tracker grid renderer
        function renderTracker() {
            const container = document.getElementById('trackerGrid');
            container.innerHTML = '';

            activeQuestions.forEach((_, idx) => {
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.onclick = () => {
                    currentQuestionIdx = idx;
                    renderQuestion();
                };

                const isCurrent = currentQuestionIdx === idx;
                const isAnswered = studentAnswers[idx] !== null;

                let cls = 'h-10 w-10 text-xs font-bold rounded-lg transition-all border flex items-center justify-center cursor-pointer ';
                if (isCurrent) {
                    cls += 'bg-blue-600/20 border-blue-500 text-blue-200';
                } else if (isAnswered) {
                    cls += 'bg-slate-900 border-slate-700/60 text-slate-300';
                } else {
                    cls += 'bg-slate-950/40 border-slate-900 text-slate-500';
                }

                btn.className = cls;
                btn.innerText = idx + 1;
                container.appendChild(btn);
            });
        }

        // Next/Back Actions
        function nextQuestion() {
            if (currentQuestionIdx < activeQuestions.length - 1) {
                currentQuestionIdx++;
                renderQuestion();
                renderTracker();
            } else {
                // Focus on Question tracker grid or scroll to bottom
                document.getElementById('trackerGrid').scrollIntoView({ behavior: 'smooth' });
            }
        }

        function prevQuestion() {
            if (currentQuestionIdx > 0) {
                currentQuestionIdx--;
                renderQuestion();
                renderTracker();
            }
        }

        // Submit & Process Score
        function submitTest() {
            if (timerInterval) clearInterval(timerInterval);

            // Calculate Score
            let correctCount = 0;
            activeQuestions.forEach((q, idx) => {
                if (studentAnswers[idx] !== null && studentAnswers[idx].trim() === q.correct_answer.trim()) {
                    correctCount++;
                }
            });

            // Set UI details
            document.getElementById('scoreText').innerText = correctCount;
            document.getElementById('scoreTotalText').innerText = `Of ${activeQuestions.length}`;

            // Messages
            let msg = 'Great practice session!';
            if (correctCount === activeQuestions.length) msg = 'Perfect Score! Exceptional Work!';
            else if (correctCount >= activeQuestions.length * 0.8) msg = 'Excellent Job! You are well prepared!';
            else if (correctCount >= activeQuestions.length * 0.5) msg = 'Good Effort! Keep practicing to improve!';
            else msg = 'Needs improvement. Practice makes perfect!';
            document.getElementById('performanceMessage').innerText = msg;

            // Meta Details
            document.getElementById('resName').innerText = studentProfile.name;
            document.getElementById('resSbte').innerText = studentProfile.sbte_reg_no;
            document.getElementById('resClassBatch').innerText = `${studentProfile.classroom_name} / ${studentProfile.batch}`;
            
            const now = new Date();
            document.getElementById('resDateTime').innerText = now.toLocaleString();
            
            const subjCode = document.getElementById('subjectSelect').value;
            const subj = subjects.find(s => s.subject_code === subjCode);
            document.getElementById('resSubject').innerText = `${subjCode} - ${subj ? subj.subject_name : ''}`;

            // Render Answer Key Review List
            const reviewContainer = document.getElementById('reviewList');
            reviewContainer.innerHTML = '';

            activeQuestions.forEach((q, idx) => {
                const isCorrect = studentAnswers[idx] !== null && studentAnswers[idx].trim() === q.correct_answer.trim();
                const reviewBox = document.createElement('div');
                reviewBox.className = `p-4 rounded-xl border ${
                    isCorrect 
                        ? 'bg-emerald-950/15 border-emerald-500/20 text-emerald-400' 
                        : 'bg-rose-950/15 border-rose-500/20 text-rose-400'
                } text-sm font-semibold space-y-2`;

                reviewBox.innerHTML = `
                    <div class="flex justify-between items-start gap-2">
                        <span class="font-extrabold text-slate-200">Q${idx + 1}: ${q.question_text}</span>
                        <span class="text-[10px] font-black uppercase px-2 py-0.5 rounded ${
                            isCorrect ? 'bg-emerald-500/20 text-emerald-400' : 'bg-rose-500/20 text-rose-400'
                        }">${isCorrect ? 'Correct' : 'Incorrect'}</span>
                    </div>
                    <div class="text-xs space-y-1 mt-2 text-slate-300">
                        <div>Your Answer: <span class="${isCorrect ? 'text-emerald-400' : 'text-rose-400'} font-bold">${studentAnswers[idx] || 'Not Answered'}</span></div>
                        <div>Correct Answer: <span class="text-emerald-400 font-bold">${q.correct_answer}</span></div>
                    </div>
                `;
                reviewContainer.appendChild(reviewBox);
            });

            // Transition Section Views
            document.getElementById('testSection').classList.add('hidden');
            document.getElementById('resultsSection').classList.remove('hidden');
        }
    </script>
</body>
</html>
