'use client';

import React, { useState, useEffect } from 'react';
import { 
  ShieldAlert, Landmark, Layers, Settings, LogOut, 
  Users, UserCheck, Key, FileText, CheckCircle, 
  Plus, Edit, Trash2, Loader2, ArrowRight
} from 'lucide-react';
import { dbService, StaffProfile, StudentProfile, ClassManagement } from '@/lib/services/db';

type PrincipalView = 'adminDesk' | 'hodDesk' | 'facultyDesk' | 'profile';

export default function PrincipalDashboard() {
  const [principalMobile, setPrincipalMobile] = useState('');
  const [principalName, setPrincipalName] = useState('College Principal');
  const [principalPhoto, setPrincipalPhoto] = useState('');
  const [activeView, setActiveView] = useState<PrincipalView>('adminDesk');
  const [loading, setLoading] = useState(true);

  // Administrative stats
  const [pendingStudents, setPendingStudents] = useState<StudentProfile[]>([]);
  const [pendingStaff, setPendingStaff] = useState<StaffProfile[]>([]);
  const [allStaff, setAllStaff] = useState<StaffProfile[]>([]);
  const [allStudents, setAllStudents] = useState<StudentProfile[]>([]);
  const [classrooms, setClassrooms] = useState<ClassManagement[]>([]);

  // Selection states
  const [selectedBranch, setSelectedBranch] = useState('EL');
  const [selectedClassroom, setSelectedClassroom] = useState('');
  const [alert, setAlert] = useState<{ type: 'success' | 'error'; message: string } | null>(null);

  useEffect(() => {
    const mobile = sessionStorage.getItem('userId') || '';
    const name = sessionStorage.getItem('userName') || 'College Principal';
    const photo = sessionStorage.getItem('userPhoto') || '';

    if (!mobile) {
      window.location.href = '/';
      return;
    }

    setPrincipalMobile(mobile);
    setPrincipalName(name);
    setPrincipalPhoto(photo);

    loadPrincipalData();
  }, []);

  const loadPrincipalData = async () => {
    setLoading(true);
    try {
      // 1. Fetch pending accounts across all departments
      const staffList = await dbService.getStaffProfiles();
      const studentList = await dbService.getStudents();
      const classesList = await dbService.getClassrooms();

      setAllStaff(staffList);
      setAllStudents(studentList);
      setClassrooms(classesList);

      setPendingStudents(studentList.filter(s => s.status === 'Pending'));
      setPendingStaff(staffList.filter(s => s.account_status === 'Pending'));

    } catch (err: any) {
      console.error('Error fetching principal console data:', err);
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
        loadPrincipalData();
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
            <span className="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Principal Console</span>
          </div>
        </div>

        {/* Active Profile */}
        <div className="p-4 bg-slate-950/40 border-b border-slate-800/60 flex items-center gap-3">
          <img
            src={principalPhoto || 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=150'}
            alt="Principal Profile"
            className="w-10 h-10 rounded-full border border-slate-700 object-cover"
          />
          <div className="overflow-hidden">
            <span className="font-bold text-xs block truncate text-slate-200">{principalName}</span>
            <span className="text-[10px] font-bold text-teal-400 block uppercase tracking-wider">College Principal</span>
          </div>
        </div>

        {/* Menu Links */}
        <nav className="flex-grow p-4 space-y-1">
          {[
            { id: 'adminDesk', label: 'Administrative Desk', icon: Landmark, badge: pendingStudents.length + pendingStaff.length },
            { id: 'hodDesk', label: 'Academic Departments', icon: Layers },
            { id: 'facultyDesk', label: 'Faculty Rooms', icon: Users },
            { id: 'profile', label: 'My Profile', icon: Settings }
          ].map((item) => {
            const Icon = item.icon;
            const isActive = activeView === item.id;
            return (
              <button
                key={item.id}
                onClick={() => {
                  setActiveView(item.id as PrincipalView);
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
              {activeView === 'adminDesk' ? 'Administrative Desk' : activeView.replace(/([A-Z])/g, ' $1')}
            </h1>
            <p className="text-sm text-slate-500 font-medium mt-0.5">
              {activeView === 'adminDesk' && 'Verify global pending registrations, manage password accounts, and inspect security logs.'}
              {activeView === 'hodDesk' && 'Select any academic department to inspect class allocations and course assignments.'}
              {activeView === 'facultyDesk' && 'Directly access subject classrooms, evaluate lesson progress, and check student scores.'}
              {activeView === 'profile' && 'Update Principal credentials, phone profile numbers, and console passwords.'}
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

        {loading ? (
          <div className="flex items-center justify-center p-12">
            <Loader2 className="w-8 h-8 animate-spin text-blue-600" />
          </div>
        ) : (
          <div className="fade-in">

            {/* 1. Administrative Desk */}
            {activeView === 'adminDesk' && (
              <div className="space-y-6">
                {pendingStudents.length === 0 && pendingStaff.length === 0 ? (
                  <div className="bg-white rounded-3xl p-8 border border-slate-200 text-center shadow-sm">
                    <CheckCircle className="w-12 h-12 text-emerald-500 mx-auto mb-3" />
                    <h3 className="font-black text-slate-800 text-lg">System Registrations Approved</h3>
                    <p className="text-slate-400 text-sm mt-1 max-w-sm mx-auto font-medium">
                      All student and staff profiles in the college are currently active and up to date.
                    </p>
                  </div>
                ) : (
                  <>
                    {/* Student List */}
                    {pendingStudents.length > 0 && (
                      <div className="bg-white border rounded-3xl p-6 shadow-sm">
                        <h3 className="text-base font-black text-slate-800 mb-4 tracking-tight">
                          Pending Student Registrations ({pendingStudents.length})
                        </h3>
                        <div className="space-y-4">
                          {pendingStudents.map((student) => (
                            <div key={student.reg_no} className="flex flex-col md:flex-row items-center justify-between p-4 border border-slate-100 rounded-2xl bg-slate-50 gap-4">
                              <div className="flex items-center gap-3">
                                <img
                                  src={student.photo_url || 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?w=150'}
                                  alt="Student"
                                  className="w-12 h-12 rounded-full border object-cover"
                                />
                                <div>
                                  <span className="font-bold text-sm block text-slate-800">{student.name}</span>
                                  <span className="text-xs text-slate-400 font-semibold block mt-0.5">
                                    Reg: {student.reg_no} | Adm: {student.adm_no}
                                  </span>
                                  <span className="text-[10px] bg-blue-50 text-blue-700 font-bold px-2 py-0.5 rounded-full inline-block mt-1">
                                    Class: {student.classroom_id} ({student.branch})
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
                          Pending Staff Registrations ({pendingStaff.length})
                        </h3>
                        <div className="space-y-4">
                          {pendingStaff.map((staff) => (
                            <div key={staff.mobile_no} className="flex flex-col md:flex-row items-center justify-between p-4 border border-slate-100 rounded-2xl bg-slate-50 gap-4">
                              <div className="flex items-center gap-3">
                                <img
                                  src={staff.photo_url || 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=150'}
                                  alt="Staff"
                                  className="w-12 h-12 rounded-full border object-cover"
                                />
                                <div>
                                  <span className="font-bold text-sm block text-slate-800">{staff.name}</span>
                                  <span className="text-xs text-slate-400 font-semibold block mt-0.5">
                                    Mobile: {staff.mobile_no} | Dept: {staff.branch}
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

            {/* 2. Academic Departments */}
            {activeView === 'hodDesk' && (
              <div className="space-y-6">
                <div className="bg-white border rounded-3xl p-6 shadow-sm">
                  <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                    <h3 className="text-base font-black text-slate-800">Select Academic Department</h3>
                    <select
                      value={selectedBranch}
                      onChange={(e) => setSelectedBranch(e.target.value)}
                      className="bg-slate-50 border border-slate-200 rounded-xl px-4 py-2 text-xs font-bold text-slate-700 outline-none"
                    >
                      <option value="EL">Electronics Engineering (EL)</option>
                      <option value="ME">Mechanical Engineering (ME)</option>
                      <option value="CE">Civil Engineering (CE)</option>
                      <option value="EEE">Electrical & Electronics Engineering (EEE)</option>
                      <option value="CT">Computer Engineering (CT)</option>
                      <option value="AU">Automobile Engineering (AU)</option>
                    </select>
                  </div>

                  <div className="border-t border-slate-100 pt-6">
                    <h4 className="text-sm font-black text-slate-800 mb-4">Class allocations for {selectedBranch}</h4>
                    <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                      {classrooms
                        .filter(c => c.branch === selectedBranch)
                        .map(cls => (
                          <div key={cls.classroom_id} className="p-4 border border-slate-100 bg-slate-50 rounded-2xl">
                            <span className="text-xs bg-slate-200 text-slate-800 font-extrabold px-2 py-0.5 rounded-full inline-block mb-3">
                              {cls.classroom_id}
                            </span>
                            <div className="space-y-1 text-xs text-slate-500 font-semibold">
                              <p>Tutor: <span className="text-slate-800 font-bold">{cls.tutor_mobile_no || 'Unassigned'}</span></p>
                              <p>Mentor: <span className="text-slate-800 font-bold">{cls.mentor_mobile_no || 'Unassigned'}</span></p>
                            </div>
                          </div>
                        ))}
                    </div>
                  </div>
                </div>
              </div>
            )}

            {/* 3. Faculty Rooms */}
            {activeView === 'facultyDesk' && (
              <div className="bg-white border rounded-3xl p-6 shadow-sm text-center">
                <Users className="w-12 h-12 text-blue-600 mx-auto mb-3" />
                <h3 className="font-black text-slate-800 text-lg">College Classroom Inspection Scopes</h3>
                <p className="text-slate-400 text-sm mt-1 max-w-sm mx-auto font-medium mb-4">
                  The Principal can select any active classroom ID to view attendance files and subject marks attainment ratios.
                </p>
                <div className="flex justify-center gap-3">
                  <select
                    className="bg-slate-50 border border-slate-200 rounded-xl px-4 py-2 text-xs font-bold text-slate-700 outline-none"
                    defaultValue=""
                  >
                    <option value="" disabled>Select Classroom</option>
                    {classrooms.map(c => (
                      <option key={c.classroom_id} value={c.classroom_id}>{c.classroom_id}</option>
                    ))}
                  </select>
                  <button className="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold transition-all flex items-center gap-1 cursor-pointer">
                    <span>Inspect</span> <ArrowRight className="w-3.5 h-3.5" />
                  </button>
                </div>
              </div>
            )}

            {/* 4. Profile */}
            {activeView === 'profile' && (
              <div className="bg-white border rounded-3xl p-6 shadow-sm max-w-md mx-auto">
                <h3 className="text-base font-black text-slate-800 mb-4">Principal Profile Details</h3>
                <div className="space-y-4 text-xs font-semibold text-slate-600">
                  <div>
                    <label className="block text-[10px] uppercase font-bold text-slate-400 mb-1">Name</label>
                    <p className="text-slate-800 font-bold bg-slate-50 p-2.5 rounded-xl border border-slate-100">{principalName}</p>
                  </div>
                  <div>
                    <label className="block text-[10px] uppercase font-bold text-slate-400 mb-1">Mobile / Login ID</label>
                    <p className="text-slate-800 font-bold bg-slate-50 p-2.5 rounded-xl border border-slate-100">{principalMobile}</p>
                  </div>
                </div>
              </div>
            )}

          </div>
        )}
      </main>
    </div>
  );
}
