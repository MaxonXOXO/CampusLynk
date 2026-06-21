import { supabase } from '@/lib/supabase';

// Type definitions matching database schema
export interface StaffProfile {
  id?: string;
  mobile_no: string;
  name: string;
  email: string;
  branch: string;
  designation: string;
  photo_url?: string;
  account_status?: 'Pending' | 'Approved' | 'Suspended';
}

export interface StudentProfile {
  reg_no: string;
  adm_no: string;
  name: string;
  email?: string;
  password?: string;
  phone?: string;
  branch: string;
  admission_year: number;
  admission_type?: 'Regular' | 'LET';
  photo_url?: string;
  classroom_id?: string;
  status?: string;
  sbte_reg_no?: string;
  mentor_mobile_no?: string;
}

export interface ClassManagement {
  classroom_id: string;
  branch: string;
  batch_year: number;
  tutor_mobile_no?: string;
  mentor_mobile_no?: string;
}

// Relational DB Actions mapping to old DataService.gs
export const dbService = {
  // --- STAFF PROFILES ---
  async getStaffProfiles(): Promise<StaffProfile[]> {
    const { data, error } = await supabase
      .from('staff_profiles')
      .select('*')
      .order('name', { ascending: true });
    
    if (error) throw error;
    return data || [];
  },

  async getStaffByMobile(mobile: string): Promise<StaffProfile | null> {
    const { data, error } = await supabase
      .from('staff_profiles')
      .select('*')
      .eq('mobile_no', mobile.replace(/[^0-9]/g, ''))
      .maybeSingle();

    if (error) throw error;
    return data;
  },

  async createStaff(staff: StaffProfile): Promise<StaffProfile> {
    const { data, error } = await supabase
      .from('staff_profiles')
      .insert([staff])
      .select()
      .single();

    if (error) throw error;
    return data;
  },

  async updateStaffProfile(mobile: string, updates: Partial<StaffProfile>): Promise<StaffProfile> {
    const { data, error } = await supabase
      .from('staff_profiles')
      .update(updates)
      .eq('mobile_no', mobile.replace(/[^0-9]/g, ''))
      .select()
      .single();

    if (error) throw error;
    return data;
  },

  async deleteStaff(mobile: string): Promise<void> {
    const { error } = await supabase
      .from('staff_profiles')
      .delete()
      .eq('mobile_no', mobile.replace(/[^0-9]/g, ''));

    if (error) throw error;
  },

  // --- STUDENTS ---
  async getStudents(classroomId?: string): Promise<StudentProfile[]> {
    let query = supabase.from('students').select('*');
    if (classroomId) {
      query = query.eq('classroom_id', classroomId);
    }
    const { data, error } = await query.order('name', { ascending: true });
    
    if (error) throw error;
    return data || [];
  },

  async getStudentByRegNo(regNo: string): Promise<StudentProfile | null> {
    const { data, error } = await supabase
      .from('students')
      .select('*')
      .eq('reg_no', regNo.toUpperCase())
      .maybeSingle();

    if (error) throw error;
    return data;
  },

  async createStudent(student: StudentProfile): Promise<StudentProfile> {
    const { data, error } = await supabase
      .from('students')
      .insert([student])
      .select()
      .single();

    if (error) throw error;
    return data;
  },

  async updateStudentProfile(regNo: string, updates: Partial<StudentProfile>): Promise<StudentProfile> {
    const { data, error } = await supabase
      .from('students')
      .update(updates)
      .eq('reg_no', regNo.toUpperCase())
      .select()
      .single();

    if (error) throw error;
    return data;
  },

  async deleteStudent(regNo: string): Promise<void> {
    const { error } = await supabase
      .from('students')
      .delete()
      .eq('reg_no', regNo.toUpperCase());

    if (error) throw error;
  },

  // --- CLASSROOMS ---
  async getClassrooms(): Promise<ClassManagement[]> {
    const { data, error } = await supabase
      .from('class_management')
      .select('*')
      .order('classroom_id', { ascending: true });
    
    if (error) throw error;
    return data || [];
  },

  async createClassroom(classroom: ClassManagement): Promise<ClassManagement> {
    const { data, error } = await supabase
      .from('class_management')
      .insert([classroom])
      .select()
      .single();

    if (error) throw error;
    return data;
  },

  // --- LOGGING SYSTEM ---
  async logSystemActivity(userType: string, action: string, details: string): Promise<void> {
    const logEntry = {
      reg_no: userType,
      test_id: null,
      action: action,
      details: details,
      timestamp: new Date().toISOString()
    };
    
    // In PostgreSQL, these logs map to academic_marks or a system audit log table.
    // If you created a test_logs table:
    const { error } = await supabase
      .from('academic_marks') // Or dedicated audit logs table if exists
      .insert([{
        reg_no: userType,
        subject_code: 'SYSTEM',
        category: action,
        co_tag: 'SYSTEM',
        max_marks: 0,
        marks_obtained: 0,
        entered_by: userType,
        timestamp: new Date().toISOString()
      }]);

    if (error) {
      console.error('Failed to write system log: ', error);
    }
  }
};
