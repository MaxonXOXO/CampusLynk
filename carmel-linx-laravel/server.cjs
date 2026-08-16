const http = require('http');
const fs = require('fs');
const path = require('path');
const url = require('url');

const PORT = 8000;
const VIEWS_DIR = path.join(__dirname, 'resources', 'views');
const PUBLIC_DIR = path.join(__dirname, 'public');

function renderBlade(filename, data = {}) {
  const filePath = path.join(VIEWS_DIR, filename);
  if (!fs.existsSync(filePath)) {
    return `<h3>Template Not Found: ${filename}</h3>`;
  }

  let content = fs.readFileSync(filePath, 'utf8');

  // Handle @include('filename') or @include('folder.filename')
  content = content.replace(/@include\(['"]([^'"]+)['"]\)/g, (match, viewName) => {
    const relPath = viewName.replace(/\./g, '/') + '.blade.php';
    const subFilePath = path.join(VIEWS_DIR, relPath);
    if (fs.existsSync(subFilePath)) {
      return renderBlade(relPath, data);
    }
    return `<!-- Include missing: ${viewName} -->`;
  });

  // Handle Blade interpolations & directives
  content = content
    .replace(/\{\{\s*csrf_token\(\)\s*\}\}/g, 'mock_csrf_token_12345')
    .replace(/\{\{\s*route\(['"]([^'"]+)['"]\)\s*\}\}/g, '#')
    .replace(/\{\{\s*asset\(['"]([^'"]+)['"]\)\s*\}\}/g, (match, assetPath) => '/' + assetPath)
    .replace(/@csrf/g, '<input type="hidden" name="_token" value="mock_csrf_token_12345">')
    .replace(/@auth[\s\S]*?@endauth/g, '')
    .replace(/@guest[\s\S]*?@endguest/g, '')
    .replace(/@if\s*\([^)]+\)/g, '')
    .replace(/@elseif\s*\([^)]+\)/g, '')
    .replace(/@else/g, '')
    .replace(/@endif/g, '')
    .replace(/@foreach\s*\([^)]+\)/g, '')
    .replace(/@endforeach/g, '');

  return content;
}

const server = http.createServer((req, res) => {
  const parsedUrl = url.parse(req.url, true);
  const pathname = parsedUrl.pathname;

  // Handle Static Asset Requests (images, JS, CSS)
  if (pathname.startsWith('/storage/') || pathname.endsWith('.jpg') || pathname.endsWith('.png') || pathname.endsWith('.css') || pathname.endsWith('.js')) {
    const staticFilePath = path.join(PUBLIC_DIR, pathname);
    if (fs.existsSync(staticFilePath)) {
      const ext = path.extname(staticFilePath).toLowerCase();
      const mimeTypes = {
        '.jpg': 'image/jpeg',
        '.png': 'image/png',
        '.css': 'text/css',
        '.js': 'application/javascript',
        '.json': 'application/json'
      };
      res.writeHead(200, { 'Content-Type': mimeTypes[ext] || 'application/octet-stream' });
      return res.end(fs.readFileSync(staticFilePath));
    }
  }

  // Handle Page Routes
  let template = 'login.blade.php';

  if (pathname === '/login' || pathname === '/' || parsedUrl.query.page === 'login' || parsedUrl.query.page === 'Login') {
    template = 'login.blade.php';
  } else if (pathname === '/student-dashboard' || parsedUrl.query.page === 'student' || parsedUrl.query.page === 'Student_Exam') {
    template = 'student_dashboard.blade.php';
  } else if (pathname === '/hod-dashboard' || parsedUrl.query.page === 'hod' || parsedUrl.query.page === 'HOD') {
    template = 'hod_dashboard.blade.php';
  } else if (pathname === '/lecturer-dashboard' || parsedUrl.query.page === 'lecturer' || parsedUrl.query.page === 'Faculty') {
    template = 'lecturer_dashboard.blade.php';
  } else if (pathname === '/tutor-dashboard' || parsedUrl.query.page === 'tutor' || parsedUrl.query.page === 'Tutor') {
    template = 'tutor_dashboard.blade.php';
  } else if (pathname === '/admin-control-desk' || parsedUrl.query.page === 'admin' || parsedUrl.query.page === 'Admin') {
    template = 'admin_control_desk.blade.php';
  } else if (pathname === '/principal-dashboard' || parsedUrl.query.page === 'principal' || parsedUrl.query.page === 'Principal') {
    template = 'chairman_dashboard.blade.php';
  }

  try {
    const html = renderBlade(template);
    res.writeHead(200, { 'Content-Type': 'text/html; charset=utf-8' });
    res.end(html);
  } catch (err) {
    res.writeHead(500, { 'Content-Type': 'text/html' });
    res.end(`<h3>Server Error Rendering ${template}</h3><pre>${err.stack}</pre>`);
  }
});

server.listen(PORT, () => {
  console.log(`========================================================`);
  console.log(` CARMEL LINX LARAVEL MOCK SERVER RUNNING`);
  console.log(` Directory: carmel-linx-laravel`);
  console.log(` URL: http://localhost:${PORT}`);
  console.log(`========================================================`);
});
