<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('r26_question_bank', function (Blueprint $table) {
            $table->id();
            $table->string('subject_code', 30)->index();
            $table->unsignedBigInteger('batch_subject_id')->nullable()->index();
            $table->string('series_no', 20)->nullable();        // Series 1, Series 2 etc.
            $table->string('pattern_type', 40)->default('table_4_1_standard'); // table_4_1_standard | table_4_2_design
            $table->string('part', 10)->default('part_a');      // part_a | part_b | part_c
            $table->string('q_no', 15)->nullable();             // 1, 2, 11(a) etc.
            $table->text('question_text');
            $table->unsignedTinyInteger('marks')->default(1);
            $table->string('co_tag', 10)->default('CO1');        // CO1 – CO4
            $table->string('bloom_level', 5)->default('L1');     // L1 – L6
            $table->string('choice_group', 20)->nullable();      // Set 1, Set 2 (Part C / Part B design)
            $table->text('scheme_key')->nullable();              // Marking key / scheme text
            $table->text('answer_key')->nullable();              // Model answer text
            $table->boolean('is_active')->default(true);
            $table->string('created_by', 20)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('r26_question_bank');
    }
};
