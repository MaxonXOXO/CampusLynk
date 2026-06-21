const fs = require('fs');
const path = require('path');

// 1. Process HOD_Dashboard.html
const hodPath = path.join(__dirname, 'HOD_Dashboard.html');
const hodContent = fs.readFileSync(hodPath, 'utf8');

// We search for '<script>' tag after line 280
const hodLines = hodContent.split('\n');
let hodScriptStartIndex = -1;
for (let i = 280; i < hodLines.length; i++) {
  if (hodLines[i].includes('<script>')) {
    hodScriptStartIndex = i;
    break;
  }
}

if (hodScriptStartIndex !== -1) {
  const hodBeforeScript = hodLines.slice(0, hodScriptStartIndex).join('\n');
  const hodScriptBody = hodLines.slice(hodScriptStartIndex).join('\n');
  
  // Save script to separate file HOD_JS.html
  const hodJsPath = path.join(__dirname, 'HOD_JS.html');
  fs.writeFileSync(hodJsPath, hodScriptBody, 'utf8');
  console.log('Created HOD_JS.html successfully.');
  
  // Replace script in HOD_Dashboard.html with GAS include
  const newHodDashboard = hodBeforeScript + '\n  <?!= include(\'HOD_JS\'); ?>\n</body>\n</html>\n';
  fs.writeFileSync(hodPath, newHodDashboard, 'utf8');
  console.log('Updated HOD_Dashboard.html successfully.');
} else {
  console.log('Could not find script start in HOD_Dashboard.html');
}

// 2. Process Principal_Dashboard.html
const prinPath = path.join(__dirname, 'Principal_Dashboard.html');
const prinContent = fs.readFileSync(prinPath, 'utf8');

const prinLines = prinContent.split('\n');
let prinScriptStartIndex = -1;
for (let i = 240; i < prinLines.length; i++) {
  if (prinLines[i].includes('<script>')) {
    prinScriptStartIndex = i;
    break;
  }
}

if (prinScriptStartIndex !== -1) {
  const prinBeforeScript = prinLines.slice(0, prinScriptStartIndex).join('\n');
  const prinScriptBody = prinLines.slice(prinScriptStartIndex).join('\n');
  
  // Save script to separate file Principal_JS.html
  const prinJsPath = path.join(__dirname, 'Principal_JS.html');
  fs.writeFileSync(prinJsPath, prinScriptBody, 'utf8');
  console.log('Created Principal_JS.html successfully.');
  
  // Replace script in Principal_Dashboard.html with GAS include
  const newPrinDashboard = prinBeforeScript + '\n  <?!= include(\'Principal_JS\'); ?>\n</body>\n</html>\n';
  fs.writeFileSync(prinPath, newPrinDashboard, 'utf8');
  console.log('Updated Principal_Dashboard.html successfully.');
} else {
  console.log('Could not find script start in Principal_Dashboard.html');
}
