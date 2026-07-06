const fs = require('fs');
const path = require('path');
const express = require('express');
const vm = require('vm');

const app = express();
const port = 8000;

app.use(express.json({ limit: '10mb' }));
app.use(express.urlencoded({ limit: '10mb', extended: true }));

// Load and save local JSON database
const dbPath = path.join(__dirname, 'db_mock.json');
let db = JSON.parse(fs.readFileSync(dbPath, 'utf8'));

function saveDb() {
  fs.writeFileSync(dbPath, JSON.stringify(db, null, 2), 'utf8');
}

// ============================================================================
// SCHEMAS DEFINITIONS FOR GLOBAL, BRANCH, AND BATCH TABLES
// ============================================================================
const SCHEMAS = {
  "Staff_Profiles": ["Mobile_No", "Name", "Email", "Branch", "Designation", "Password", "Photo_Drive_Link", "Account_Status"],
  "Class_Management": ["Classroom_ID", "Branch", "Batch_Year", "Tutor_Mobile_No", "Mentor_Mobile_No"],
  "Database_Registry": ["Registry_Key", "Spreadsheet_ID", "Folder_ID"],
  "PO_Config": ["PO_ID", "PO_Name", "Description"],
  "Branch_Config": ["Branch_Code", "Vision", "Mission", "PEOs", "PSOs"],
  "Institution_Config": ["Config_Key", "Config_Value"],
  "Staff_Branch_Assignment": ["Assignment_ID", "Staff_Mobile", "Branch_Code"],
  "Students_Registry_Lookup": ["Reg_No", "Adm_No", "Email", "Password", "Classroom_ID", "Status"],
  "Question_Bank": ["Subject_Code", "Question_ID", "Type", "Question_Text", "Options", "Correct_Answer", "CO_Tag", "Marks"],
  "Syllabus_Registry": ["Subject_Code", "Revision_Year", "Subject_Name", "CO_Count"],
  "Model_Exams_Configs": ["Exam_ID", "Subject_Code", "Exam_Name", "Config_JSON"],
  "Students": ["Reg_No", "Adm_No", "Name", "Email", "Password", "Phone", "Branch", "Admission_Year", "Admission_Type", "Photo_Drive_Link", "Classroom_ID", "Status", "SBTE_Reg_No", "Mentor_Mobile_No"],
  "Subject_Faculty_Mapping": ["Mapping_ID", "Classroom_ID", "Subject_Code", "Subject_Name", "Faculty_Mobile_No", "Course_Type", "Attainment_Threshold"],
  "Attendance_Logs": ["Log_ID", "Reg_No", "Subject_Code", "Date", "Hour", "Status", "Logged_By"],
  "Class_Logs": ["Log_ID", "Subject_Code", "Date", "Hour", "Topic_Covered", "Remarks", "Logged_By"],
  "Lesson_Plans": ["Plan_ID", "Subject_Code", "Unit_No", "Topic", "Planned_Hours", "Status"],
  "Academic_Marks": ["Mark_ID", "Reg_No", "Subject_Code", "Category", "CO_Tag", "Max_Marks", "Marks_Obtained", "Entered_By", "Timestamp"],
  "Test_Config": ["Test_ID", "Subject_Code", "Classroom_ID", "Test_Name", "Start_Time", "End_Time", "Duration", "Selected_COs", "MCQ_Count", "Descriptive_Count", "Target_Percentage", "Pass_Threshold", "Is_Active"],
  "Student_Responses": ["Response_ID", "Reg_No", "Test_ID", "Question_ID", "Selected_Option", "Descriptive_Text", "Marks_Obtained", "Evaluated_By", "Status"],
  "Tutor_Diary": ["Diary_ID", "Reg_No", "Date", "Category", "Discussion_Notes", "Action_Taken", "Remarks", "Logged_By"],
  "Extracurricular_Activities": ["Activity_ID", "Reg_No", "Club", "Activity_Name", "Participation_Details", "Points", "Approved_By", "Timestamp"],
  "Placements_&_Training": ["Record_ID", "Reg_No", "Type", "Company_Or_Institution", "Details", "Date", "Status"],
  "Surveys_&_Feedback": ["Survey_ID", "Reg_No", "Subject_Code", "Survey_Type", "Responses_JSON", "Timestamp"]
};

// ============================================================================
// GOOGLE APPS SCRIPT SPREADSHEETAPP EMULATION LAYER
// ============================================================================

class MockRange {
  constructor(sheetName, row, col, numRows, numCols) {
    this.sheetName = sheetName;
    this.startRow = row; // 1-indexed
    this.startCol = col; // 1-indexed
    this.numRows = numRows;
    this.numCols = numCols;
  }

  getValues() {
    const rows = db[this.sheetName] || [];
    const schema = SCHEMAS[this.sheetName];
    const keys = schema || (rows.length > 0 ? Object.keys(rows[0]) : ["Field_0"]);
    const result = [];

    for (let r = 0; r < this.numRows; r++) {
      const targetRowIdx = this.startRow + r - 2; // GAS Row 2 corresponds to db index 0
      const rowArr = [];

      if (this.startRow + r === 1) {
        // Headers row
        for (let c = 0; c < this.numCols; c++) {
          rowArr.push(keys[this.startCol + c - 1] || "");
        }
      } else if (targetRowIdx >= 0 && targetRowIdx < rows.length) {
        const rowObj = rows[targetRowIdx];
        for (let c = 0; c < this.numCols; c++) {
          const keyName = keys[this.startCol + c - 1];
          rowArr.push(keyName && rowObj[keyName] !== undefined ? rowObj[keyName] : "");
        }
      } else {
        for (let c = 0; c < this.numCols; c++) rowArr.push("");
      }
      result.push(rowArr);
    }
    return result;
  }

  setValue(val) {
    const rows = db[this.sheetName] || [];
    if (rows.length === 0) return;
    const schema = SCHEMAS[this.sheetName];
    const keys = schema || Object.keys(rows[0]);
    const targetRowIdx = this.startRow - 2; // Row 2 is index 0
    const targetColIdx = this.startCol - 1;

    if (targetRowIdx >= 0 && targetRowIdx < rows.length) {
      const keyName = keys[targetColIdx];
      if (keyName) {
        rows[targetRowIdx][keyName] = val;
        saveDb();
      }
    }
  }

  setValues(grid) {
    // Setting an entire grid of values
    const rows = db[this.sheetName] || [];
    if (rows.length === 0) return;
    const schema = SCHEMAS[this.sheetName];
    const keys = schema || Object.keys(rows[0]);

    for (let r = 0; r < grid.length; r++) {
      const targetRowIdx = this.startRow + r - 2;
      if (targetRowIdx >= 0 && targetRowIdx < rows.length) {
        for (let c = 0; c < grid[r].length; c++) {
          const targetColIdx = this.startCol + c - 1;
          const keyName = keys[targetColIdx];
          if (keyName) {
            rows[targetRowIdx][keyName] = grid[r][c];
          }
        }
      }
    }
    saveDb();
  }
}

class MockSheet {
  constructor(name) {
    this.name = name;
  }

  getDataRange() {
    const rows = db[this.name] || [];
    const schema = SCHEMAS[this.name];
    const maxCols = schema ? schema.length : (rows.length > 0 ? Object.keys(rows[0]).length : 1);
    return new MockRange(this.name, 1, 1, rows.length + 1, maxCols);
  }

  getValues() {
    const rows = db[this.name] || [];
    const schema = SCHEMAS[this.name];
    const keys = schema || (rows.length > 0 ? Object.keys(rows[0]) : []);
    const result = [keys];
    for (let r of rows) {
      result.push(keys.map(k => r[k] !== undefined ? r[k] : ""));
    }
    return result;
  }

  appendRow(rowArr) {
    if (!db[this.name]) db[this.name] = [];
    const rows = db[this.name];
    let newObj = {};

    const schema = SCHEMAS[this.name];
    if (schema) {
      // Check if it is the header row being appended
      const isHeaderRow = rowArr.length === schema.length && rowArr.every((v, i) => v === schema[i]);
      if (isHeaderRow) {
        return; // Don't write header row to database as a data row
      }
    }

    const keys = schema || (rows.length > 0 ? Object.keys(rows[0]) : null);

    if (keys) {
      keys.forEach((key, idx) => {
        newObj[key] = rowArr[idx] !== undefined ? rowArr[idx] : "";
      });
      // Append extra arguments if any
      for (let i = keys.length; i < rowArr.length; i++) {
        newObj[`Field_${i}`] = rowArr[i];
      }
    } else {
      // Create schema dynamically if empty
      rowArr.forEach((val, idx) => {
        newObj[`Field_${idx}`] = val;
      });
    }
    rows.push(newObj);
    saveDb();
  }

  deleteRow(index) {
    const dbIndex = index - 2; // GAS 2 is index 0
    if (db[this.name] && dbIndex >= 0 && dbIndex < db[this.name].length) {
      db[this.name].splice(dbIndex, 1);
      saveDb();
    }
  }

  getRange(row, col, numRows = 1, numCols = 1) {
    return new MockRange(this.name, row, col, numRows, numCols);
  }

  getLastRow() {
    return (db[this.name] || []).length + 1;
  }
}

const SpreadsheetApp = {
  openById: (id) => spreadsheet,
  getActiveSpreadsheet: () => spreadsheet
};

const spreadsheet = {
  getId: () => 'CARMEL_SPREADSHEET_MOCK',
  getSheetByName: (name) => {
    if (!db[name]) {
      db[name] = [];
      saveDb();
    }
    return new MockSheet(name);
  },
  insertSheet: (name) => {
    if (!db[name]) {
      db[name] = [];
      saveDb();
    }
    return new MockSheet(name);
  },
  deleteSheet: (sheet) => {
    // mock delete operation is optional, do nothing or delete key from db
    return;
  }
};

const PropertiesService = {
  getScriptProperties: () => ({
    getProperty: (key) => mockProperties[key] || null,
    setProperty: (key, val) => { mockProperties[key] = val.toString(); },
    deleteProperty: (key) => { delete mockProperties[key]; }
  })
};
let mockProperties = {};

const mockFolder = (name) => ({
  getFoldersByName: (subName) => ({
    hasNext: () => false,
    next: () => mockFolder(subName)
  }),
  createFolder: (subName) => mockFolder(subName),
  getId: () => "MOCK_FOLDER_ID_" + name.toUpperCase().replace(/\s+/g, "_"),
  addFile: () => {},
  removeFile: () => {}
});

const DriveApp = {
  getFoldersByName: (name) => ({
    hasNext: () => false,
    next: () => mockFolder(name)
  }),
  createFolder: (name) => mockFolder(name),
  getFileById: (id) => ({
    getId: () => id,
    setSharing: () => {}
  }),
  getRootFolder: () => mockFolder("root"),
  Access: { ANYONE_WITH_LINK: 'ANYONE_WITH_LINK' },
  Permission: { VIEW: 'VIEW' }
};

// ============================================================================
// HTMLSERVICE EMULATION & INJECTORS
// ============================================================================

class MockHtmlTemplate {
  constructor(filename) {
    this.filename = filename;
  }
  evaluate() {
    const filePath = path.join(__dirname, this.filename + '.html');
    if (!fs.existsSync(filePath)) {
      throw new Error(`Template file not found: ${this.filename}.html`);
    }
    let html = fs.readFileSync(filePath, 'utf8');
    html = resolveIncludes(html);
    return new MockHtmlOutput(html);
  }
}

class MockHtmlOutput {
  constructor(html) {
    this.html = html;
    this.title = "Carmel Linx";
  }
  setTitle(title) { this.title = title; return this; }
  addMetaTag() { return this; }
  setXFrameOptionsMode() { return this; }
  getContent() { return this.html; }
}

const HtmlService = {
  createTemplateFromFile: (filename) => new MockHtmlTemplate(filename),
  createHtmlOutputFromFile: (filename) => {
    const filePath = path.join(__dirname, filename + '.html');
    if (!fs.existsSync(filePath)) {
      throw new Error(`Html output file not found: ${filename}.html`);
    }
    let html = fs.readFileSync(filePath, 'utf8');
    html = resolveIncludes(html);
    return new MockHtmlOutput(html);
  },
  XFrameOptionsMode: { ALLOWALL: 'ALLOWALL' }
};

function resolveIncludes(html) {
  return html.replace(/<\?!=?\s*include\(['"]([^'"]+)['"]\);\s*\?>/g, (match, filename) => {
    const filePath = path.join(__dirname, filename + '.html');
    if (fs.existsSync(filePath)) {
      let content = fs.readFileSync(filePath, 'utf8');
      return resolveIncludes(content); // resolve nested includes
    }
    return `<!-- Include failed: ${filename} -->`;
  });
}

// ============================================================================
// SANDBOXED VM CONTEXT LOADING
// ============================================================================

const context = {
  SpreadsheetApp,
  HtmlService,
  PropertiesService,
  DriveApp,
  Logger: {
    log: (...args) => console.log('[GAS LOG]:', ...args)
  },
  console,
  JSON,
  Math,
  Date,
  Array,
  Object,
  String,
  Number,
  Boolean,
  RegExp,
  encodeURIComponent,
  decodeURIComponent,
  include: (filename) => {
    return HtmlService.createHtmlOutputFromFile(filename).getContent();
  }
};

vm.createContext(context);

// Load script files in specific dependency order
const scripts = ['DataService.gs', 'Auth.gs', 'AIEvaluator.gs', 'Report.gs', 'Code.gs'];
scripts.forEach(script => {
  const filePath = path.join(__dirname, script);
  if (fs.existsSync(filePath)) {
    const code = fs.readFileSync(filePath, 'utf8');
    vm.runInContext(code, context, { filename: script });
  }
});

// Inject the custom client script representing google.script.run
const GOOGLE_SCRIPT_RUN_MOCK = `
<script>
(function() {
  const createRunner = (success, failure) => {
    const runner = {
      withSuccessHandler: (s) => createRunner(s, failure),
      withFailureHandler: (f) => createRunner(success, f)
    };
    return new Proxy(runner, {
      get: (target, prop) => {
        if (prop in target) return target[prop];
        return function(...args) {
          fetch('/api/run', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ action: prop, args: args })
          })
          .then(res => res.json())
          .then(res => {
            if (res && res.status === 'ERROR') {
              if (failure) failure(res.message || res);
              else alert('GAS Error: ' + (res.message || JSON.stringify(res)));
            } else {
              if (success) success(res);
            }
          })
          .catch(err => {
            if (failure) failure(err);
            else console.error('Network Error:', err);
          });
        };
      }
    });
  };

  window.google = {
    script: {
      run: createRunner(null, null)
    }
  };
})();
</script>
`;

// ============================================================================
// EXPRESS WEB SERVER ROUTES
// ============================================================================

// Main GET entry mimicking doGet
app.get('/', (req, res) => {
  const queryPage = req.query.page || 'Login';
  console.log(`[GET /] Page parameter: ${queryPage}`);
  try {
    const e = { parameter: { page: queryPage } };
    
    // Call doGet(e) in the GAS VM context
    const output = context.doGet(e);
    console.log(`[GET /] doGet completed successfully`);
    let htmlContent = output.getContent();

    // Inject our mock google.script.run script right before closing body
    htmlContent = htmlContent.replace('</body>', `${GOOGLE_SCRIPT_RUN_MOCK}</body>`);
    console.log(`[GET /] Script injection completed`);

    res.send(htmlContent);
  } catch (err) {
    console.error(`[GET /] Error rendering page:`, err);
    res.status(500).send(`<h3>Server Simulator Compilation Error</h3><pre>${err.stack}</pre>`);
  }
});

// API endpoint mimicking google.script.run
app.post('/api/run', (req, res) => {
  const { action, args } = req.body;
  console.log(`[API RUN] Action: ${action}, Args:`, JSON.stringify(args));
  
  if (typeof context[action] === 'function') {
    try {
      // Refresh DB before run
      db = JSON.parse(fs.readFileSync(dbPath, 'utf8'));
      
      const result = context[action].apply(null, args);
      console.log(`[API RUN] Success result:`, JSON.stringify(result));
      res.json(result);
    } catch (err) {
      console.error(`[API RUN] Error executing ${action}:`, err);
      res.json({ status: "ERROR", message: err.toString() });
    }
  } else {
    console.warn(`[API RUN] Warning: function ${action} not found.`);
    res.json({ status: "ERROR", message: `GAS function '${action}' is not defined in any loaded scripts.` });
  }
});

app.listen(port, () => {
  console.log(`========================================================`);
  console.log(` CARMEL LINX LOCAL PROTOTYPE SERVER RUNNING`);
  console.log(` Open your browser and navigate to: http://localhost:3000`);
  console.log(`========================================================`);
});
