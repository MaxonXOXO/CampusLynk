<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parent Portal Access — Carmel Polytechnic College</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Google Fonts & FontAwesome -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --bg-dark: #0b0f19;
            --card-bg: rgba(15, 23, 42, 0.85);
            --accent-cyan: #06b6d4;
            --accent-purple: #8b5cf6;
            --accent-emerald: #10b981;
        }

        body {
            background-color: var(--bg-dark);
            background-image: 
                radial-gradient(at 10% 20%, rgba(6, 182, 212, 0.12) 0px, transparent 50%),
                radial-gradient(at 90% 80%, rgba(139, 92, 246, 0.12) 0px, transparent 50%);
            color: #f1f5f9;
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-card {
            background: var(--card-bg);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            width: 100%;
            max-width: 440px;
            padding: 35px 30px;
        }

        .form-control-custom {
            background: rgba(15, 23, 42, 0.9);
            border: 1px solid rgba(255, 255, 255, 0.15);
            color: #ffffff;
            border-radius: 12px;
            padding: 12px 16px;
            font-size: 0.95rem;
            transition: all 0.2s ease;
        }

        .form-control-custom:focus {
            background: rgba(15, 23, 42, 0.95);
            border-color: var(--accent-cyan);
            box-shadow: 0 0 0 3px rgba(6, 182, 212, 0.25);
            color: #ffffff;
        }

        .btn-portal {
            background: linear-gradient(135deg, #06b6d4 0%, #3b82f6 100%);
            color: #ffffff;
            border: none;
            border-radius: 12px;
            padding: 12px;
            font-weight: 700;
            font-size: 1rem;
            letter-spacing: 0.3px;
            box-shadow: 0 10px 20px -5px rgba(6, 182, 212, 0.4);
            transition: all 0.2s ease;
        }

        .btn-portal:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 25px -5px rgba(6, 182, 212, 0.5);
            color: #ffffff;
        }

        .info-pill {
            background: rgba(6, 182, 212, 0.1);
            border: 1px solid rgba(6, 182, 212, 0.2);
            border-radius: 10px;
            padding: 10px 14px;
            font-size: 0.8rem;
            color: #a5f3fc;
        }
    </style>
</head>
<body>

    <div class="login-card">
        <div class="text-center mb-4">
            <div class="d-inline-flex align-items-center justify-content-center bg-info bg-opacity-10 text-cyan rounded-circle p-3 mb-3 border border-info border-opacity-25" style="width: 60px; height: 60px;">
                <i class="fa-solid fa-user-shield fs-3 text-cyan"></i>
            </div>
            <h4 class="fw-bold mb-1">Parent Portal Access</h4>
            <p class="text-secondary small mb-0">Carmel Polytechnic College, Alappuzha</p>
        </div>

        <div class="info-pill mb-4 text-center">
            <i class="fa-solid fa-key me-1"></i> <strong>Zero Password Access:</strong> Verify using your ward's Register Number and your Registered Mobile Number.
        </div>

        <form id="parentLoginForm">
            <div class="mb-3">
                <label class="form-label text-slate-300 small fw-semibold">
                    <i class="fa-solid fa-id-card me-1 text-cyan"></i> Student Register Number / Admission No
                </label>
                <input type="text" id="regNo" class="form-control form-control-custom" placeholder="e.g. 24010152" required uppercase>
            </div>

            <div class="mb-4">
                <label class="form-label text-slate-300 small fw-semibold">
                    <i class="fa-solid fa-mobile-screen-button me-1 text-cyan"></i> Guardian Registered Mobile Number
                </label>
                <input type="tel" id="guardianMobile" class="form-control form-control-custom" placeholder="e.g. 9876543210" required>
            </div>

            <div id="loginError" class="alert alert-danger py-2 px-3 small d-none mb-3" role="alert"></div>

            <button type="submit" id="btnSubmit" class="btn btn-portal w-100 mb-3">
                <i class="fa-solid fa-arrow-right-to-bracket me-2"></i> View Ward's Dashboard
            </button>
        </form>

        <div class="text-center mt-3 pt-3 border-top border-secondary border-opacity-25">
            <a href="/login" class="text-secondary text-decoration-none small">
                <i class="fa-solid fa-arrow-left me-1"></i> Back to Campus Login
            </a>
        </div>
    </div>

    <script>
        const form = document.getElementById('parentLoginForm');
        const errDiv = document.getElementById('loginError');
        const btnSubmit = document.getElementById('btnSubmit');

        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            errDiv.classList.add('d-none');
            btnSubmit.disabled = true;
            btnSubmit.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-2"></i> Verifying...';

            const regNo = document.getElementById('regNo').value.trim();
            const guardianMobile = document.getElementById('guardianMobile').value.trim();

            try {
                const response = await fetch('/parent/login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ regNo, guardianMobile })
                });

                const data = await response.json();

                if (data.status === 'SUCCESS') {
                    window.location.href = data.redirect;
                } else {
                    errDiv.innerText = data.message || 'Invalid details. Please try again.';
                    errDiv.classList.remove('d-none');
                    btnSubmit.disabled = false;
                    btnSubmit.innerHTML = '<i class="fa-solid fa-arrow-right-to-bracket me-2"></i> View Ward\'s Dashboard';
                }
            } catch (err) {
                errDiv.innerText = 'Network error. Please check your connection.';
                errDiv.classList.remove('d-none');
                btnSubmit.disabled = false;
                btnSubmit.innerHTML = '<i class="fa-solid fa-arrow-right-to-bracket me-2"></i> View Ward\'s Dashboard';
            }
        });
    </script>
</body>
</html>
