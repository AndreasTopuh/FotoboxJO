<?php
session_start();

// Check admin access
if (!isset($_SESSION['admin_access']) || $_SESSION['admin_access'] !== true) {
    header('Location: admin-login.php');
    exit();
}

// Include PWA helper
require_once '../includes/pwa-helper.php';

// Data file path - using user-photos directory for consistency
$dataFile = __DIR__ . '/../user-photos/cash_codes.json';

// Ensure user-photos directory exists
$dataDir = dirname($dataFile);
if (!is_dir($dataDir)) {
    mkdir($dataDir, 0755, true);
}

// Initialize data file if it doesn't exist
if (!file_exists($dataFile)) {
    file_put_contents($dataFile, json_encode([]));
}

// Handle AJAX requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');

    $input = json_decode(file_get_contents('php://input'), true);

    if ($input['action'] === 'generate_code') {
        // Read existing codes
        $codes = json_decode(file_get_contents($dataFile), true) ?: [];

        // Generate unique 5-digit code
        do {
            $newCode = str_pad(mt_rand(10000, 99999), 5, '0', STR_PAD_LEFT);
            // Avoid admin codes
            if ($newCode === '11000' || $newCode === '54321') {
                continue;
            }
        } while (isset($codes[$newCode]));

        // Add new code
        $codes[$newCode] = [
            'generated_at' => date('Y-m-d H:i:s'),
            'used' => false,
            'used_at' => null
        ];

        // Save to file
        if (file_put_contents($dataFile, json_encode($codes, JSON_PRETTY_PRINT))) {
            echo json_encode([
                'success' => true,
                'code' => $newCode,
                'message' => 'Kode berhasil di-generate'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Gagal menyimpan kode'
            ]);
        }
        exit();
    }

    if ($input['action'] === 'get_codes') {
        $codes = json_decode(file_get_contents($dataFile), true) ?: [];
        echo json_encode(['success' => true, 'codes' => $codes]);
        exit();
    }
}

// Read codes for display
$codes = json_decode(file_get_contents($dataFile), true) ?: [];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Dashboard - GoBooth</title>

    <?php PWAHelper::addPWAHeaders(); ?>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="home-styles.css" />
    <style>
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
            min-height: 100vh;
        }

        .admin-header {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 2rem;
            backdrop-filter: blur(10px);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.3);
            text-align: center;
        }

        .admin-title {
            color: #333;
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .admin-subtitle {
            color: #666;
            font-size: 1.1rem;
            margin-bottom: 0;
        }

        .generate-section {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 2rem;
            backdrop-filter: blur(10px);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.3);
            text-align: center;
        }

        .generate-btn {
            background: var(--pink-primary);
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 12px;
            font-size: 1.2rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-bottom: 1rem;
        }

        .generate-btn:hover {
            background: var(--pink-secondary);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(233, 30, 99, 0.3);
        }

        .generate-btn:disabled {
            background: #ccc;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        .latest-code {
            background: #e8f5e8;
            border: 2px solid #4caf50;
            border-radius: 12px;
            padding: 20px;
            margin-top: 1rem;
            display: none;
        }

        .latest-code.show {
            display: block;
        }

        .code-display {
            font-size: 2rem;
            font-weight: 700;
            color: #2e7d32;
            font-family: 'Courier New', monospace;
            letter-spacing: 5px;
            margin-bottom: 10px;
        }

        .code-instruction {
            color: #388e3c;
            font-weight: 600;
        }

        .codes-list {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            padding: 2rem;
            backdrop-filter: blur(10px);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .codes-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .codes-title {
            color: #333;
            font-size: 1.5rem;
            font-weight: 700;
            margin: 0;
        }

        .refresh-btn {
            background: #f0f0f0;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 8px 15px;
            cursor: pointer;
            font-size: 0.9rem;
            transition: all 0.2s ease;
        }

        .refresh-btn:hover {
            background: #e0e0e0;
        }

        .codes-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }

        .codes-table th,
        .codes-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .codes-table th {
            background: #f8f9fa;
            font-weight: 600;
            color: #333;
        }

        .status-unused {
            color: #28a745;
            font-weight: 600;
        }

        .status-used {
            color: #dc3545;
            font-weight: 600;
        }

        .no-codes {
            text-align: center;
            color: #666;
            font-style: italic;
            padding: 2rem;
        }

        .logout-btn {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #dc3545;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .logout-btn:hover {
            background: #c82333;
        }

        .loading {
            color: #666;
            font-style: italic;
        }

        @media (max-width: 768px) {
            .container {
                padding: 1rem;
            }

            .codes-table {
                font-size: 0.9rem;
            }

            .codes-table th,
            .codes-table td {
                padding: 8px 6px;
            }
        }
    </style>
</head>

<body>
    <div class="gradientBgCanvas"></div>

    <a href="../../admin/admin-login.php?logout=1" class="logout-btn">Logout</a>

    <div class="container">
        <!-- Header -->
        <div class="admin-header">
            <h1 class="admin-title">Admin Dashboard</h1>
            <p class="admin-subtitle">Kelola kode pembayaran cash untuk customer</p>
        </div>

        <!-- Generate Code Section -->
        <div class="generate-section">
            <button id="generateBtn" class="generate-btn" onclick="generateCode()">
                Generate Kode Cash Baru
            </button>

            <div id="latestCode" class="latest-code">
                <div class="code-display" id="codeDisplay"></div>
                <div class="code-instruction">Berikan kode ini kepada customer</div>
            </div>
        </div>

        <!-- Codes List -->
        <div class="codes-list">
            <div class="codes-header">
                <h2 class="codes-title">Riwayat Kode Cash</h2>
                <button class="refresh-btn" onclick="loadCodes()">🔄 Refresh</button>
            </div>

            <div id="codesContainer">
                <div class="loading">Memuat data...</div>
            </div>
        </div>
    </div>

    <script>
        let refreshInterval;

        // Load codes on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadCodes();
            startAutoRefresh();
        });

        async function generateCode() {
            const btn = document.getElementById('generateBtn');
            const latestCode = document.getElementById('latestCode');
            const codeDisplay = document.getElementById('codeDisplay');

            btn.disabled = true;
            btn.textContent = 'Generating...';

            try {
                const response = await fetch('admin-new.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        action: 'generate_code'
                    })
                });

                const result = await response.json();

                if (result.success) {
                    codeDisplay.textContent = result.code;
                    latestCode.classList.add('show');

                    // Auto hide after 30 seconds
                    setTimeout(() => {
                        latestCode.classList.remove('show');
                    }, 30000);

                    // Refresh codes list
                    loadCodes();
                } else {
                    alert('Gagal generate kode: ' + result.message);
                }
            } catch (error) {
                console.error('Error generating code:', error);
                alert('Terjadi kesalahan saat generate kode');
            } finally {
                btn.disabled = false;
                btn.textContent = 'Generate Kode Cash Baru';
            }
        }

        async function loadCodes() {
            const container = document.getElementById('codesContainer');
            container.innerHTML = '<div class="loading">Memuat data...</div>';

            try {
                const response = await fetch('admin-new.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        action: 'get_codes'
                    })
                });

                const result = await response.json();

                if (result.success) {
                    displayCodes(result.codes);
                } else {
                    container.innerHTML = '<div class="no-codes">Gagal memuat data kode</div>';
                }
            } catch (error) {
                console.error('Error loading codes:', error);
                container.innerHTML = '<div class="no-codes">Terjadi kesalahan saat memuat data</div>';
            }
        }

        function displayCodes(codes) {
            const container = document.getElementById('codesContainer');

            if (Object.keys(codes).length === 0) {
                container.innerHTML = '<div class="no-codes">Belum ada kode yang di-generate</div>';
                return;
            }

            // Sort codes by generation time (newest first)
            const sortedCodes = Object.entries(codes).sort((a, b) => {
                return new Date(b[1].generated_at) - new Date(a[1].generated_at);
            });

            let html = `
                <table class="codes-table">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Status</th>
                            <th>Dibuat</th>
                            <th>Digunakan</th>
                        </tr>
                    </thead>
                    <tbody>
            `;

            sortedCodes.forEach(([code, data]) => {
                const status = data.used ? 'Terpakai' : 'Belum digunakan';
                const statusClass = data.used ? 'status-used' : 'status-unused';
                const usedAt = data.used_at ? new Date(data.used_at).toLocaleString('id-ID') : '-';

                html += `
                    <tr>
                        <td style="font-family: 'Courier New', monospace; font-weight: 600; font-size: 1.1rem;">${code}</td>
                        <td class="${statusClass}">${status}</td>
                        <td>${new Date(data.generated_at).toLocaleString('id-ID')}</td>
                        <td>${usedAt}</td>
                    </tr>
                `;
            });

            html += `
                    </tbody>
                </table>
            `;

            container.innerHTML = html;
        }

        function startAutoRefresh() {
            // Refresh every 30 seconds
            refreshInterval = setInterval(() => {
                loadCodes();
            }, 30000);
        }

        // Cleanup on page unload
        window.addEventListener('beforeunload', function() {
            if (refreshInterval) {
                clearInterval(refreshInterval);
            }
        });
    </script>

    <?php PWAHelper::addPWAScript(); ?>
</body>

</html>