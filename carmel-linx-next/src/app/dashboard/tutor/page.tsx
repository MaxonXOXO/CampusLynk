'use client';

import React, { useState, useEffect } from 'react';
import { 
  Users, User, FileText, Settings, LogOut, Plus, 
  Trash2, Edit, Award, Printer, BookOpen, Loader2
} from 'lucide-react';
import { dbService, StudentProfile, StaffProfile } from '@/lib/services/db';

export default function TutorDashboard() {
  const [tutorMobile, setTutorMobile] = useState('');
  const [tutorName, setTutorName] = useState('Class Tutor');
  const [tutorBranch, setTutorBranch] = useState('');
  const [tutorPhoto, setTutorPhoto] = useState('');
  const [loading, setLoading] = useState(true);

  // Classroom data
  const [classroomId, setClassroomId] = useState('');
  const [batchYear, setBatchYear] = useState('');
  const [students, setStudents] = useState<StudentProfile[]>([]);
  const [selectedStudent, setSelectedStudent] = useState<StudentProfile | null>(null);

  // Form inputs
  const [diaryCategory, setDiaryCategory] = useState('Academic');
  const [diaryNotes, setDiaryNotes] = useState('');
  const [diaryAction, setDiaryAction] = useState('');
  const [alert, setAlert] = useState<{ type: 'success' | 'error'; message: string } | null>(null);

  useEffect(() => {
    const mobile = sessionStorage.getItem('userId') || '';
    const name = sessionStorage.getItem('userName') || 'Class Tutor';
    const branch = sessionStorage.getItem('userBranch') || '';
    const photo = sessionStorage.getItem('userPhoto') || '';

    if (!mobile) {
      window.location.href = '/';
      return;
    }

    setTutorMobile(mobile);
    setTutorName(name);
    setTutorBranch(branch);
    setTutorPhoto(photo);

    loadTutorClassroom(mobile);
  }, []);

  const loadTutorClassroom = async (mobile: string) => {
    setLoading(true);
    try {
      // Find the classroom where this staff is tutor or mentor
      const classrooms = await dbService.getClassrooms();
      const myClass = classrooms.find(c => c.tutor_mobile_no === mobile || c.mentor_mobile_no === mobile);
      
      if (myClass) {
        setClassroomId(myClass.classroom_id);
        setBatchYear(myClass.batch_year.toString());
        
        // Fetch students in this class
        const classStudents = await dbService.getStudents(myClass.classroom_id);
        setStudents(classStudents.filter(s => s.status === 'Approved'));
      }
    } catch (err: any) {
      console.error('Error fetching classroom roster:', err);
    } finally {
      setLoading(false);
    }
  };

  const handleSaveDiary = async (e: React.FormEvent) => {
    e.preventDefault();
    if (!selectedStudent || !diaryNotes) return;

    try {
      // Log counseling diary note
      await dbService.logSystemActivity(
        selectedStudent.reg_no,
        `DIARY_${diaryCategory}`,
        `Notes: ${diaryNotes} | Action: ${diaryAction}`
      );
      setAlert({ type: 'success', message: 'Counseling remark logged successfully.' });
      setDiaryNotes('');
      setDiaryAction('');
    } catch (err: any) {
      setAlert({ type: 'error', message: 'Failed to save entry: ' + err.message });
    }
  };

  const handleLogout = () => {
    sessionStorage.clear();
    window.location.href = '/';
  };

  return (
    <div className="bg-slate-50 min-h-screen flex flex-col md:flex-row text-slate-800">
      {/* Sidebar */}
      <aside className="w-full md:w-64 bg-slate-900 text-white flex-shrink-0 flex flex-col border-r border-slate-800 print:hidden">
        <div className="p-6 border-b border-slate-800 flex items-center gap-3">
          <div className="bg-blue-600 text-white font-bold rounded-lg w-8 h-8 flex items-center justify-center text-sm">
            CL
          </div>
          <div>
            <h2 className="font-extrabold text-sm tracking-wide">Carmel Linx</h2>
            <span className="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Tutor Panel</span>
          </div>
        </div>

        {/* Profile */}
        <div className="p-4 bg-slate-950/40 border-b border-slate-800/60 flex items-center gap-3">
          <img
            src={tutorPhoto || 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=100'}
            alt="Tutor avatar"
            className="w-10 h-10 rounded-full border border-slate-700 object-cover"
          />
          <div className="overflow-hidden">
            <span className="font-bold text-xs block truncate text-slate-200">{tutorName}</span>
            <span className="text-[10px] font-bold text-green-400 block uppercase tracking-wider">{tutorBranch} Tutor</span>
          </div>
        </div>

        {/* Nav Links */}
        <nav className="flex-grow p-4 space-y-1">
          <button className="w-full text-left px-4 py-2.5 rounded-xl font-bold text-xs flex items-center gap-3 bg-blue-600/15 text-blue-400 transition-all cursor-pointer">
            <Users className="w-4 h-4" /> My Supervised Class
          </button>
          <button className="w-full text-left px-4 py-2.5 rounded-xl font-bold text-xs flex items-center gap-3 text-slate-400 hover:bg-slate-800 hover:text-white transition-all cursor-pointer">
            <Settings className="w-4 h-4" /> My Profile
          </button>
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
      <main className="flex-grow p-6 md:p-8 overflow-y-auto max-h-screen print:p-0">
        {/* Header */}
        <div className="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-slate-200 pb-5 mb-6 print:hidden">
          <div>
            <h1 className="text-2xl font-black text-slate-800 tracking-tight">My Supervised Class</h1>
            <p className="text-sm text-slate-500 font-medium mt-0.5">
              {classroomId ? `Assigned Group: ${classroomId} | Batch Year: ${batchYear}` : 'No classroom currently assigned to this account.'}
            </p>
          </div>
        </div>

        {alert && (
          <div
            className={`p-4 mb-4 rounded-xl text-sm font-semibold border print:hidden ${
              alert.type === 'success'
                ? 'bg-green-50 text-green-700 border-green-200'
                : 'bg-red-50 text-red-700 border-red-200'
            }`}
          >
            {alert.message}
          </div>
        )}

        {loading ? (
          <div className="flex items-center justify-center p-12 print:hidden">
            <Loader2 className="w-8 h-8 animate-spin text-blue-600" />
          </div>
        ) : (
          <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            {/* Student List Sidebar */}
            <div className="bg-white border border-slate-200 rounded-3xl p-6 shadow-sm lg:col-span-1 h-fit print:hidden">
              <h3 className="text-base font-black text-slate-800 mb-4 tracking-tight border-b pb-2">Student Profiles</h3>
              {students.length === 0 ? (
                <p className="text-slate-400 text-xs font-bold py-6 text-center">No approved students enrolled.</p>
              ) : (
                <div className="space-y-3 max-h-[500px] overflow-y-auto pr-1">
                  {students.map((student) => (
                    <button
                      key={student.reg_no}
                      onClick={() => {
                        setSelectedStudent(student);
                        setAlert(null);
                      }}
                      className={`w-full flex items-center gap-3 p-3 rounded-2xl border transition-all text-left font-bold ${
                        selectedStudent?.reg_no === student.reg_no
                          ? 'border-blue-500 bg-blue-50/30'
                          : 'border-slate-100 hover:border-slate-200 hover:bg-slate-50'
                      }`}
                    >
                      <img
                        src={student.photo_url || 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?w=150'}
                        alt={student.name}
                        className="w-10 h-10 rounded-full object-cover border"
                      />
                      <div>
                        <span className="text-xs text-slate-800 block truncate">{student.name}</span>
                        <span className="text-[10px] text-slate-400 font-mono">{student.reg_no}</span>
                      </div>
                    </button>
                  ))}
                </div>
              )}
            </div>

            {/* PTM Details & Counselor Diary */}
            <div className="lg:col-span-2 space-y-6">
              {selectedStudent ? (
                <>
                  {/* PTM Progress Card Card */}
                  <div className="bg-white border border-slate-200 rounded-3xl p-6 md:p-8 shadow-sm flex flex-col gap-6 relative">
                    <button
                      onClick={() => window.print()}
                      className="absolute right-6 top-6 px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white rounded-xl text-xs font-bold transition-all print:hidden flex items-center justify-center gap-1.5 cursor-pointer"
                    >
                      <Printer className="w-3.5 h-3.5" /> Print PTM Card
                    </button>

                    <div className="text-center border-b pb-4">
                      <h2 className="text-xl font-extrabold text-slate-850 uppercase tracking-wide">
                        Carmel Polytechnic College
                      </h2>
                      <span className="text-xs text-slate-400 font-bold uppercase tracking-wider block mt-1">
                        Student Academic Progress Report Card
                      </span>
                    </div>

                    <div className="flex flex-col sm:flex-row items-center gap-4 bg-slate-50 rounded-2xl p-4 border border-slate-100">
                      <img
                        src={selectedStudent.photo_url || 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?w=150'}
                        alt="Avatar"
                        className="w-16 h-16 rounded-xl border object-cover"
                      />
                      <div className="grid grid-cols-2 gap-x-6 gap-y-1.5 text-xs font-bold text-slate-700 w-full">
                        <div>Student Name: <span className="text-slate-900 font-extrabold block text-sm">{selectedStudent.name}</span></div>
                        <div>Register No: <span className="text-slate-900 block font-mono text-sm">{selectedStudent.reg_no}</span></div>
                        <div>Classroom ID: <span className="text-slate-500 block">{selectedStudent.classroom_id}</span></div>
                        <div>Department: <span className="text-slate-500 block">{selectedStudent.branch}</span></div>
                      </div>
                    </div>

                    {/* CO Progress Outcome mockup */}
                    <div>
                      <h4 className="text-xs font-black text-slate-800 uppercase tracking-wider mb-2">Subject Outcomes</h4>
                      <div className="grid grid-cols-4 gap-4">
                        {['CO1', 'CO2', 'CO3', 'CO4'].map((co) => (
                          <div key={co} className="border border-slate-150 rounded-2xl p-3 bg-slate-50/50 text-center font-bold">
                            <span className="text-[10px] text-slate-400 uppercase tracking-wider block">{co}</span>
                            <span className="text-base text-slate-800 block mt-1">85%</span>
                          </div>
                        ))}
                      </div>
                    </div>

                    {/* Sign-off signatures */}
                    <div className="grid grid-cols-2 gap-10 mt-8 pt-8 border-t border-slate-100 text-xs font-bold text-slate-500 text-center">
                      <div>
                        <div className="h-10 border-b border-slate-350 w-44 mx-auto mb-2"></div>
                        <span>Class Tutor Signature</span>
                      </div>
                      <div>
                        <div className="h-10 border-b border-slate-350 w-44 mx-auto mb-2"></div>
                        <span>Parent / Guardian Signature</span>
                      </div>
                    </div>
                  </div>

                  {/* Counselor Log Form */}
                  <div className="bg-white border border-slate-200 rounded-3xl p-6 md:p-8 shadow-sm flex flex-col gap-6 print:hidden">
                    <div className="border-b border-slate-100 pb-3">
                      <h3 className="text-base font-black text-slate-800 tracking-tight">Mentoring Diary & Counselor Log</h3>
                      <p className="text-xs text-slate-400 font-bold uppercase mt-1">
                        Record counselor discussions and parent meetings.
                      </p>
                    </div>

                    <form onSubmit={handleSaveDiary} className="space-y-4">
                      <div className="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                          <label className="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Category</label>
                          <select
                            value={diaryCategory}
                            onChange={(e) => setDiaryCategory(e.target.value)}
                            className="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-xs font-bold text-slate-700 outline-none"
                          >
                            <option value="Academic">Academic progress</option>
                            <option value="Absenteeism">Absenteeism & Attendance</option>
                            <option value="Discipline">Discipline & Behavior</option>
                            <option value="PTM Meeting">PTM Meeting</option>
                            <option value="Personal Counseling">Personal counseling</option>
                          </select>
                        </div>
                        <div className="sm:col-span-2">
                          <label className="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Notes / Remarks</label>
                          <input
                            type="text"
                            value={diaryNotes}
                            onChange={(e) => setDiaryNotes(e.target.value)}
                            required
                            className="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-xs font-bold text-slate-800 outline-none focus:border-blue-500"
                            placeholder="Summarize counseling points..."
                          />
                        </div>
                      </div>

                      <div>
                        <label className="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Action Taken / Resolution</label>
                        <input
                          type="text"
                          value={diaryAction}
                          onChange={(e) => setDiaryAction(e.target.value)}
                          className="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-xs font-bold text-slate-800 outline-none focus:border-blue-500"
                          placeholder="e.g. Warned student, parents contacted, advised remediation"
                        />
                      </div>

                      <button
                        type="submit"
                        className="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold transition-all shadow-md cursor-pointer"
                      >
                        Save Counseling Entry
                      </button>
                    </form>
                  </div>
                </>
              ) : (
                <div className="bg-white rounded-3xl p-8 border border-slate-200 text-center shadow-sm print:hidden">
                  <BookOpen className="w-12 h-12 text-slate-400 mx-auto mb-3" />
                  <h3 className="font-black text-slate-800 text-lg">No Student Selected</h3>
                  <p className="text-slate-400 text-sm mt-1 max-w-xs mx-auto font-medium">
                    Select a student profile from the left sidebar to generate PTM cards and edit mentoring diaries.
                  </p>
                </div>
              )}
            </div>

          </div>
        )}
      </main>
    </div>
  );
}
