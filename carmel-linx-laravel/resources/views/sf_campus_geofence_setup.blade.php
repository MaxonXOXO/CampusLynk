<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Campus GPS Location Core Setup | Carmel Linx</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #2563eb;
            --primary-dark: #1d4ed8;
            --success: #10b981;
            --warning: #f59e0b;
            --bg-dark: #0f172a;
            --card-bg: #1e293b;
            --text-light: #f8fafc;
            --text-muted: #94a3b8;
            --border-color: #334155;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Inter', sans-serif;
            -webkit-tap-highlight-color: transparent;
        }

        body {
            background-color: var(--bg-dark);
            color: var(--text-light);
            min-height: 100vh;
            padding-bottom: 40px;
        }

        .header {
            background: rgba(30, 41, 59, 0.85);
            backdrop-filter: blur(12px);
            padding: 16px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid var(--border-color);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .header h1 {
            font-size: 1.15rem;
            font-weight: 700;
            color: #60a5fa;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .back-btn {
            color: var(--text-muted);
            font-size: 1.1rem;
            text-decoration: none;
            padding: 6px 12px;
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.05);
        }

        .container {
            max-width: 600px;
            margin: 20px auto;
            padding: 0 16px;
        }

        .setup-card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 20px;
            padding: 24px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
        }

        .card-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 20px;
            padding-bottom: 16px;
            border-bottom: 1px solid var(--border-color);
        }

        .card-header i {
            font-size: 1.5rem;
            color: var(--primary);
        }

        .card-header h2 {
            font-size: 1.1rem;
            font-weight: 700;
        }

        .card-header p {
            font-size: 0.8rem;
            color: var(--text-muted);
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-group label {
            display: block;
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--text-muted);
            margin-bottom: 6px;
        }

        .input-wrapper {
            position: relative;
        }

        .input-wrapper i {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
        }

        .form-control {
            width: 100%;
            padding: 12px 14px 12px 42px;
            background: #0f172a;
            border: 1px solid var(--border-color);
            border-radius: 12px;
            color: var(--text-light);
            font-size: 0.95rem;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.2);
        }

        .btn-capture {
            width: 100%;
            padding: 14px;
            background: rgba(37, 99, 235, 0.15);
            border: 1px solid rgba(37, 99, 235, 0.4);
            border-radius: 12px;
            color: #60a5fa;
            font-weight: 700;
            font-size: 0.9rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-bottom: 20px;
            transition: all 0.2s ease;
        }

        .btn-capture:hover {
            background: rgba(37, 99, 235, 0.25);
        }

        .btn-save {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border: none;
            border-radius: 12px;
            color: #fff;
            font-weight: 700;
            font-size: 1rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            box-shadow: 0 4px 14px rgba(37, 99, 235, 0.3);
            transition: all 0.2s ease;
        }

        .btn-save:active {
            transform: scale(0.98);
        }

        .alert-success {
            background: rgba(16, 185, 129, 0.15);
            border: 1px solid rgba(16, 185, 129, 0.3);
            color: #34d399;
            padding: 12px 16px;
            border-radius: 12px;
            margin-bottom: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
        }
    </style>
</head>
<body>

    <header class="header">
        <a href="javascript:history.back()" class="back-btn"><i class="fa-solid fa-chevron-left"></i> Back</a>
        <h1><i class="fa-solid fa-location-dot"></i> Campus GPS Setup</h1>
        <span style="font-size: 0.75rem; background: rgba(16, 185, 129, 0.2); color: #34d399; padding: 4px 10px; border-radius: 20px; font-weight: 600;">Control Desk</span>
    </header>

    <div class="container">
        @if(session('success'))
            <div class="alert-success">
                <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
            </div>
        @endif

        <div class="setup-card">
            <div class="card-header">
                <i class="fa-solid fa-map-location-dot"></i>
                <div>
                    <h2>College Premises Boundary Setup</h2>
                    <p>Enforce location accuracy for SF staff mobile time punching.</p>
                </div>
            </div>

            <button type="button" class="btn-capture" onclick="captureCurrentGPS()">
                <i class="fa-solid fa-crosshairs"></i> Capture My Current Location as Campus Centroid
            </button>

            <form action="/sf-attendance/geofence-setup" method="POST">
                @csrf
                <div class="form-group">
                    <label>Campus Name</label>
                    <div class="input-wrapper">
                        <i class="fa-solid fa-building-columns"></i>
                        <input type="text" name="campus_name" class="form-control" value="{{ $geofence->campus_name }}" required>
                    </div>
                </div>

                <div class="form-group">
                    <label>Centroid Latitude (°N)</label>
                    <div class="input-wrapper">
                        <i class="fa-solid fa-arrows-up-down"></i>
                        <input type="number" step="any" id="inputLat" name="centroid_lat" class="form-control" value="{{ $geofence->centroid_lat }}" required>
                    </div>
                </div>

                <div class="form-group">
                    <label>Centroid Longitude (°E)</label>
                    <div class="input-wrapper">
                        <i class="fa-solid fa-arrows-left-right"></i>
                        <input type="number" step="any" id="inputLng" name="centroid_lng" class="form-control" value="{{ $geofence->centroid_lng }}" required>
                    </div>
                </div>

                <div class="form-group">
                    <label>Allowed Campus Radius (Meters)</label>
                    <div class="input-wrapper">
                        <i class="fa-solid fa-ruler-combined"></i>
                        <input type="number" name="radius_meters" class="form-control" value="{{ $geofence->radius_meters }}" min="10" max="5000" required>
                    </div>
                </div>

                <div class="form-group">
                    <label>Max Device GPS Accuracy Limit (Meters)</label>
                    <div class="input-wrapper">
                        <i class="fa-solid fa-bullseye"></i>
                        <input type="number" name="max_accuracy_meters" class="form-control" value="{{ $geofence->max_accuracy_meters }}" min="5" max="200" required>
                    </div>
                </div>

                <button type="submit" class="btn-save">
                    <i class="fa-solid fa-floppy-disk"></i> Save GPS Location Core Setup
                </button>
            </form>
        </div>
    </div>

    <script>
        function captureCurrentGPS() {
            if ("geolocation" in navigator) {
                navigator.geolocation.getCurrentPosition((pos) => {
                    document.getElementById('inputLat').value = pos.coords.latitude.toFixed(8);
                    document.getElementById('inputLng').value = pos.coords.longitude.toFixed(8);
                    alert("Current GPS coordinates captured successfully!\nLatitude: " + pos.coords.latitude + "\nLongitude: " + pos.coords.longitude);
                }, (err) => {
                    alert("Unable to fetch current GPS coordinates. Please grant location access.");
                }, { enableHighAccuracy: true });
            } else {
                alert("Geolocation is not supported by your browser.");
            }
        }
    </script>
</body>
</html>
