<?php
// Include session manager and PWA helper
require_once '../includes/session-manager.php';
require_once '../includes/pwa-helper.php';

// Check if admin is accessing with correct code
session_start();
if (!isset($_SESSION['admin_access']) || $_SESSION['admin_access'] !== true) {
    // Redirect to admin login
    header('Location: admin-login.php');
    exit();
}

// Function to get data file path (same as verify_cash_code.php and admin-fixed.php)
function getDataFilePath()
{
    // Use user-photos directory which has working permissions like image uploads
    $userPhotosPath = '/var/www/html/FotoboxJO/src/user-photos/cash_codes.json';

    // Check if user-photos path exists and is writable
    if (file_exists($userPhotosPath) && is_writable(dirname($userPhotosPath))) {
        return $userPhotosPath;
    }

    // Create in user-photos if it doesn't exist
    if (!file_exists($userPhotosPath)) {
        $dir = dirname($userPhotosPath);
        if (is_dir($dir) && is_writable($dir)) {
            file_put_contents($userPhotosPath, '{}');
            chmod($userPhotosPath, 0666);
            return $userPhotosPath;
        }
    }

    // Fallback paths
    $paths = [
        __DIR__ . '/../user-photos/cash_codes.json',
        __DIR__ . '/../data/cash_codes.json',
        '/var/www/html/FotoboxJO/src/data/cash_codes.json',
        $_SERVER['DOCUMENT_ROOT'] . '/FotoboxJO/src/data/cash_codes.json'
    ];

    foreach ($paths as $path) {
        if (file_exists($path) && is_writable(dirname($path))) {
            return $path;
        }
    }

    // Default fallback to user-photos
    return $userPhotosPath;
}

// Function to generate new cash code
function generateCashCode()
{
    // Generate random 5 digit code (avoid 54321 and 11000 to prevent conflicts)
    do {
        $code = str_pad(mt_rand(10000, 99999), 5, '0', STR_PAD_LEFT);
    } while ($code === '54321' || $code === '11000');

    return $code;
}

// Handle AJAX requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if it's JSON request
    $contentType = $_SERVER["CONTENT_TYPE"] ?? '';

    if (strpos($contentType, 'application/json') !== false) {
        $input = json_decode(file_get_contents('php://input'), true);
        $action = $input['action'] ?? '';
    } else {
        $action = $_POST['action'] ?? '';
    }

    if ($action === 'generate_code') {
        $newCode = generateCashCode();

        // Save to cash codes file using the unified path function
        $codesFile = getDataFilePath();

        // Create directory if it doesn't exist
        $dataDir = dirname($codesFile);
        if (!file_exists($dataDir)) {
            if (!mkdir($dataDir, 0777, true)) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Gagal membuat direktori data']);
                exit();
            }
        }

        // Ensure directory is writable
        if (!is_writable($dataDir)) {
            chmod($dataDir, 0777);
        }

        // Load existing codes
        $codes = [];
        if (file_exists($codesFile)) {
            $content = file_get_contents($codesFile);
            if ($content !== false) {
                $codes = json_decode($content, true) ?? [];
            }
        }

        // Add new code
        $codes[$newCode] = [
            'generated_at' => date('Y-m-d H:i:s'),
            'used' => false,
            'used_at' => null
        ];

        // Prepare JSON content
        $jsonContent = json_encode($codes, JSON_PRETTY_PRINT);

        // Try to write file
        $writeResult = file_put_contents($codesFile, $jsonContent);

        if ($writeResult !== false) {
            // Ensure file is readable/writable
            chmod($codesFile, 0666);

            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'code' => $newCode, 'message' => 'Kode berhasil di-generate']);
        } else {
            // Get more detailed error
            $error = error_get_last();
            $errorMsg = $error ? $error['message'] : 'Unknown file write error';

            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Gagal menyimpan kode: ' . $errorMsg]);
        }
        exit();
    }

    if ($action === 'get_codes') {
        $codesFile = getDataFilePath();
        $codes = [];
        if (file_exists($codesFile)) {
            $codes = json_decode(file_get_contents($codesFile), true) ?? [];
        }

        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'codes' => $codes]);
        exit();
    }
}

// Load current codes for display
$codesFile = getDataFilePath();
$codes = [];
if (file_exists($codesFile)) {
    $codes = json_decode(file_get_contents($codesFile), true) ?? [];
}

// Sort codes by generation time (newest first)
if (!empty($codes)) {
    uasort($codes, function ($a, $b) {
        return strtotime($b['generated_at']) - strtotime($a['generated_at']);
    });
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Panel - Cash Codes</title>

    <?php PWAHelper::addPWAHeaders(); ?>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="home-styles.css" />
    <style>
        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            min-height: 100vh;
            padding: 1rem;
            max-width: 100%;
            margin: 0 auto;
        }

        .admin-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            padding: 1.5rem;
            backdrop-filter: blur(10px);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.3);
            width: 100%;
            margin-bottom: 1rem;
        }

        .admin-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .admin-title {
            color: #333;
            font-size: 1.5rem;
            font-weight: 700;
            margin: 0;
            text-align: center;
            flex: 1;
        }

        .logout-btn {
            background: #ff4757;
            color: white;
            border: none;
            border-radius: 8px;
            padding: 8px 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 0.9rem;
        }

        .logout-btn:hover {
            background: #ff3742;
            transform: translateY(-2px);
        }

        .generate-section {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            text-align: center;
        }

        .generate-section h3 {
            font-size: 1.2rem;
            margin-bottom: 0.5rem;
            color: #333;
        }

        .generate-section p {
            font-size: 0.9rem;
            color: #666;
            margin-bottom: 1rem;
        }

        .generate-btn {
            background: var(--pink-primary);
            color: white;
            border: none;
            border-radius: 12px;
            padding: 12px 24px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 100%;
            max-width: 250px;
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

        .current-code {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 12px;
            padding: 1.5rem;
            margin: 1rem 0;
            text-align: center;
        }

        .current-code h4 {
            font-size: 1rem;
            margin-bottom: 0.5rem;
        }

        .code-display {
            font-size: 2rem;
            font-weight: 700;
            letter-spacing: 0.3rem;
            margin: 0.5rem 0;
            font-family: 'Courier New', monospace;
        }

        .current-code p {
            font-size: 0.9rem;
            margin: 0.5rem 0 0 0;
        }

        .codes-history h3 {
            font-size: 1.2rem;
            margin-bottom: 1rem;
            color: #333;
        }

        .codes-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
            font-size: 0.85rem;
        }

        .codes-table th,
        .codes-table td {
            padding: 8px 6px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .codes-table th {
            background: var(--pink-primary);
            color: white;
            font-weight: 600;
            font-size: 0.8rem;
        }

        .codes-table tr:hover {
            background: #f5f5f5;
        }

        .status-badge {
            padding: 3px 8px;
            border-radius: 15px;
            font-size: 0.7rem;
            font-weight: 600;
            display: inline-block;
        }

        .status-unused {
            background: #d4edda;
            color: #155724;
        }

        .status-used {
            background: #f8d7da;
            color: #721c24;
        }

        .refresh-btn {
            background: #28a745;
            color: white;
            border: none;
            border-radius: 6px;
            padding: 6px 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 0.8rem;
        }

        .refresh-btn:hover {
            background: #218838;
        }

        .loading {
            opacity: 0.6;
            pointer-events: none;
        }

        .no-codes {
            text-align: center;
            color: #666;
            font-style: italic;
            padding: 2rem;
            font-size: 0.9rem;
        }

        /* Mobile specific adjustments */
        @media (max-width: 768px) {
            .container {
                padding: 0.5rem;
            }

            .admin-card {
                padding: 1rem;
                border-radius: 12px;
            }

            .admin-title {
                font-size: 1.3rem;
            }

            .admin-header {
                flex-direction: column;
                text-align: center;
            }

            .codes-table {
                font-size: 0.75rem;
            }

            .codes-table th,
            .codes-table td {
                padding: 6px 4px;
            }

            .code-display {
                font-size: 1.8rem;
                letter-spacing: 0.2rem;
            }

            .generate-btn {
                padding: 10px 20px;
                font-size: 0.9rem;
            }
        }

        @media (max-width: 480px) {
            .admin-title {
                font-size: 1.2rem;
            }

            .codes-table {
                font-size: 0.7rem;
            }

            .codes-table th,
            .codes-table td {
                padding: 4px 2px;
            }

            .code-display {
                font-size: 1.5rem;
                letter-spacing: 0.1rem;
            }

            .current-code {
                padding: 1rem;
            }
        }
    </style>
</head>

<body>
    <div class="gradientBgCanvas"></div>

    <div class="container">
        <div class="admin-card">
            <div class="admin-header">
                <h1 class="admin-title">Admin Panel - Cash Codes</h1>
                <div>
                    <button class="refresh-btn" onclick="refreshCodes()">Refresh</button>
                    <button class="logout-btn" onclick="logout()">Logout</button>
                </div>
            </div>

            <!-- Generate New Code Section -->
            <div class="generate-section">
                <h3>Generate New Cash Code</h3>
                <p>Klik tombol di bawah untuk membuat kode cash baru untuk customer</p>
                <button class="generate-btn" onclick="generateNewCode()" id="generateBtn">
                    Generate New Code
                </button>

                <div id="currentCodeDisplay" style="display: none;">
                    <div class="current-code">
                        <h4>Kode Cash Terbaru:</h4>
                        <div class="code-display" id="latestCode">-</div>
                        <p>Berikan kode ini kepada customer untuk akses cash payment</p>
                    </div>
                </div>
            </div>

            <!-- Codes History -->
            <div class="codes-history">
                <h3>Riwayat Kode Cash</h3>
                <table class="codes-table">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Dibuat</th>
                            <th>Status</th>
                            <th>Digunakan</th>
                        </tr>
                    </thead>
                    <tbody id="codesTableBody">
                        <?php foreach ($codes as $code => $data): ?>
                            <tr>
                                <td><strong><?php echo $code; ?></strong></td>
                                <td><?php echo date('d/m/Y H:i', strtotime($data['generated_at'])); ?></td>
                                <td>
                                    <span class="status-badge <?php echo $data['used'] ? 'status-used' : 'status-unused'; ?>">
                                        <?php echo $data['used'] ? 'Terpakai' : 'Belum Terpakai'; ?>
                                    </span>
                                </td>
                                <td><?php echo $data['used_at'] ? date('d/m/Y H:i', strtotime($data['used_at'])) : '-'; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        let refreshInterval;

        // Load page
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Admin page loaded');
            // Start auto refresh for codes
            setInterval(() => {
                loadCodesViaAjax();
            }, 30000); // Refresh every 30 seconds
        });

        async function generateNewCode() {
            const btn = document.getElementById('generateBtn');
            const currentCodeDisplay = document.getElementById('currentCodeDisplay');
            const latestCode = document.getElementById('latestCode');

            btn.disabled = true;
            btn.textContent = 'Generating...';
            btn.classList.add('loading');

            try {
                const response = await fetch('admin.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        action: 'generate_code'
                    })
                });

                const result = await response.json();
                console.log('Generate result:', result);

                if (result.success) {
                    // Show the new code
                    latestCode.textContent = result.code;
                    currentCodeDisplay.style.display = 'block';

                    // Hide after 60 seconds
                    setTimeout(() => {
                        currentCodeDisplay.style.display = 'none';
                    }, 60000);

                    // Refresh the table after 2 seconds
                    setTimeout(() => {
                        refreshCodes();
                    }, 2000);

                    console.log('Code generated successfully:', result.code);
                } else {
                    console.error('Failed to generate code:', result.message);
                    alert('Gagal generate kode: ' + (result.message || 'Unknown error'));
                }
            } catch (error) {
                console.error('Error generating code:', error);
                alert('Terjadi kesalahan saat generate kode. Silakan coba lagi.');
            } finally {
                btn.disabled = false;
                btn.textContent = 'Generate New Code';
                btn.classList.remove('loading');
            }
        }

        async function loadCodesViaAjax() {
            try {
                const response = await fetch('admin.php', {
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
                    updateCodesTable(result.codes);
                }
            } catch (error) {
                console.error('Error loading codes:', error);
            }
        }

        function updateCodesTable(codes) {
            const tbody = document.getElementById('codesTableBody');

            if (!codes || Object.keys(codes).length === 0) {
                tbody.innerHTML = '<tr><td colspan="4" class="no-codes">Belum ada kode yang di-generate</td></tr>';
                return;
            }

            // Sort codes by generation time (newest first)
            const sortedCodes = Object.entries(codes).sort((a, b) => {
                return new Date(b[1].generated_at) - new Date(a[1].generated_at);
            });

            let html = '';
            sortedCodes.forEach(([code, data]) => {
                const statusClass = data.used ? 'status-used' : 'status-unused';
                const statusText = data.used ? 'Terpakai' : 'Belum Terpakai';
                const usedAt = data.used_at ? new Date(data.used_at).toLocaleDateString('id-ID', {
                    day: '2-digit',
                    month: '2-digit',
                    year: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                }) : '-';

                html += `
                    <tr>
                        <td><strong style="font-family: 'Courier New', monospace;">${code}</strong></td>
                        <td>${new Date(data.generated_at).toLocaleDateString('id-ID', {
                            day: '2-digit',
                            month: '2-digit',
                            year: 'numeric',
                            hour: '2-digit',
                            minute: '2-digit'
                        })}</td>
                        <td><span class="status-badge ${statusClass}">${statusText}</span></td>
                        <td>${usedAt}</td>
                    </tr>
                `;
            });

            tbody.innerHTML = html;
        }

        async function refreshCodes() {
            // Reload the page to get fresh data
            window.location.reload();
        }

        function logout() {
            if (confirm('Apakah Anda yakin ingin logout?')) {
                window.location.href = 'admin-login.php?logout=1';
            }
        }
    </script>

    <?php PWAHelper::addPWAScript(); ?>
</body>

</html>