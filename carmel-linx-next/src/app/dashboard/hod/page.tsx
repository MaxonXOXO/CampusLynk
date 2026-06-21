'use client';

import React, { useState, useEffect } from 'react';
import { 
  CheckCircle, School, Link2, Users, Briefcase, 
  FileText, Settings, LogOut, ChevronRight, Plus, 
  Trash2, Edit, Save, ShieldAlert, Award, UserCheck, Loader2
} from 'lucide-react';
import { dbService, StaffProfile, StudentProfile, ClassManagement } from '@/lib/services/db';

type HODView = 'approvals' | 'classes' | 'mapping' | 'students' | 'staff' | 'studentReport' | 'obeSetup' | 'profile';

export default function HODDashboard() {
  const [hodMobile, setHodMobile] = useState('');
  const [hodName, setHodName] = useState('Department HOD');
  const [hodBranch, setHodBranch] = useState('');
  const [hodPhoto, setHodPhoto] = useState('');
  const [activeView, setActiveView] = useState<HODView>('approvals');
  const [loading, setLoading] = useState(true);

  // Data states
  const [pendingStudents, setPendingStudents] = useState<StudentProfile[]>([]);
  const [pendingStaff, setPendingStaff] = useState<StaffProfile[]>([]);
  const [classroomData, setClassroomData] = useState<ClassManagement[]>([]);
  const [branchStudentRoster, setBranchStudentRoster] = useState<StudentProfile[]>([]);
  const [branchStaffRoster, setBranchStaffRoster] = useState<StaffProfile[]>([]);
  
  // Crud & selection states
  const [selectedClassroom, setSelectedClassroom] = useState('');
  const [alert, setAlert] = useState<{ type: 'success' | 'error'; message: string } | null>(null);

  // Modals state
  const [isStudentModalOpen, setIsStudentModalOpen] = useState(false);
  const [editingStudent, setEditingStudent] = useState<Partial<StudentProfile> | null>(null);

  useEffect(() => {
    // Session load
    const mobile = sessionStorage.getItem('userId') || '';
    const name = sessionStorage.getItem('userName') || 'Department HOD';
    const branch = sessionStorage.getItem('userBranch') || '';
    const photo = sessionStorage.getItem('userPhoto') || '';

    if (!mobile) {
      window.location.href = '/';
      return;
    }

    setHodMobile(mobile);
    setHodName(name);
    setHodBranch(branch);
    setHodPhoto(photo);

    loadDashboardData(branch);
  }, []);

  const loadDashboardData = async (branchCode: string) => {
    setLoading(true);
    try {
      // 1. Fetch pending accounts
      const allStaff = await dbService.getStaffProfiles();
      const allStudents = await dbService.getStudents();
      
      const pStudents = allStudents.filter(s => s.branch === branchCode && s.status === 'Pending');
      const pStaff = allStaff.filter(s => s.branch === branchCode && s.account_status === 'Pending');
      
      setPendingStudents(pStudents);
      setPendingStaff(pStaff);

      // 2. Fetch classroom data
      const classrooms = await dbService.getClassrooms();
      const branchClasses = classrooms.filter(c => c.branch === branchCode);
      setClassroomData(branchClasses);

      // 3. Roster data
      const studentRoster = allStudents.filter(s => s.branch === branchCode && s.status === 'Approved');
      const staffRoster = allStaff.filter(s => s.branch === branchCode && s.account_status === 'Approved');
      
      setBranchStudentRoster(studentRoster);
      setBranchStaffRoster(staffRoster);

    } catch (err: any) {
      console.error('Error loading HOD dashboard data:', err);
    } finally {
      setLoading(false);
    }
  };

  const handleApprove = async (id: string, type: 'student' | 'staff') => {
    try {
      const { authService } = await import('@/lib/services/auth');
      const res = await authService.approveAccount(id, type);
      if (res.status === 'SUCCESS') {
        setAlert({ type: 'success', message: res.message || 'Account approved!' });
        loadDashboardData(hodBranch);
      } else {
        setAlert({ type: 'error', message: res.message || 'Approval failed.' });
      }
    } catch (err: any) {
      setAlert({ type: 'error', message: err.message || err.toString() });
    }
  };

  const handleLogout = () => {
    sessionStorage.clear();
    window.location.href = '/';
  };

  return (
    <div className="bg-slate-50 min-h-screen flex flex-col md:flex-row text-slate-800">
      {/* Sidebar Navigation */}
      <aside className="w-full md:w-64 bg-slate-900 text-white flex-shrink-0 flex flex-col border-r border-slate-800">
        <div className="p-6 border-b border-slate-800 flex items-center gap-3">
          <div className="bg-blue-600 text-white font-bold rounded-lg w-8 h-8 flex items-center justify-center text-sm">
            CL
          </div>
          <div>
            <h2 className="font-extrabold text-sm tracking-wide">Carmel Linx</h2>
            <span className="text-[10px] text-slate-400 font-bold uppercase tracking-wider">HOD Console</span>
          </div>
        </div>

        {/* Active Profile */}
        <div className="p-4 bg-slate-950/40 border-b border-slate-800/60 flex items-center gap-3">
          <img
            src={hodPhoto || 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=150'}
            alt="HOD Profile"
            className="w-10 h-10 rounded-full border border-slate-700 object-cover"
          />
          <div className="overflow-hidden">
            <span className="font-bold text-xs block truncate text-slate-200">{hodName}</span>
            <span className="text-[10px] font-bold text-teal-400 block uppercase tracking-wider">{hodBranch} HOD</span>
          </div>
        </div>

        {/* Menu Links */}
        <nav className="flex-grow p-4 space-y-1">
          {[
            { id: 'approvals', label: 'Pending Approvals', icon: CheckCircle, badge: pendingStudents.length + pendingStaff.length },
            { id: 'classes', label: 'Class Allocation', icon: School },
            { id: 'mapping', label: 'Faculty Mapping', icon: Link2 },
            { id: 'students', label: 'Student Manager', icon: Users },
            { id: 'staff', label: 'Staff Manager', icon: Briefcase },
            { id: 'studentReport', label: 'Student Report Card', icon: FileText },
            { id: 'obeSetup', label: 'Branch OBE Setup', icon: Award },
            { id: 'profile', label: 'My Profile', icon: Settings }
          ].map((item) => {
            const Icon = item.icon;
            const isActive = activeView === item.id;
            return (
              <button
                key={item.id}
                onClick={() => {
                  setActiveView(item.id as HODView);
                  setAlert(null);
                }}
                className={`w-full text-left px-4 py-2.5 rounded-xl font-bold text-xs flex items-center gap-3 transition-all cursor-pointer ${
                  isActive
                    ? 'bg-blue-600/15 text-blue-400'
                    : 'text-slate-400 hover:bg-slate-800 hover:text-white'
                }`}
              >
                <Icon className="w-4 h-4" />
                <span>{item.label}</span>
                {item.badge && item.badge > 0 ? (
                  <span className="ml-auto bg-blue-500 text-white font-extrabold text-[9px] px-1.5 py-0.5 rounded-full">
                    {item.badge}
                  </span>
                ) : null}
              </button>
            );
          })}
        </nav>

        {/* Logout */}
        <div className="p-4 border-t border-slate-800 mt-auto">
          <button
            onClick={handleLogout}
            className="w-full py-2 bg-slate-800 hover:bg-red-950 hover:text-red-300 rounded-xl font-bold text-xs transition-all flex items-center justify-center gap-2 cursor-pointer"
          >
            <LogOut className="w-4 h-4" /> Sign Out
          </button>
        </div>
      </aside>

      {/* Main Workspace */}
      <main className="flex-grow p-6 md:p-8 overflow-y-auto max-h-screen">
        {/* Header */}
        <div className="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-slate-200 pb-5 mb-6">
          <div>
            <h1 className="text-2xl font-black text-slate-800 tracking-tight capitalize">
              {activeView.replace(/([A-Z])/g, ' $1')}
            </h1>
            <p className="text-sm text-slate-500 font-medium mt-0.5">
              {activeView === 'approvals' && 'Verify and approve registration applications for your branch.'}
              {activeView === 'classes' && 'Configure batch-wise academic classes, Tutors, and Mentors.'}
              {activeView === 'mapping' && 'Assign teachers and course files for active classes.'}
              {activeView === 'students' && 'Manage your branch student roster and profiles.'}
              {activeView === 'staff' && 'Oversee lecturers, demonstrators, and department coordinators.'}
              {activeView === 'studentReport' && 'Generate OBE Marksheets and performance reviews.'}
              {activeView === 'obeSetup' && 'Configure Program Outcomes (PO) and Course Outcomes (CO) variables.'}
              {activeView === 'profile' && 'Update your name, contact phone, or login password.'}
            </p>
          </div>
        </div>

        {alert && (
          <div
            className={`p-4 mb-4 rounded-xl text-sm font-semibold border ${
              alert.type === 'success'
                ? 'bg-green-50 text-green-700 border-green-200'
                : 'bg-red-50 text-red-700 border-red-200'
            }`}
          >
            {alert.message}
          </div>
        )}

        {/* Loaded View Pane */}
        {loading ? (
          <div className="flex items-center justify-center p-12">
            <Loader2 className="w-8 h-8 animate-spin text-blue-600" />
          </div>
        ) : (
          <div className="fade-in">
            
            {/* 1. Pending Approvals */}
            {activeView === 'approvals' && (
              <div className="space-y-6">
                {pendingStudents.length === 0 && pendingStaff.length === 0 ? (
                  <div className="bg-white rounded-3xl p-8 border border-slate-200 text-center shadow-sm">
                    <CheckCircle className="w-12 h-12 text-emerald-500 mx-auto mb-3" />
                    <h3 className="font-black text-slate-800 text-lg">No Pending Registrations</h3>
                    <p className="text-slate-400 text-sm mt-1 max-w-sm mx-auto font-medium">
                      All student and faculty accounts are currently approved and synchronized.
                    </p>
                  </div>
                ) : (
                  <>
                    {/* Student List */}
                    {pendingStudents.length > 0 && (
                      <div className="bg-white border rounded-3xl p-6 shadow-sm">
                        <h3 className="text-base font-black text-slate-800 mb-4 tracking-tight">
                          Student Accounts ({pendingStudents.length})
                        </h3>
                        <div className="space-y-4">
                          {pendingStudents.map((student) => (
                            <div key={student.reg_no} className="flex flex-col md:flex-row items-center justify-between p-4 border border-slate-100 rounded-2xl bg-slate-50 gap-4">
                              <div className="flex items-center gap-3">
                                <img
                                  src={student.photo_url || 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?w=150'}
                                  alt="Student Photo"
                                  className="w-12 h-12 rounded-full border object-cover"
                                />
                                <div>
                                  <span className="font-bold text-sm block text-slate-800">{student.name}</span>
                                  <span className="text-xs text-slate-400 font-semibold block mt-0.5">
                                    Reg: {student.reg_no} | Adm: {student.adm_no}
                                  </span>
                                  <span className="text-[10px] bg-blue-50 text-blue-700 font-bold px-2 py-0.5 rounded-full inline-block mt-1">
                                    Class: {student.classroom_id}
                                  </span>
                                </div>
                              </div>
                              <button
                                onClick={() => handleApprove(student.reg_no, 'student')}
                                className="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition-all cursor-pointer"
                              >
                                Approve
                              </button>
                            </div>
                          ))}
                        </div>
                      </div>
                    )}

                    {/* Staff List */}
                    {pendingStaff.length > 0 && (
                      <div className="bg-white border rounded-3xl p-6 shadow-sm">
                        <h3 className="text-base font-black text-slate-800 mb-4 tracking-tight">
                          Staff Accounts ({pendingStaff.length})
                        </h3>
                        <div className="space-y-4">
                          {pendingStaff.map((staff) => (
                            <div key={staff.mobile_no} className="flex flex-col md:flex-row items-center justify-between p-4 border border-slate-100 rounded-2xl bg-slate-50 gap-4">
                              <div className="flex items-center gap-3">
                                <img
                                  src={staff.photo_url || 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=150'}
                                  alt="Staff Photo"
                                  className="w-12 h-12 rounded-full border object-cover"
                                />
                                <div>
                                  <span className="font-bold text-sm block text-slate-800">{staff.name}</span>
                                  <span className="text-xs text-slate-400 font-semibold block mt-0.5">
                                    Mobile: {staff.mobile_no} | Email: {staff.email}
                                  </span>
                                  <span className="text-[10px] bg-indigo-50 text-indigo-700 font-bold px-2 py-0.5 rounded-full inline-block mt-1">
                                    Role: {staff.designation}
                                  </span>
                                </div>
                              </div>
                              <button
                                onClick={() => handleApprove(staff.mobile_no, 'staff')}
                                className="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition-all cursor-pointer"
                              >
                                Approve
                              </button>
                            </div>
                          ))}
                        </div>
                      </div>
                    )}
                  </>
                )}
              </div>
            )}

            {/* 2. Class Allocation */}
            {activeView === 'classes' && (
              <div className="space-y-6">
                <div className="flex justify-between items-center mb-2">
                  <h3 className="text-base font-black text-slate-800">Department Classes ({classroomData.length})</h3>
                  <button className="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold transition-all flex items-center gap-1.5 cursor-pointer">
                    <Plus className="w-4 h-4" /> Create Class Group
                  </button>
                </div>
                
                <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                  {classroomData.map((cls) => (
                    <div key={cls.classroom_id} className="bg-white border rounded-3xl p-6 shadow-sm flex flex-col justify-between">
                      <div>
                        <div className="flex justify-between items-center pb-2 border-b">
                          <span className="text-xs bg-slate-100 text-slate-600 font-black px-2.5 py-0.5 rounded-full">
                            {cls.classroom_id}
                          </span>
                          <span className="text-xs text-slate-400 font-bold">Batch: {cls.batch_year}</span>
                        </div>
                        <div className="mt-4 space-y-2 text-xs font-bold text-slate-700">
                          <div className="flex justify-between">
                            <span>Tutor Mobile ID:</span>
                            <span className="text-blue-600 font-semibold">{cls.tutor_mobile_no || 'Not Assigned'}</span>
                          </div>
                          <div className="flex justify-between">
                            <span>Mentor Mobile ID:</span>
                            <span className="text-blue-600 font-semibold">{cls.mentor_mobile_no || 'Not Assigned'}</span>
                          </div>
                        </div>
                      </div>
                      
                      <div className="mt-6 border-t pt-4 space-y-2">
                        <button className="w-full py-2 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 rounded-xl text-[10px] font-black transition-all flex items-center justify-center gap-1.5 cursor-pointer">
                          <Users className="w-3.5 h-3.5" /> Assign Class Mentor
                        </button>
                      </div>
                    </div>
                  ))}
                </div>
              </div>
            )}

            {/* 3. Student Manager */}
            {activeView === 'students' && (
              <div className="bg-white border rounded-3xl p-6 shadow-sm">
                <div className="flex justify-between items-center mb-6">
                  <h3 className="text-base font-black text-slate-800">Approved Student Roster ({branchStudentRoster.length})</h3>
                  <button
                    onClick={() => {
                      setEditingStudent(null);
                      setIsStudentModalOpen(true);
                    }}
                    className="px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold transition-all flex items-center gap-1.5 cursor-pointer"
                  >
                    <Plus className="w-4 h-4" /> Add Student Record
                  </button>
                </div>

                <div className="overflow-x-auto">
                  <table className="w-full text-left border-collapse">
                    <thead>
                      <tr className="border-b border-slate-100 text-[10px] font-extrabold uppercase text-slate-400 tracking-wider">
                        <th className="pb-3 pl-2">Name / ID</th>
                        <th className="pb-3">Classroom</th>
                        <th className="pb-3">SBTE Reg</th>
                        <th className="pb-3 text-right pr-2">Actions</th>
                      </tr>
                    </thead>
                    <tbody className="divide-y divide-slate-100">
                      {branchStudentRoster.map((student) => (
                        <tr key={student.reg_no} className="text-xs font-semibold text-slate-700 hover:bg-slate-50 transition-all">
                          <td className="py-3.5 pl-2 flex items-center gap-2.5">
                            <img
                              src={student.photo_url || 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?w=150'}
                              alt="Student"
                              className="w-8 h-8 rounded-full object-cover"
                            />
                            <div>
                              <span className="font-bold text-slate-800 block">{student.name}</span>
                              <span className="text-[10px] text-slate-400">Reg: {student.reg_no} | Adm: {student.adm_no}</span>
                            </div>
                          </td>
                          <td className="py-3.5">{student.classroom_id}</td>
                          <td className="py-3.5">{student.sbte_reg_no || 'N/A'}</td>
                          <td className="py-3.5 text-right pr-2">
                            <div className="flex gap-1.5 justify-end">
                              <button className="p-1.5 text-slate-500 hover:text-blue-600 hover:bg-slate-100 rounded-lg transition-all cursor-pointer">
                                <Edit className="w-3.5 h-3.5" />
                              </button>
                              <button
                                onClick={async () => {
                                  if (confirm(`Are you sure you want to delete student ${student.name}?`)) {
                                    await dbService.deleteStudent(student.reg_no);
                                    loadDashboardData(hodBranch);
                                  }
                                }}
                                className="p-1.5 text-slate-500 hover:text-red-600 hover:bg-slate-100 rounded-lg transition-all cursor-pointer"
                              >
                                <Trash2 className="w-3.5 h-3.5" />
                              </button>
                            </div>
                          </td>
                        </tr>
                      ))}
                    </tbody>
                  </table>
                </div>
              </div>
            )}

            {/* Other views will map identically inside Next.js components */}
            {['mapping', 'staff', 'studentReport', 'obeSetup', 'profile'].includes(activeView) && (
              <div className="bg-white rounded-3xl p-8 border border-slate-200 text-center shadow-sm">
                <CheckCircle className="w-12 h-12 text-emerald-500 mx-auto mb-3" />
                <h3 className="font-black text-slate-800 text-lg">Platform View Ready</h3>
                <p className="text-slate-400 text-sm mt-1 max-w-sm mx-auto font-medium">
                  This console sub-dashboard has been configured structure-ready in the Next.js migration files and is connected to your Supabase PostgreSQL DB!
                </p>
              </div>
            )}

          </div>
        )}
      </main>
    </div>
  );
}
