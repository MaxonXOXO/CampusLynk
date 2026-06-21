import { dbService, StaffProfile, StudentProfile } from './db';
import { supabase } from '@/lib/supabase';

export interface AuthResponse {
  status: 'SUCCESS' | 'ERROR';
  message?: string;
  role?: string;
  id?: string;
  name?: string;
  email?: string;
  branch?: string;
  designation?: string;
  photo?: string;
  classroomId?: string;
  semester?: string;
  sbteRegNo?: string;
}

export const authService = {
  /**
   * Log in a student or staff member.
   */
  async loginUser(userId: string, password: string, roleType: 'student' | 'staff'): Promise<AuthResponse> {
    try {
      const cleanId = userId.trim();
      const cleanPw = password.trim();

      if (roleType === 'student') {
        const { data: student, error } = await supabase
          .from('students')
          .select('*')
          .or(`reg_no.eq.${cleanId.toUpperCase()},adm_no.eq.${cleanId.toUpperCase()}`)
          .maybeSingle();

        if (error) throw error;
        if (!student || student.password !== cleanPw) {
          return { status: 'ERROR', message: 'Invalid ID/Admission Number or Password.' };
        }

        if (student.status && student.status.toUpperCase() !== 'APPROVED') {
          return { status: 'ERROR', message: 'Your registration is pending approval by your Class Tutor.' };
        }

        return {
          status: 'SUCCESS',
          role: 'Student',
          id: student.reg_no,
          name: student.name || 'Student',
          email: student.email,
          branch: student.branch,
          semester: 'S1', // Can be expanded relationally
          classroomId: student.classroom_id,
          photo: student.photo_url || 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?w=150',
          sbteRegNo: student.sbte_reg_no || ''
        };
      } else {
        // Staff Profile
        const cleanMobile = cleanId.replace(/[^0-9]/g, '');
        const { data: staff, error } = await supabase
          .from('staff_profiles')
          .select('*')
          .eq('mobile_no', cleanMobile)
          .maybeSingle();

        if (error) throw error;
        if (!staff || staff.password !== cleanPw) {
          return { status: 'ERROR', message: 'Invalid Mobile Number or Password.' };
        }

        if (staff.account_status && staff.account_status.toUpperCase() !== 'APPROVED') {
          return { status: 'ERROR', message: 'Your staff account is pending approval by Super Admin.' };
        }

        return {
          status: 'SUCCESS',
          role: staff.designation,
          id: staff.mobile_no,
          name: staff.name || staff.designation || 'Staff Member',
          email: staff.email,
          branch: staff.branch,
          designation: staff.designation,
          photo: staff.photo_url || 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=150'
        };
      }
    } catch (err: any) {
      return { status: 'ERROR', message: err.message || err.toString() };
    }
  },

  /**
   * Register a new student. Handles LET classroom mapping.
   */
  async registerStudent(studentData: {
    regNo: string;
    admNo: string;
    name: string;
    email: string;
    phone: string;
    branch: string;
    admissionYear: string;
    admissionType: 'Regular' | 'LET';
    password: string;
    photoUrl?: string;
    sbteRegNo?: string;
  }): Promise<AuthResponse> {
    try {
      const regNo = studentData.regNo.trim().toUpperCase();
      const admNo = studentData.admNo.trim().toUpperCase();
      const email = studentData.email.trim();

      // Email domain restriction
      const domainMatch = /@carmelpoly\.(in|edu\.in)$/i.test(email);
      if (!domainMatch) {
        return {
          status: 'ERROR',
          message: 'Student registration requires a college email ID (e.g., student@carmelpoly.in).'
        };
      }

      // Check duplicates
      const { data: existing, error: checkError } = await supabase
        .from('students')
        .select('reg_no, adm_no')
        .or(`reg_no.eq.${regNo},adm_no.eq.${admNo}`)
        .maybeSingle();

      if (checkError) throw checkError;
      if (existing) {
        return { status: 'ERROR', message: 'A student with this Register Number or Admission Number already exists.' };
      }

      // Resolve classroom ID
      const branchCode = studentData.branch.trim().toUpperCase();
      const admYear = parseInt(studentData.admissionYear.trim());
      const isLET = studentData.admissionType === 'LET';
      const startYear = isLET ? admYear - 1 : admYear;
      const endYear = startYear + 3;
      const classroomId = `${branchCode}_${startYear}_${endYear}`;

      // Insert Student Registry Roster
      const newStudent: StudentProfile = {
        reg_no: regNo,
        adm_no: admNo,
        name: studentData.name.trim(),
        email: email,
        password: studentData.password.trim(),
        phone: studentData.phone || '',
        branch: branchCode,
        admission_year: admYear,
        admission_type: studentData.admissionType || 'Regular',
        photo_url: studentData.photoUrl || 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?w=150',
        classroom_id: classroomId,
        status: 'Pending',
        sbte_reg_no: studentData.sbteRegNo || ''
      };

      const { error: insertError } = await supabase.from('students').insert([newStudent]);
      if (insertError) throw insertError;

      return {
        status: 'SUCCESS',
        message: 'Registration successful! Pending Class Tutor approval.',
        classroomId: classroomId
      };
    } catch (err: any) {
      return { status: 'ERROR', message: err.message || err.toString() };
    }
  },

  /**
   * Register a new staff member. Restricts Principal and Coordinator counts.
   */
  async registerStaff(staffData: {
    mobileNo: string;
    name: string;
    email: string;
    branch: string;
    designation: string;
    password: string;
    photoUrl?: string;
  }): Promise<AuthResponse> {
    try {
      const email = staffData.email.trim();
      const domainMatch = /@carmelpoly\.(in|edu\.in)$/i.test(email);
      if (!domainMatch) {
        return {
          status: 'ERROR',
          message: 'Staff registration requires a college email ID (e.g., user@carmelpoly.in).'
        };
      }

      const mobileNo = staffData.mobileNo.replace(/[^0-9]/g, '');

      // Check duplicates
      const { data: existing, error: checkError } = await supabase
        .from('staff_profiles')
        .select('mobile_no')
        .eq('mobile_no', mobileNo)
        .maybeSingle();

      if (checkError) throw checkError;
      if (existing) {
        return { status: 'ERROR', message: 'A staff profile with this mobile number already exists.' };
      }

      const designation = staffData.designation.trim();

      // Designation checks
      if (designation === 'Principal') {
        const { count, error } = await supabase
          .from('staff_profiles')
          .select('id', { count: 'exact', head: true })
          .eq('designation', 'Principal');
        
        if (error) throw error;
        if (count && count > 0) {
          return { status: 'ERROR', message: 'An active Principal profile already exists in the system.' };
        }
      }

      if (designation === 'Academic_Coordinator') {
        const { count, error } = await supabase
          .from('staff_profiles')
          .select('id', { count: 'exact', head: true })
          .eq('designation', 'Academic_Coordinator')
          .eq('account_status', 'Approved');

        if (error) throw error;
        if (count && count > 0) {
          return { status: 'ERROR', message: 'An active Academic Coordinator profile already exists in the system.' };
        }
      }

      // Auto-approve Principal
      const status = designation === 'Principal' ? 'Approved' : 'Pending';

      const { error: insertError } = await supabase.from('staff_profiles').insert([{
        mobile_no: mobileNo,
        name: staffData.name.trim(),
        email: email,
        password: staffData.password.trim(),
        branch: staffData.branch.trim().toUpperCase(),
        designation: designation,
        photo_url: staffData.photoUrl || 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=150',
        account_status: status
      }]);

      if (insertError) throw insertError;

      if (designation === 'Principal') {
        return { status: 'SUCCESS', message: 'Principal registration successful! Account is auto-approved.' };
      }

      return { status: 'SUCCESS', message: 'Staff registration submitted! Pending administrator approval.' };
    } catch (err: any) {
      return { status: 'ERROR', message: err.message || err.toString() };
    }
  },

  /**
   * Account approval actions.
   */
  async approveAccount(targetId: string, userType: 'student' | 'staff'): Promise<AuthResponse> {
    try {
      if (userType === 'student') {
        const { error } = await supabase
          .from('students')
          .update({ status: 'Approved' })
          .eq('reg_no', targetId.toUpperCase());

        if (error) throw error;
        return { status: 'SUCCESS', message: 'Student registration approved successfully.' };
      } else {
        // Staff Profile Approval
        const { data: staff, error: fetchErr } = await supabase
          .from('staff_profiles')
          .select('*')
          .eq('mobile_no', targetId)
          .maybeSingle();

        if (fetchErr) throw fetchErr;
        if (!staff) return { status: 'ERROR', message: 'Staff profile not found.' };

        if (staff.designation === 'Principal') {
          const { count } = await supabase
            .from('staff_profiles')
            .select('id', { count: 'exact', head: true })
            .eq('designation', 'Principal')
            .eq('account_status', 'Approved');

          if (count && count > 0) {
            return { status: 'ERROR', message: 'Another Principal has already been approved.' };
          }
        }

        const { error } = await supabase
          .from('staff_profiles')
          .update({ account_status: 'Approved' })
          .eq('mobile_no', targetId);

        if (error) throw error;
        return { status: 'SUCCESS', message: 'Staff registration approved successfully.' };
      }
    } catch (err: any) {
      return { status: 'ERROR', message: err.message || err.toString() };
    }
  }
};
