# Carmel Linx - Outcome-Based Exam Portal

**Carmel Linx** is a lightweight, high-performance, and mobile-friendly Outcome-Based Education (OBE) Exam Management System built for Carmel Polytechnic. The entire system operates directly inside your college's Google Workspace (Google Drive & Google Sheets), ensuring 100% data ownership and zero external database costs.

---

## 🚀 Part 1: How to Run the Local Simulator (Prototype)

To verify the styling, user roles, exam engine, tab-monitoring, and reports locally on your machine before uploading to Google Drive:

1. **Open your Terminal** (PowerShell or Command Prompt) in this directory:
   ```bash
   cd "c:\Users\fotonlabz\Desktop\Test Portal"
   ```
2. **Install Node.js Dependencies**:
   ```bash
   npm install
   ```
3. **Start the Local Server**:
   ```bash
   npm start
   ```
4. **Open in Browser**:
   Navigate to [http://localhost:3000](http://localhost:3000)

### Pre-configured Login Accounts
The local database (`db_mock.json`) is pre-populated with active accounts:
* **Super Admin**: Mobile `9000000001` | Password: `admin123`
* **Principal**: Mobile `9000000002` | Password: `principal123`
* **ECE HOD**: Mobile `9845000001` | Password: `password123`
* **Faculty (Lecturer/Demo)**: Mobile `9845000002` | Password: `password123`
* **Tutor / Mentor**: Mobile `9845000004` | Password: `password123`
* **Students** (ECE Semester 3):
  * `REG24EC01` | Password: `password123` (Amal Raj)
  * `REG24EC02` | Password: `password123` (Diya Elizabeth)

---

## ☁️ Part 2: How to Deploy to Google Apps Script (Production)

Once you are satisfied with the local preview, follow these steps to make it live for the entire college.

### Step 1: Create your Google Spreadsheet
Create a new Google Spreadsheet in your college Google Drive. Create the following **9 tabs** (sheets) and paste the exact headers in row 1:

1. **`Students`**
   * Headers: `Reg_No` | `Adm_No` | `Name` | `Email` | `Password` | `Branch` | `Year_Of_Admission` | `Semester` | `Photo_Drive_Link` | `Classroom_ID` | `Status`
2. **`Staff_Profiles`**
   * Headers: `Mobile_No` | `Name` | `Email` | `Branch` | `Designation` | `Password` | `Photo_Drive_Link` | `Account_Status`
3. **`Class_Management`**
   * Headers: `Classroom_ID` | `Branch` | `Batch_Year` | `Tutor_Mobile_No` | `Mentor_Mobile_No`
4. **`Subject_Faculty_Mapping`**
   * Headers: `Mapping_ID` | `Classroom_ID` | `Subject_Code` | `Subject_Name` | `Faculty_Mobile_No`
5. **`Question_Bank`**
   * Headers: `Subject_Code` | `Question_ID` | `Type` | `Question_Text` | `Options` | `Correct_Answer` | `CO_Tag`
6. **`Test_Config`**
   * Headers: `Test_ID` | `Subject_Code` | `Classroom_ID` | `Test_Name` | `Start_Time` | `End_Time` | `Duration` | `Selected_COs` | `MCQ_Count` | `Descriptive_Count` | `Target_Percentage` | `Pass_Threshold` | `Is_Active`
7. **`Test_Logs`**
   * Headers: `Log_ID` | `Reg_No` | `Test_ID` | `Action` | `Timestamp` | `Details`
8. **`Student_Responses`**
   * Headers: `Response_ID` | `Reg_No` | `Test_ID` | `Question_ID` | `Selected_Option` | `Descriptive_Text` | `Marks_Obtained` | `Evaluated_By` | `Status`
9. **`Series_Test_Marks`**
   * Headers: `Mark_ID` | `Reg_No` | `Classroom_ID` | `Subject_Code` | `Series_Exam_Name` | `CO_Tag` | `Max_Marks` | `Marks_Obtained` | `Entered_By` | `Timestamp`

### Step 2: Open Google Apps Script Editor
1. In your Google Spreadsheet, click **Extensions** -> **Apps Script**.
2. Remove any default code in `Code.gs`.

### Step 3: Copy-Paste Code Files
Create matching files in the Google Apps Script sidebar:
1. Create script files (`.gs`) and copy contents:
   * `Code.gs` (from `Code.gs`)
   * `Auth.gs` (from `Auth.gs`)
   * `DataService.gs` (from `DataService.gs`)
   * `AIEvaluator.gs` (from `AIEvaluator.gs`)
   * `Report.gs` (from `Report.gs`)
2. Create HTML files (`.html`) and copy contents:
   * `Common_CSS.html` (from `Common_CSS.html`)
   * `Login.html` (from `Login.html`)
   * `Student_Exam.html` (from `Student_Exam.html`)
   * `Faculty_Dashboard.html` (from `Faculty_Dashboard.html`)
   * `HOD_Dashboard.html` (from `HOD_Dashboard.html`)
   * `Tutor_Dashboard.html` (from `Tutor_Dashboard.html`)
   * `Admin_Dashboard.html` (from `Admin_Dashboard.html`)

### Step 4: Add Gemini AI API Key (Optional)
To enable automated grading of descriptive answers:
1. Go to the Apps Script editor **Project Settings** (gear icon).
2. Under **Script Properties**, click **Add script property**.
3. Set Property Name: `GEMINI_API_KEY`
4. Set Property Value: *(Your Gemini API Key from Google AI Studio)*
5. Click **Save script properties**.

### Step 5: Deploy the Web App
1. In the top-right corner of the editor, click **Deploy** -> **New deployment**.
2. Click the gear icon next to "Select type" and choose **Web app**.
3. Configure settings:
   * **Execute as**: `Me (your-institutional-email)`
   * **Who has access**: `Anyone` *(Crucial so students can access from their devices)*
4. Click **Deploy**.
5. Copy the generated **Web App URL** – this is the link students and staff will use to access the Carmel Linx portal!

---

## 🔮 Part 3: Future Add-on Extensions Roadmap

To maintain a clean, simple, and easily debuggable codebase, you should always **create independent HTML and GS files** for new features rather than adding complex nested logic to existing dashboards. 

Here is the architectural blueprint for expanding Carmel Linx:

### 1. Proposed Spreadsheet Tabs (Database Extensions)
To support upcoming features, add these sheets as tabs in your Google Spreadsheet:
* **`Attendance_Logs`**: `Log_ID` | `Reg_No` | `Classroom_ID` | `Date` | `Status` (Present/Absent) | `Hour`
* **`Assignment_Marks`**: `Entry_ID` | `Reg_No` | `Subject_Code` | `Assignment_No` | `CO_Tag` | `Max_Marks` | `Marks_Obtained`
* **`Tutor_Diary`**: `Diary_ID` | `Classroom_ID` | `Date` | `Topic_Discussed` | `Action_Taken` | `Remarks`

### 2. Proposed Independent HTML Views
Create these files as independent frontend layers in your GAS project:
* **`Tutor_Diary.html`**: A dedicated form for entering and reviewing online tutor/mentor diaries.
* **`Attendance_Roster.html`**: A high-speed, checkbox-based student attendance register grid.
* **`Course_File_Generator.html`**: A printable layout aggregating syllabus documents, question banks, attendance summaries, quiz averages, and attainment levels for a given subject.

### 3. Proposed Backend Scripts (`.gs`)
* **`AttainmentCalculator.gs`**:
  * Implement `calculateProgramOutcomeAttainment()` to aggregate Course Outcomes (COs) and map them to Program Outcomes (POs).
  * Write a function to bundle and export a compiled Course File into a clean PDF format.

