-- ============================================================================
-- CARMEL LINX - SYSTEM DATABASE SCHEMA (PostgreSQL / Supabase)
-- ============================================================================

-- Enable UUID extension if not enabled
CREATE EXTENSION IF NOT EXISTS "uuid-ossp";

-- 1. PO Config Table
CREATE TABLE IF NOT EXISTS po_config (
    po_id VARCHAR(10) PRIMARY KEY,
    po_name VARCHAR(255) NOT NULL,
    description TEXT
);

-- 2. Staff Profiles Table
CREATE TABLE IF NOT EXISTS staff_profiles (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    mobile_no VARCHAR(15) UNIQUE NOT NULL,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    branch VARCHAR(50) NOT NULL,
    designation VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL,
    photo_url TEXT,
    account_status VARCHAR(50) DEFAULT 'Pending' CHECK (account_status IN ('Pending', 'Approved', 'Suspended')),
    created_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

-- 3. Class Management Table
CREATE TABLE IF NOT EXISTS class_management (
    classroom_id VARCHAR(50) PRIMARY KEY, -- e.g., 'EL_2025_28'
    branch VARCHAR(50) NOT NULL,
    batch_year INTEGER NOT NULL,
    tutor_mobile_no VARCHAR(15) REFERENCES staff_profiles(mobile_no) ON DELETE SET NULL,
    mentor_mobile_no VARCHAR(15) REFERENCES staff_profiles(mobile_no) ON DELETE SET NULL
);

-- 4. Syllabus Registry Table
CREATE TABLE IF NOT EXISTS syllabus_registry (
    subject_code VARCHAR(50) PRIMARY KEY,
    revision_year INTEGER NOT NULL,
    subject_name VARCHAR(255) NOT NULL,
    co_count INTEGER DEFAULT 6
);

-- 5. Branch Configuration Table
CREATE TABLE IF NOT EXISTS branch_config (
    branch_code VARCHAR(10) PRIMARY KEY,
    vision TEXT,
    mission TEXT,
    peos TEXT[],
    psos TEXT[]
);

-- 6. Institution Configuration Table
CREATE TABLE IF NOT EXISTS institution_config (
    config_key VARCHAR(100) PRIMARY KEY,
    config_value TEXT NOT NULL
);

-- 7. Student Registry Table
CREATE TABLE IF NOT EXISTS students (
    reg_no VARCHAR(50) PRIMARY KEY,
    adm_no VARCHAR(50) UNIQUE NOT NULL,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(15),
    branch VARCHAR(50) NOT NULL,
    admission_year INTEGER NOT NULL,
    admission_type VARCHAR(50), -- e.g., 'Regular', 'Lateral'
    photo_url TEXT,
    classroom_id VARCHAR(50) REFERENCES class_management(classroom_id) ON DELETE SET NULL,
    status VARCHAR(50) DEFAULT 'Approved',
    sbte_reg_no VARCHAR(50),
    mentor_mobile_no VARCHAR(15) REFERENCES staff_profiles(mobile_no) ON DELETE SET NULL,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

-- 8. Question Bank Table
CREATE TABLE IF NOT EXISTS question_bank (
    question_id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    branch_code VARCHAR(10) NOT NULL,
    subject_code VARCHAR(50) REFERENCES syllabus_registry(subject_code) ON DELETE CASCADE,
    type VARCHAR(20) NOT NULL CHECK (type IN ('MCQ', 'Descriptive')),
    question_text TEXT NOT NULL,
    options JSONB, -- Array of strings for MCQ options
    correct_answer TEXT, -- Index or text of correct answer for MCQs
    co_tag VARCHAR(10) NOT NULL, -- e.g., 'CO1', 'CO2'
    marks INTEGER NOT NULL,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

-- 9. Active Test Configuration Table
CREATE TABLE IF NOT EXISTS test_configs (
    test_id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    subject_code VARCHAR(50) REFERENCES syllabus_registry(subject_code) ON DELETE CASCADE,
    classroom_id VARCHAR(50) REFERENCES class_management(classroom_id) ON DELETE CASCADE,
    test_name VARCHAR(255) NOT NULL,
    start_time TIMESTAMP WITH TIME ZONE NOT NULL,
    end_time TIMESTAMP WITH TIME ZONE NOT NULL,
    duration INTEGER NOT NULL, -- Duration in minutes
    selected_cos VARCHAR(10)[] NOT NULL,
    mcq_count INTEGER DEFAULT 0,
    descriptive_count INTEGER DEFAULT 0,
    target_percentage INTEGER DEFAULT 50,
    pass_threshold INTEGER DEFAULT 40,
    is_active BOOLEAN DEFAULT false,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

-- 10. Student Exam Responses Table
CREATE TABLE IF NOT EXISTS student_responses (
    response_id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    reg_no VARCHAR(50) REFERENCES students(reg_no) ON DELETE CASCADE,
    test_id UUID REFERENCES test_configs(test_id) ON DELETE CASCADE,
    question_id UUID REFERENCES question_bank(question_id) ON DELETE CASCADE,
    selected_option VARCHAR(10), -- For MCQs
    descriptive_text TEXT, -- For Descriptive questions
    marks_obtained DECIMAL(5,2) DEFAULT 0.0,
    evaluated_by VARCHAR(15) REFERENCES staff_profiles(mobile_no) ON DELETE SET NULL,
    status VARCHAR(50) DEFAULT 'Submitted' CHECK (status IN ('Submitted', 'Saved', 'Evaluated')),
    submitted_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT unique_student_question_response UNIQUE (reg_no, test_id, question_id)
);

-- 11. Cumulative Academic Marks Table (For final grade calculation and historical storage)
CREATE TABLE IF NOT EXISTS academic_marks (
    mark_id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    reg_no VARCHAR(50) REFERENCES students(reg_no) ON DELETE CASCADE,
    subject_code VARCHAR(50) REFERENCES syllabus_registry(subject_code) ON DELETE CASCADE,
    category VARCHAR(50) NOT NULL, -- e.g., 'Model Exam', 'Assignment', 'Lab'
    co_tag VARCHAR(10) NOT NULL,
    max_marks INTEGER NOT NULL,
    marks_obtained DECIMAL(5,2) NOT NULL,
    entered_by VARCHAR(15) REFERENCES staff_profiles(mobile_no) ON DELETE SET NULL,
    timestamp TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

-- 12. Tutor Diary Notes Table
CREATE TABLE IF NOT EXISTS tutor_diary (
    diary_id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    reg_no VARCHAR(50) REFERENCES students(reg_no) ON DELETE CASCADE,
    date DATE NOT NULL DEFAULT CURRENT_DATE,
    category VARCHAR(100) NOT NULL, -- e.g., 'Academic Performance', 'Behavioral Issues'
    discussion_notes TEXT NOT NULL,
    action_taken TEXT,
    remarks TEXT,
    logged_by VARCHAR(15) REFERENCES staff_profiles(mobile_no) ON DELETE SET NULL,
    timestamp TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

-- Indexes for performance optimization under high concurrency
CREATE INDEX IF NOT EXISTS idx_students_classroom ON students(classroom_id);
CREATE INDEX IF NOT EXISTS idx_question_bank_subject ON question_bank(subject_code);
CREATE INDEX IF NOT EXISTS idx_test_configs_class ON test_configs(classroom_id);
CREATE INDEX IF NOT EXISTS idx_student_responses_lookup ON student_responses(reg_no, test_id);
CREATE INDEX IF NOT EXISTS idx_academic_marks_lookup ON academic_marks(reg_no, subject_code);
