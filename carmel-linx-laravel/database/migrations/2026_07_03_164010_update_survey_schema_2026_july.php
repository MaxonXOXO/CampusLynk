<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add faculty_name to mid_semester_surveys.
     * Expand student_survey_responses with full 12-question SAR survey schema.
     * OLD score columns are kept (made nullable) so lecturer dashboard charts are unaffected.
     */
    public function up(): void
    {
        // 1. Add faculty_name to surveys table
        Schema::table('mid_semester_surveys', function (Blueprint $table) {
            $table->string('faculty_name', 150)->nullable()->after('batch_subject_id');
        });

        // 2. Expand student_survey_responses
        Schema::table('student_survey_responses', function (Blueprint $table) {
            // Make old columns nullable (backward compat — lecturer dashboard still reads these)
            $table->tinyInteger('pace_score')->nullable()->change();
            $table->tinyInteger('clarity_score')->nullable()->change();
            $table->tinyInteger('interaction_score')->nullable()->change();
            $table->tinyInteger('practicality_score')->nullable()->change();
            $table->tinyInteger('evaluation_score')->nullable()->change();

            // Section 2: Core Teaching Questions (Q5–Q12), all 1–3 scale
            $table->tinyInteger('q5_co_communication')->nullable()->after('evaluation_score')
                  ->comment('Q5: Teacher communicates COs and learning goals. 1-3');
            $table->tinyInteger('q6_syllabus_pace')->nullable()->after('q5_co_communication')
                  ->comment('Q6: Pace/speed/coverage of syllabus appropriate. 1-3');
            $table->tinyInteger('q7_concept_clarity')->nullable()->after('q6_syllabus_pace')
                  ->comment('Q7: Complex concepts clarity + real-world links. 1-3');
            $table->tinyInteger('q8_teaching_tools')->nullable()->after('q7_concept_clarity')
                  ->comment('Q8: Use of PPTs, ICT, animations, demos. 1-3');
            $table->tinyInteger('q9_student_interaction')->nullable()->after('q8_teaching_tools')
                  ->comment('Q9: Encourages questions, manages doubts patiently. 1-3');
            $table->tinyInteger('q10_assessment_alignment')->nullable()->after('q9_student_interaction')
                  ->comment('Q10: Assessment questions match topics taught. 1-3');
            $table->tinyInteger('q11_evaluation_fairness')->nullable()->after('q10_assessment_alignment')
                  ->comment('Q11: Evaluation is fair, timely, transparent. 1-3');
            $table->tinyInteger('q12_slow_learner_support')->nullable()->after('q11_evaluation_fairness')
                  ->comment('Q12: Remedial guidance for slow learners. 1-3');

            // Section 3: Branch-Specific Lab/Practical (Q13), one per student
            $table->tinyInteger('q13_branch_specific')->nullable()->after('q12_slow_learner_support')
                  ->comment('Q13: Branch lab/practical demonstration effectiveness. 1-3 (optional)');

            // Section 4: Open-Ended Feedback (optional)
            $table->text('q17_difficult_topics')->nullable()->after('q13_branch_specific')
                  ->comment('Q17: Topics found most difficult');
            $table->text('q18_suggestions')->nullable()->after('q17_difficult_topics')
                  ->comment('Q18: Suggestions to improve delivery');
        });
    }

    public function down(): void
    {
        Schema::table('mid_semester_surveys', function (Blueprint $table) {
            $table->dropColumn('faculty_name');
        });

        Schema::table('student_survey_responses', function (Blueprint $table) {
            $table->dropColumn([
                'q5_co_communication', 'q6_syllabus_pace', 'q7_concept_clarity',
                'q8_teaching_tools', 'q9_student_interaction', 'q10_assessment_alignment',
                'q11_evaluation_fairness', 'q12_slow_learner_support',
                'q13_branch_specific', 'q17_difficult_topics', 'q18_suggestions',
            ]);

            // Restore NOT NULL on old columns
            $table->tinyInteger('pace_score')->nullable(false)->change();
            $table->tinyInteger('clarity_score')->nullable(false)->change();
            $table->tinyInteger('interaction_score')->nullable(false)->change();
            $table->tinyInteger('practicality_score')->nullable(false)->change();
            $table->tinyInteger('evaluation_score')->nullable(false)->change();
        });
    }
};

