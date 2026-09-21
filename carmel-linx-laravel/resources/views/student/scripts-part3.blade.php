          dot.className = "w-8 h-8 rounded-lg text-xs font-semibold border bg-emerald-50 text-emerald-800 border-emerald-300";
        } else {
          dot.className = "w-8 h-8 rounded-lg text-xs font-semibold border bg-white text-slate-700 border-slate-200 hover:bg-slate-100";
        }
      });
    }

    function recordMockAnswer(qIdx, opt) {
      mockStudentAnswers[qIdx] = opt;
      displayMockCurrentQuestion();
    }

    function jumpToMockQuestion(idx) {
      mockCurrentIdx = idx;
      displayMockCurrentQuestion();
    }

    function navigateMockNextQuestion() {
      if (mockCurrentIdx < mockQuestions.length - 1) {
        mockCurrentIdx++;
        displayMockCurrentQuestion();
      }
    }

    function navigateMockPrevQuestion() {
      if (mockCurrentIdx > 0) {
        mockCurrentIdx--;
        displayMockCurrentQuestion();
      }
    }

    function startMockTestTimer() {
      clearInterval(mockTimerInterval);
      mockTimerInterval = setInterval(() => {
        mockRemainingSeconds--;
        const mins = Math.floor(mockRemainingSeconds / 60);
        const secs = mockRemainingSeconds % 60;
        document.getElementById('mockTestTimer').innerText = `${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
        if (mockRemainingSeconds <= 0) {
          clearInterval(mockTimerInterval);
          alert('Time up! Submitting practice test automatically.');
          submitMockFullTest();
        }
      }, 1000);
    }

    function submitMockFullTest() {
      if (!confirm('Are you sure you want to submit your practice test?')) return;
      clearInterval(mockTimerInterval);

      let correctCount = 0;
      mockQuestions.forEach((q, idx) => {
        if (mockStudentAnswers[idx] && mockStudentAnswers[idx].trim().toLowerCase() === (q.correct_answer || '').trim().toLowerCase()) {
          correctCount++;
        }
      });

      document.getElementById('mockExamSection').classList.add('hidden');
      document.getElementById('mockResultSection').classList.remove('hidden');

      const total = mockQuestions.length;
      const pct = Math.round((correctCount / total) * 100);

      document.getElementById('mockFinalScoreText').innerText = `${correctCount} / ${total}`;
      document.getElementById('mockFinalPercentageText').innerText = `${pct}% Proficiency`;

      const reviewBox = document.getElementById('mockDetailedReviewList');
      reviewBox.innerHTML = mockQuestions.map((q, idx) => {
        const ans = mockStudentAnswers[idx];
        const isCorrect = ans && (ans.trim().toLowerCase() === (q.correct_answer || '').trim().toLowerCase());
        return `
          <div class="p-4 rounded-xl border ${isCorrect ? 'bg-emerald-50/40 border-emerald-200' : 'bg-rose-50/40 border-rose-200'} space-y-2">
            <p class="text-xs font-bold text-slate-900">Q${idx + 1}: ${q.question_text || q.question}</p>
            <p class="text-[11px] text-slate-600">Your Answer: <strong class="${isCorrect ? 'text-emerald-700' : 'text-rose-700'}">${ans || 'Not Answered'}</strong></p>
            ${!isCorrect ? `<p class="text-[11px] text-emerald-700">Correct Answer: <strong>${q.correct_answer}</strong></p>` : ''}
          </div>
        `;
      }).join('');
      if (window.initLucide) window.initLucide();
    }

    function resetMockPracticeTest() {
      document.getElementById('mockResultSection').classList.add('hidden');
      document.getElementById('mockSetupSection').classList.remove('hidden');
      loadMockSubjects();
    }

    requestAnimationFrame(function() {
      document.body.classList.remove('sidebar-preload');
    });
  </script>
</body>
</html>
