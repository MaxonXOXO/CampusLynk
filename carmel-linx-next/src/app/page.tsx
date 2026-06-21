'use client';

import React, { useState } from 'react';
import { School, Badge, Lock, User, Mail, Phone, Upload, Loader2, ArrowLeft } from 'lucide-react';
import { authService } from '@/lib/services/auth';

export default function LoginPage() {
  const [isRegisterMode, setIsRegisterMode] = useState(false);
  const [activeRole, setActiveRole] = useState<'student' | 'staff'>('student');
  const [isLoading, setIsLoading] = useState(false);
  
  // Alert message state
  const [alert, setAlert] = useState<{ type: 'success' | 'error'; message: string } | null>(null);

  // Form inputs
  const [loginId, setLoginId] = useState('');
  const [loginPassword, setLoginPassword] = useState('');

  // Register state
  const [regName, setRegName] = useState('');
  const [regEmail, setRegEmail] = useState('');
  const [regPassword, setRegPassword] = useState('');
  const [regPhoto, setRegPhoto] = useState<string>('');

  // Student specific register fields
  const [regStudentId, setRegStudentId] = useState('');
  const [regStudentAdm, setRegStudentAdm] = useState('');
  const [regStudentBranch, setRegStudentBranch] = useState('EL');
  const [regStudentYear, setRegStudentYear] = useState('2024');
  const [regStudentSem, setRegStudentSem] = useState('S3');

  // Staff specific register fields
  const [regStaffMobile, setRegStaffMobile] = useState('');
  const [regStaffBranch, setRegStaffBranch] = useState('EL');
  const [regStaffDesig, setRegStaffDesig] = useState('HOD');

  const handlePhotoUpload = (e: React.ChangeEvent<HTMLInputElement>) => {
    const file = e.target.files?.[0];
    if (!file) return;

    const reader = new FileReader();
    reader.onloadend = () => {
      setRegPhoto(reader.result as string);
    };
    reader.readAsDataURL(file);
  };

  const handleLogin = async (e: React.FormEvent) => {
    e.preventDefault();
    setAlert(null);

    if (!loginId || !loginPassword) {
      setAlert({ type: 'error', message: 'Please fill in all credentials.' });
      return;
    }

    setIsLoading(true);
    try {
      const response = await authService.loginUser(loginId, loginPassword, activeRole);
      
      if (response.status === 'SUCCESS') {
        setAlert({ type: 'success', message: 'Login successful! Redirecting...' });
        
        // Save to sessionStorage
        sessionStorage.setItem('userRole', response.role || '');
        sessionStorage.setItem('userId', response.id || '');
        sessionStorage.setItem('userName', response.name || '');
        sessionStorage.setItem('userBranch', response.branch || 'Admin');
        sessionStorage.setItem('userPhoto', response.photo || '');

        // Redirect routing logic
        setTimeout(() => {
          let route = '/login';
          if (response.role === 'Student') route = '/dashboard/student';
          else if (response.role === 'HOD') route = '/dashboard/hod';
          else if (response.role === 'Principal') route = '/dashboard/principal';
          else if (response.role === 'Super_Admin' || response.role === 'Academic_Coordinator') route = '/dashboard/admin';
          else {
            route = '/dashboard/faculty';
          }
          window.location.href = route;
        }, 1500);
      } else {
        setAlert({ type: 'error', message: response.message || 'Login failed.' });
      }
    } catch (err: any) {
      setAlert({ type: 'error', message: 'Server connection failed: ' + err.message });
    } finally {
      setIsLoading(false);
    }
  };

  const handleRegistration = async (e: React.FormEvent) => {
    e.preventDefault();
    setAlert(null);

    if (!regName || !regEmail || !regPassword) {
      setAlert({ type: 'error', message: 'Please fill in all required shared fields.' });
      return;
    }

    setIsLoading(true);
    try {
      if (activeRole === 'student') {
        if (!regStudentId || !regStudentAdm) {
          setAlert({ type: 'error', message: 'Please provide Register and Admission Numbers.' });
          setIsLoading(false);
          return;
        }

        const res = await authService.registerStudent({
          regNo: regStudentId,
          admNo: regStudentAdm,
          name: regName,
          email: regEmail,
          phone: '',
          branch: regStudentBranch,
          admissionYear: regStudentYear,
          admissionType: 'Regular', // Defaults to regular
          password: regPassword,
          photoUrl: regPhoto,
          sbteRegNo: ''
        });

        if (res.status === 'SUCCESS') {
          setAlert({ type: 'success', message: res.message || 'Registration successful!' });
          setTimeout(() => {
            setIsRegisterMode(false);
            setAlert(null);
          }, 3000);
        } else {
          setAlert({ type: 'error', message: res.message || 'Registration failed.' });
        }
      } else {
        if (!regStaffMobile) {
          setAlert({ type: 'error', message: 'Please provide a valid Mobile Number.' });
          setIsLoading(false);
          return;
        }

        const res = await authService.registerStaff({
          mobileNo: regStaffMobile,
          name: regName,
          email: regEmail,
          branch: regStaffBranch,
          designation: regStaffDesig,
          password: regPassword,
          photoUrl: regPhoto
        });

        if (res.status === 'SUCCESS') {
          setAlert({ type: 'success', message: res.message || 'Registration submitted!' });
          setTimeout(() => {
            setIsRegisterMode(false);
            setAlert(null);
          }, 3000);
        } else {
          setAlert({ type: 'error', message: res.message || 'Registration failed.' });
        }
      }
    } catch (err: any) {
      setAlert({ type: 'error', message: 'Registration failed: ' + err.message });
    } finally {
      setIsLoading(false);
    }
  };

  return (
    <main className="min-h-screen w-full bg-gradient-to-tr from-slate-900 via-indigo-950 to-blue-900 flex items-center justify-center p-4">
      <div className="w-full max-w-lg bg-white/95 backdrop-blur-xl shadow-2xl rounded-3xl overflow-hidden border border-white/20 p-6 md:p-8 transform transition-all duration-300">
        
        {/* Branding */}
        <div className="text-center mb-8">
          <div className="inline-flex items-center justify-center bg-blue-600 text-white w-16 h-16 rounded-2xl shadow-lg shadow-blue-500/30 font-bold text-3xl mb-3 tracking-wider select-none">
            CL
          </div>
          <h1 className="text-3xl font-black text-slate-800 tracking-tight">Carmel Linx</h1>
          <p className="text-slate-500 font-medium text-sm mt-1">Outcome-Based Education Exam Portal</p>
        </div>

        {/* Form Area */}
        {!isRegisterMode ? (
          <div>
            {/* Login Role Tabs */}
            <div className="flex bg-slate-100 p-1.5 rounded-2xl mb-6 border border-slate-200">
              <button
                type="button"
                onClick={() => {
                  setActiveRole('student');
                  setAlert(null);
                }}
                className={`flex-1 py-2.5 rounded-xl font-bold text-sm transition-all duration-200 flex items-center justify-center gap-1.5 ${
                  activeRole === 'student'
                    ? 'text-blue-600 bg-white shadow-sm'
                    : 'text-slate-600 hover:text-slate-800'
                }`}
              >
                <School className="w-4 h-4" /> Student
              </button>
              <button
                type="button"
                onClick={() => {
                  setActiveRole('staff');
                  setAlert(null);
                }}
                className={`flex-1 py-2.5 rounded-xl font-bold text-sm transition-all duration-200 flex items-center justify-center gap-1.5 ${
                  activeRole === 'staff'
                    ? 'text-blue-600 bg-white shadow-sm'
                    : 'text-slate-600 hover:text-slate-800'
                }`}
              >
                <Badge className="w-4 h-4" /> Staff Portal
              </button>
            </div>

            <form onSubmit={handleLogin} className="space-y-4">
              <div>
                <label className="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                  {activeRole === 'student' ? 'Register / Admission Number' : 'Mobile Number (ID)'}
                </label>
                <div className="relative">
                  <div className="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <User className="w-4 h-4" />
                  </div>
                  <input
                    type="text"
                    value={loginId}
                    onChange={(e) => setLoginId(e.target.value)}
                    className="w-full pl-10 pr-4 py-3 rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none text-slate-800 font-medium transition-all"
                    placeholder={activeRole === 'student' ? 'e.g. REG24EC01' : 'e.g. 9845000001'}
                    required
                  />
                </div>
              </div>

              <div>
                <label className="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                  Password
                </label>
                <div className="relative">
                  <div className="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <Lock className="w-4 h-4" />
                  </div>
                  <input
                    type="password"
                    value={loginPassword}
                    onChange={(e) => setLoginPassword(e.target.value)}
                    className="w-full pl-10 pr-4 py-3 rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none text-slate-800 font-medium transition-all"
                    placeholder="••••••••"
                    required
                  />
                </div>
              </div>

              {/* Alert Notification */}
              {alert && (
                <div
                  className={`p-4 rounded-xl text-sm font-semibold border ${
                    alert.type === 'success'
                      ? 'bg-green-50 text-green-700 border-green-200'
                      : 'bg-red-50 text-red-700 border-red-200'
                  }`}
                >
                  {alert.message}
                </div>
              )}

              <button
                type="submit"
                disabled={isLoading}
                className="w-full py-3.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 disabled:opacity-50 text-white rounded-xl font-bold shadow-lg shadow-blue-500/20 transition-all flex items-center justify-center gap-2 cursor-pointer"
              >
                {isLoading ? (
                  <>
                    <Loader2 className="w-4 h-4 animate-spin" />
                    <span>Verifying...</span>
                  </>
                ) : (
                  <span>Access Portal</span>
                )}
              </button>
            </form>

            <div className="text-center mt-6 space-y-2">
              <p className="text-slate-500 text-sm">Don't have an account?</p>
              <div className="flex justify-center gap-4 text-xs font-bold">
                <button
                  type="button"
                  onClick={() => {
                    setActiveRole('student');
                    setIsRegisterMode(true);
                    setAlert(null);
                  }}
                  className="text-blue-600 hover:text-blue-700 cursor-pointer"
                >
                  Register as Student
                </button>
                <span className="text-slate-300">|</span>
                <button
                  type="button"
                  onClick={() => {
                    setActiveRole('staff');
                    setIsRegisterMode(true);
                    setAlert(null);
                  }}
                  className="text-blue-600 hover:text-blue-700 cursor-pointer"
                >
                  Register as Staff
                </button>
              </div>
            </div>
          </div>
        ) : (
          /* Registration Section */
          <div>
            <h2 className="text-xl font-extrabold text-slate-800 mb-6 text-center border-b border-slate-100 pb-3">
              {activeRole === 'student' ? 'Register Student Profile' : 'Register Academic Staff'}
            </h2>
            
            <form onSubmit={handleRegistration} className="space-y-4 max-h-[420px] overflow-y-auto pr-1">
              <div>
                <label className="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                  Full Name
                </label>
                <div className="relative">
                  <div className="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <User className="w-4 h-4" />
                  </div>
                  <input
                    type="text"
                    value={regName}
                    onChange={(e) => setRegName(e.target.value)}
                    required
                    className="w-full pl-10 pr-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500/20 outline-none text-slate-800 font-medium transition-all"
                    placeholder="Enter Full Name"
                  />
                </div>
              </div>

              <div>
                <label className="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                  Email Address
                </label>
                <div className="relative">
                  <div className="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <Mail className="w-4 h-4" />
                  </div>
                  <input
                    type="email"
                    value={regEmail}
                    onChange={(e) => setRegEmail(e.target.value)}
                    required
                    className="w-full pl-10 pr-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500/20 outline-none text-slate-800 font-medium transition-all"
                    placeholder="name@carmelpoly.edu.in"
                  />
                </div>
              </div>

              {/* Student specific fields */}
              {activeRole === 'student' ? (
                <>
                  <div className="grid grid-cols-2 gap-4">
                    <div>
                      <label className="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                        Register No
                      </label>
                      <input
                        type="text"
                        value={regStudentId}
                        onChange={(e) => setRegStudentId(e.target.value)}
                        required
                        className="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500/20 outline-none text-slate-800 font-medium transition-all"
                        placeholder="REG24EC01"
                      />
                    </div>
                    <div>
                      <label className="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                        Admission No
                      </label>
                      <input
                        type="text"
                        value={regStudentAdm}
                        onChange={(e) => setRegStudentAdm(e.target.value)}
                        required
                        className="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500/20 outline-none text-slate-800 font-medium transition-all"
                        placeholder="ADM24EC01"
                      />
                    </div>
                  </div>
                  
                  <div className="grid grid-cols-2 gap-4">
                    <div>
                      <label className="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                        Branch
                      </label>
                      <select
                        value={regStudentBranch}
                        onChange={(e) => setRegStudentBranch(e.target.value)}
                        className="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500/20 outline-none text-slate-800 font-medium bg-white"
                      >
                        <option value="EL">Electronics Engineering (EL)</option>
                        <option value="ME">Mechanical Engineering (ME)</option>
                        <option value="CE">Civil Engineering (CE)</option>
                        <option value="EEE">Electrical & Electronics Engineering (EEE)</option>
                        <option value="CT">Computer Engineering (CT)</option>
                        <option value="AU">Automobile Engineering (AU)</option>
                      </select>
                    </div>
                    <div>
                      <label className="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                        Admission Year
                      </label>
                      <input
                        type="number"
                        value={regStudentYear}
                        onChange={(e) => setRegStudentYear(e.target.value)}
                        required
                        className="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500/20 outline-none text-slate-800 font-medium transition-all"
                        placeholder="2024"
                      />
                    </div>
                  </div>

                  <div>
                    <label className="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                      Current Semester
                    </label>
                    <select
                      value={regStudentSem}
                      onChange={(e) => setRegStudentSem(e.target.value)}
                      className="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500/20 outline-none text-slate-800 font-medium bg-white"
                    >
                      <option value="S1">S1</option>
                      <option value="S2">S2</option>
                      <option value="S3">S3</option>
                      <option value="S4">S4</option>
                      <option value="S5">S5</option>
                      <option value="S6">S6</option>
                    </select>
                  </div>
                </>
              ) : (
                /* Staff specific fields */
                <>
                  <div>
                    <label className="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                      Mobile No (Login ID)
                    </label>
                    <div className="relative">
                      <div className="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <Phone className="w-4 h-4" />
                      </div>
                      <input
                        type="text"
                        value={regStaffMobile}
                        onChange={(e) => setRegStaffMobile(e.target.value)}
                        required
                        className="w-full pl-10 pr-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500/20 outline-none text-slate-800 font-medium transition-all"
                        placeholder="10-digit Mobile Number"
                      />
                    </div>
                  </div>
                  
                  <div className="grid grid-cols-2 gap-4">
                    <div>
                      <label className="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                        Branch
                      </label>
                      <select
                        value={regStaffBranch}
                        onChange={(e) => setRegStaffBranch(e.target.value)}
                        className="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500/20 outline-none text-slate-800 font-medium bg-white"
                      >
                        <option value="EL">Electronics Engineering (EL)</option>
                        <option value="ME">Mechanical Engineering (ME)</option>
                        <option value="CE">Civil Engineering (CE)</option>
                        <option value="EEE">Electrical & Electronics Engineering (EEE)</option>
                        <option value="CT">Computer Engineering (CT)</option>
                        <option value="AU">Automobile Engineering (AU)</option>
                        <option value="Admin">Administration</option>
                      </select>
                    </div>
                    <div>
                      <label className="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                        Designation
                      </label>
                      <select
                        value={regStaffDesig}
                        onChange={(e) => setRegStaffDesig(e.target.value)}
                        className="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500/20 outline-none text-slate-800 font-medium bg-white"
                      >
                        <option value="HOD">Head of Department (HOD)</option>
                        <option value="Lecturer">Lecturer</option>
                        <option value="Demonstrator">Demonstrator</option>
                        <option value="Trade_Instructor">Trade Instructor</option>
                        <option value="Principal">Principal</option>
                      </select>
                    </div>
                  </div>
                </>
              )}

              {/* Password */}
              <div>
                <label className="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                  Password
                </label>
                <div className="relative">
                  <div className="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <Lock className="w-4 h-4" />
                  </div>
                  <input
                    type="password"
                    value={regPassword}
                    onChange={(e) => setRegPassword(e.target.value)}
                    required
                    className="w-full pl-10 pr-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500/20 outline-none text-slate-800 font-medium transition-all"
                    placeholder="••••••••"
                  />
                </div>
              </div>

              {/* Photo Upload */}
              <div>
                <label className="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                  Passport Photo
                </label>
                <div className="flex items-center gap-3">
                  <label className="flex items-center gap-2 px-4 py-2 bg-blue-50 hover:bg-blue-100 text-blue-700 rounded-xl cursor-pointer text-xs font-bold transition-all">
                    <Upload className="w-3.5 h-3.5" />
                    <span>Upload Image</span>
                    <input
                      type="file"
                      accept="image/*"
                      onChange={handlePhotoUpload}
                      className="hidden"
                    />
                  </label>
                  {regPhoto && (
                    <div className="relative w-12 h-12 rounded-lg overflow-hidden border border-slate-200">
                      <img src={regPhoto} alt="Preview" className="w-full h-full object-cover" />
                    </div>
                  )}
                </div>
              </div>

              {/* Alert Notification */}
              {alert && (
                <div
                  className={`p-4 rounded-xl text-sm font-semibold border ${
                    alert.type === 'success'
                      ? 'bg-green-50 text-green-700 border-green-200'
                      : 'bg-red-50 text-red-700 border-red-200'
                  }`}
                >
                  {alert.message}
                </div>
              )}

              {/* Action Buttons */}
              <div className="flex gap-3 pt-2">
                <button
                  type="button"
                  onClick={() => {
                    setIsRegisterMode(false);
                    setAlert(null);
                  }}
                  className="flex-1 py-3 border border-slate-200 hover:bg-slate-50 rounded-xl font-bold transition-all text-slate-700 text-sm cursor-pointer"
                >
                  Back to Login
                </button>
                <button
                  type="submit"
                  disabled={isLoading}
                  className="flex-1 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 disabled:opacity-50 text-white rounded-xl font-bold shadow-lg transition-all flex items-center justify-center gap-2 text-sm cursor-pointer"
                >
                  {isLoading ? (
                    <>
                      <Loader2 className="w-4 h-4 animate-spin" />
                      <span>Submitting...</span>
                    </>
                  ) : (
                    <span>Register</span>
                  )}
                </button>
              </div>
            </form>
          </div>
        )}

      </div>
    </main>
  );
}
