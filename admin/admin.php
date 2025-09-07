<?php
session_start();

// Check admin access
if (!isset($_SESSION['admin_access']) || $_SESSION['admin_access'] !== true) {
    header('Location: admin-login.php');
    exit();
}

require_once 'config/database.php';

$currentSection = $_GET['section'] ?? 'dashboard';
$message = $_GET['msg'] ?? '';

try {
    $frames = Database::getAllFrames();
    $stickers = Database::getAllStickers();

    // Get frames by layout for layout-specific management
    $framesByLayout = [];
    for ($i = 1; $i <= 6; $i++) {
        $framesByLayout[$i] = Database::getFramesByLayout($i);
    }
} catch (Exception $e) {
    $message = "Database error: " . $e->getMessage();
    $frames = [];
    $stickers = [];
    $framesByLayout = [];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>GoFotobox Admin</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #FFE4EA 0%, #E28585 100%);
            min-height: 100vh;
        }

        .admin-container {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            width: 250px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 20px;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
        }

        .logo {
            text-align: center;
            margin-bottom: 30px;
            padding: 20px 0;
            border-bottom: 2px solid #E28585;
        }

        .logo h2 {
            color: #E28585;
            font-weight: 700;
        }

        .nav-menu {
            list-style: none;
        }

        .nav-menu li {
            margin-bottom: 10px;
        }

        .nav-menu a {
            display: flex;
            align-items: center;
            padding: 15px;
            text-decoration: none;
            color: #666;
            border-radius: 10px;
            transition: all 0.3s ease;
        }

        .nav-menu a:hover,
        .nav-menu a.active {
            background: #E28585;
            color: white;
            transform: translateX(5px);
        }

        .nav-menu i {
            margin-right: 10px;
            width: 20px;
        }

        .logout-btn {
            margin-top: auto;
            padding: 15px;
            background: #ff4757;
            color: white;
            text-decoration: none;
            border-radius: 10px;
            text-align: center;
            transition: all 0.3s ease;
        }

        .logout-btn:hover {
            background: #ff3838;
            transform: translateY(-2px);
        }

        /* Main Content */
        .main-content {
            flex: 1;
            padding: 30px;
            overflow-y: auto;
        }

        .content-header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 20px;
            border-radius: 15px;
            margin-bottom: 30px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .content-header h1 {
            color: #E28585;
            font-size: 2rem;
            font-weight: 700;
        }

        .content-section {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        /* Upload Section */
        .upload-section {
            margin-bottom: 30px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 10px;
            border: 2px dashed #E28585;
        }

        .upload-form {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            align-items: center;
        }

        .form-group {
            flex: 1;
            min-width: 200px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
            color: #666;
        }

        .form-group input {
            width: 100%;
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
            transition: border-color 0.3s ease;
        }

        .form-group input:focus {
            outline: none;
            border-color: #E28585;
        }

        .upload-btn {
            background: #E28585;
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .upload-btn:hover {
            background: #d67575;
            transform: translateY(-2px);
        }

        /* Gallery Grid */
        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .gallery-item {
            background: white;
            border-radius: 10px;
            padding: 15px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .gallery-item:hover {
            transform: translateY(-5px);
        }

        .gallery-item img {
            width: 100%;
            height: 120px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 10px;
        }

        .gallery-item h4 {
            color: #333;
            margin-bottom: 5px;
            font-size: 14px;
        }

        .gallery-item .meta {
            font-size: 12px;
            color: #666;
            margin-bottom: 10px;
        }

        .delete-btn {
            background: #ff4757;
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 5px;
            font-size: 12px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .delete-btn:hover {
            background: #ff3838;
        }

        .message {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 8px;
            font-weight: 500;
        }

        .message.success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .message.error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .stat-card i {
            font-size: 2rem;
            color: #E28585;
            margin-bottom: 10px;
        }

        .stat-card h3 {
            font-size: 2rem;
            color: #333;
            margin-bottom: 5px;
        }

        .stat-card p {
            color: #666;
            font-size: 14px;
        }

        /* Tabs */
        .tabs,
        .layout-tabs {
            display: flex;
            margin-bottom: 20px;
            background: #f8f9fa;
            padding: 5px;
            border-radius: 10px;
        }

        .layout-tabs {
            flex-wrap: wrap;
            gap: 5px;
        }

        .tab-btn {
            flex: 1;
            padding: 12px;
            background: none;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .layout-tabs .tab-btn {
            flex: 0 1 auto;
            min-width: 120px;
        }

        .tab-btn.active {
            background: #E28585;
            color: white;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        /* Color Indicator */
        .color-indicator {
            position: absolute;
            top: 10px;
            right: 10px;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            border: 2px solid white;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }

        .gallery-item {
            position: relative;
        }

        /* Image Modal */
        .image-modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.9);
            animation: fadeIn 0.3s;
        }

        .modal-content {
            position: relative;
            margin: auto;
            padding: 0;
            width: 70%;
            max-width: 500px;
            top: 50%;
            transform: translateY(-50%);
        }

        .modal-image {
            width: 100%;
            height: auto;
            max-height: 60vh;
            object-fit: contain;
            border-radius: 10px;
            box-shadow: 0 4px 25px rgba(0, 0, 0, 0.5);
        }

        .modal-info {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 20px;
            margin-top: 10px;
            border-radius: 10px;
            text-align: center;
        }

        .modal-info h3 {
            color: #E28585;
            margin-bottom: 10px;
        }

        .modal-info p {
            color: #666;
            margin: 5px 0;
        }

        .close-modal {
            position: absolute;
            top: 15px;
            right: 25px;
            color: #fff;
            font-size: 35px;
            font-weight: bold;
            cursor: pointer;
            z-index: 1001;
            background: rgba(0, 0, 0, 0.7);
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.3s ease;
        }

        .close-modal:hover {
            background: rgba(226, 133, 133, 0.8);
        }

        .modal-nav-btn {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(0, 0, 0, 0.7);
            color: white;
            border: none;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            font-size: 20px;
            cursor: pointer;
            transition: all 0.3s ease;
            z-index: 1001;
            opacity: 0;
            visibility: hidden;
        }

        .modal-content:hover .modal-nav-btn {
            opacity: 1;
            visibility: visible;
        }

        .modal-prev {
            left: 20px;
        }

        .modal-next {
            right: 20px;
        }

        .modal-nav-btn:hover {
            background: rgba(226, 133, 133, 0.9);
            transform: translateY(-50%) scale(1.1);
        }

        .modal-nav-btn:disabled {
            opacity: 0.3;
            cursor: not-allowed;
            background: rgba(0, 0, 0, 0.3);
        }

        .modal-counter {
            margin-top: 15px;
            font-size: 14px;
            color: #666;
            font-weight: bold;
        }

        .gallery-item img {
            width: 100%;
            height: 120px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 10px;
            cursor: pointer;
            transition: transform 0.3s ease;
        }

        .gallery-item img:hover {
            transform: scale(1.05);
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @media (max-width: 768px) {
            .admin-container {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
                padding: 15px;
            }

            .nav-menu {
                display: flex;
                overflow-x: auto;
                gap: 10px;
            }

            .nav-menu li {
                margin: 0;
                white-space: nowrap;
            }

            .main-content {
                padding: 15px;
            }
        }

        /* Multiple Upload Styles */
        .form-group.full-width {
            flex: 100%;
            width: 100%;
        }

        .form-group select {
            width: 100%;
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
            transition: border-color 0.3s ease;
            background-color: white;
        }

        .form-group select:focus {
            outline: none;
            border-color: #E28585;
        }

        .form-group small {
            color: #666;
            font-size: 12px;
            margin-top: 5px;
            display: block;
        }

        .upload-progress {
            margin-top: 20px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 8px;
            border: 2px solid #E28585;
        }

        .progress-bar {
            width: 100%;
            height: 20px;
            background: #e9ecef;
            border-radius: 10px;
            overflow: hidden;
            margin-bottom: 10px;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #E28585, #d67575);
            width: 0%;
            transition: width 0.3s ease;
            border-radius: 10px;
        }

        .progress-text {
            text-align: center;
            font-weight: 600;
            color: #E28585;
        }

        .upload-results {
            margin-top: 20px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 8px;
        }

        .upload-results h4 {
            color: #E28585;
            margin-bottom: 15px;
            font-size: 16px;
        }

        .result-item {
            padding: 10px;
            margin-bottom: 8px;
            border-radius: 5px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .result-item.success {
            background: #d4edda;
            color: #155724;
            border-left: 4px solid #28a745;
        }

        .result-item.error {
            background: #f8d7da;
            color: #721c24;
            border-left: 4px solid #dc3545;
        }

        .result-item i {
            font-size: 16px;
        }

        .result-summary {
            margin-bottom: 15px;
            padding: 15px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .summary-stats {
            display: flex;
            gap: 20px;
            align-items: center;
        }

        .summary-stat {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .summary-stat.success {
            color: #28a745;
        }

        .summary-stat.error {
            color: #dc3545;
        }
    </style>
</head>

<body>
    <div class="admin-container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="logo">
                <h2>GoFotobox</h2>
                <p>Admin Panel</p>
            </div>

            <ul class="nav-menu">
                <li>
                    <a href="?section=dashboard" class="<?= $currentSection === 'dashboard' ? 'active' : '' ?>">
                        <i class="fas fa-tachometer-alt"></i>
                        Dashboard
                    </a>
                </li>
                <li>
                    <a href="?section=assets" class="<?= $currentSection === 'assets' ? 'active' : '' ?>">
                        <i class="fas fa-images"></i>
                        Manage Assets
                    </a>
                </li>
                <li>
                    <a href="?section=layout-frames" class="<?= $currentSection === 'layout-frames' ? 'active' : '' ?>">
                        <i class="fas fa-layer-group"></i>
                        Layout Frames
                    </a>
                </li>
                <li>
                    <a href="?section=layout-stickers" class="<?= $currentSection === 'layout-stickers' ? 'active' : '' ?>">
                        <i class="fas fa-smile"></i>
                        Layout Stickers
                    </a>
                </li>
                <li>
                    <a href="?section=frame-sticker-combos" class="<?= $currentSection === 'frame-sticker-combos' ? 'active' : '' ?>">
                        <i class="fas fa-object-group"></i>
                        Frame & Sticker Combos
                    </a>
                </li>
                <li>
                    <a href="../src/pages/admin-new.php" target="_blank" style="border-left: 3px solid #4CAF50;">
                        <i class="fas fa-money-bill-wave"></i>
                        Cash Code Generator
                    </a>
                </li>
            </ul>

            <div style="margin-top: auto; padding-top: 20px;">
                <a href="admin-login.php?logout=1" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i>
                    Logout
                </a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <div class="content-header">
                <h1>
                    <?php if ($currentSection === 'dashboard'): ?>
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    <?php elseif ($currentSection === 'assets'): ?>
                        <i class="fas fa-images"></i> Manage Assets
                    <?php elseif ($currentSection === 'layout-frames'): ?>
                        <i class="fas fa-layer-group"></i> Layout Frames
                    <?php elseif ($currentSection === 'layout-stickers'): ?>
                        <i class="fas fa-smile"></i> Layout Stickers
                    <?php elseif ($currentSection === 'frame-sticker-combos'): ?>
                        <i class="fas fa-object-group"></i> Frame & Sticker Combos
                    <?php endif; ?>
                </h1>
            </div>

            <?php if ($message): ?>
                <div class="message <?= strpos($message, 'success') !== false ? 'success' : 'error' ?>">
                    <?= htmlspecialchars($message) ?>
                </div>
            <?php endif; ?>

            <div class="content-section">
                <?php if ($currentSection === 'dashboard'): ?>
                    <!-- Dashboard Content -->
                    <div class="stats-grid">
                        <div class="stat-card">
                            <i class="fas fa-frame"></i>
                            <h3><?= count($frames) ?></h3>
                            <p>Total Frames</p>
                        </div>
                        <div class="stat-card">
                            <i class="fas fa-star"></i>
                            <h3><?= count($stickers) ?></h3>
                            <p>Total Stickers</p>
                        </div>
                        <div class="stat-card">
                            <i class="fas fa-database"></i>
                            <h3>MySQL</h3>
                            <p>Database Connected</p>
                        </div>
                        <div class="stat-card">
                            <i class="fas fa-server"></i>
                            <h3>Active</h3>
                            <p>System Status</p>
                        </div>
                    </div>

                    <h3>Recent Assets</h3>
                    <div class="gallery-grid">
                        <?php
                        $recentAssets = array_merge(
                            array_slice($frames, 0, 3),
                            array_slice($stickers, 0, 3)
                        );
                        foreach ($recentAssets as $asset):
                        ?>
                            <div class="gallery-item">
                                <img
                                    src="<?= htmlspecialchars($asset['file_path']) ?>"
                                    alt="<?= htmlspecialchars($asset['nama']) ?>"
                                    onclick="openImageModal('<?= htmlspecialchars($asset['file_path']) ?>', '<?= htmlspecialchars($asset['nama']) ?>', '<?= isset($asset['id']) && $asset['id'] <= count($frames) ? 'Frame' : 'Sticker' ?>', '<?= htmlspecialchars($asset['filename']) ?>', '<?= round($asset['file_size'] / 1024, 1) ?>', '<?= htmlspecialchars($asset['warna'] ?? '') ?>')">
                                <h4><?= htmlspecialchars($asset['nama']) ?></h4>
                                <div class="meta">
                                    <?= isset($asset['id']) && $asset['id'] <= count($frames) ? 'Frame' : 'Sticker' ?> •
                                    <?= date('M d, Y', strtotime($asset['created_at'])) ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                <?php elseif ($currentSection === 'assets'): ?>
                    <!-- Assets Management -->
                    <div class="tab-buttons">
                        <button class="tab-btn active" onclick="switchTab('frames')">
                            <i class="fas fa-frame"></i> Frames
                        </button>
                        <button class="tab-btn" onclick="switchTab('stickers')">
                            <i class="fas fa-star"></i> Stickers
                        </button>
                        <button class="tab-btn" onclick="switchTab('multiple')">
                            <i class="fas fa-cloud-upload-alt"></i> Multiple Upload
                        </button>
                    </div> <!-- Frames Tab -->
                    <div id="frames-tab" class="tab-content active">
                        <div class="upload-section">
                            <h3><i class="fas fa-upload"></i> Upload New Frame</h3>
                            <form action="api/upload-frame.php" method="POST" enctype="multipart/form-data" class="upload-form">
                                <div class="form-group">
                                    <label>Frame Name</label>
                                    <input type="text" name="nama" placeholder="e.g., Classic Frame" required>
                                </div>
                                <div class="form-group">
                                    <label>Frame Color</label>
                                    <input type="color" name="warna" value="#FFFFFF" required>
                                </div>
                                <div class="form-group">
                                    <label>Frame Image</label>
                                    <input type="file" name="frame_image" accept="image/*" required>
                                </div>
                                <button type="submit" class="upload-btn">
                                    <i class="fas fa-upload"></i> Upload Frame
                                </button>
                            </form>
                        </div>

                        <h3>Existing Frames (<?= count($frames) ?>)</h3>
                        <div class="gallery-grid">
                            <?php foreach ($frames as $frame): ?>
                                <div class="gallery-item">
                                    <img
                                        src="<?= htmlspecialchars($frame['file_path']) ?>"
                                        alt="<?= htmlspecialchars($frame['nama']) ?>"
                                        onclick="openImageModal('<?= htmlspecialchars($frame['file_path']) ?>', '<?= htmlspecialchars($frame['nama']) ?>', 'Frame', '<?= htmlspecialchars($frame['filename']) ?>', '<?= round($frame['file_size'] / 1024, 1) ?>', '<?= htmlspecialchars($frame['warna'] ?? '') ?>')">
                                    <h4><?= htmlspecialchars($frame['nama']) ?></h4>
                                    <div class="meta">
                                        <?= htmlspecialchars($frame['filename']) ?> •
                                        <?= round($frame['file_size'] / 1024, 1) ?>KB
                                        <?php if (!empty($frame['warna'])): ?>
                                            • <span style="display: inline-block; width: 20px; height: 20px; background: <?= htmlspecialchars($frame['warna']) ?>; border: 1px solid #ccc; vertical-align: middle; margin-left: 5px;"></span>
                                        <?php endif; ?>
                                    </div>
                                    <button onclick="deleteAsset('frame', <?= $frame['id'] ?>)" class="delete-btn">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Stickers Tab -->
                    <div id="stickers-tab" class="tab-content">
                        <div class="upload-section">
                            <h3><i class="fas fa-upload"></i> Upload New Sticker</h3>
                            <form action="api/upload-sticker.php" method="POST" enctype="multipart/form-data" class="upload-form">
                                <div class="form-group">
                                    <label>Sticker Name</label>
                                    <input type="text" name="nama" placeholder="e.g., Heart Sticker" required>
                                </div>
                                <div class="form-group">
                                    <label>Sticker Image</label>
                                    <input type="file" name="sticker_image" accept="image/*" required>
                                </div>
                                <button type="submit" class="upload-btn">
                                    <i class="fas fa-upload"></i> Upload Sticker
                                </button>
                            </form>
                        </div>

                        <h3>Existing Stickers (<?= count($stickers) ?>)</h3>
                        <div class="gallery-grid">
                            <?php foreach ($stickers as $sticker): ?>
                                <div class="gallery-item">
                                    <img
                                        src="<?= htmlspecialchars($sticker['file_path']) ?>"
                                        alt="<?= htmlspecialchars($sticker['nama']) ?>"
                                        onclick="openImageModal('<?= htmlspecialchars($sticker['file_path']) ?>', '<?= htmlspecialchars($sticker['nama']) ?>', 'Sticker', '<?= htmlspecialchars($sticker['filename']) ?>', '<?= round($sticker['file_size'] / 1024, 1) ?>', '')">
                                    <h4><?= htmlspecialchars($sticker['nama']) ?></h4>
                                    <div class="meta">
                                        <?= htmlspecialchars($sticker['filename']) ?> •
                                        <?= round($sticker['file_size'] / 1024, 1) ?>KB
                                    </div>
                                    <button onclick="deleteAsset('sticker', <?= $sticker['id'] ?>)" class="delete-btn">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Multiple Upload Tab -->
                    <div id="multiple-tab" class="tab-content">
                        <div class="upload-section">
                            <h3><i class="fas fa-cloud-upload-alt"></i> Multiple Frame Upload</h3>
                            <form id="multiple-frames-form" enctype="multipart/form-data" class="upload-form">
                                <div class="form-group">
                                    <label>Name Prefix</label>
                                    <input type="text" name="name_prefix" placeholder="e.g., Wedding Frame" value="Frame" required>
                                </div>
                                <div class="form-group">
                                    <label>Default Color</label>
                                    <input type="color" name="default_color" value="#FFFFFF" required>
                                </div>
                                <div class="form-group full-width">
                                    <label>Select Multiple Frame Images</label>
                                    <input type="file" name="frame_images[]" accept="image/*" multiple required>
                                    <small>Hold Ctrl/Cmd to select multiple files. Max 5MB per file.</small>
                                </div>
                                <button type="submit" class="upload-btn">
                                    <i class="fas fa-cloud-upload-alt"></i> Upload Multiple Frames
                                </button>
                            </form>

                            <div id="multiple-frames-progress" class="upload-progress" style="display: none;">
                                <div class="progress-bar">
                                    <div class="progress-fill"></div>
                                </div>
                                <div class="progress-text">Uploading...</div>
                            </div>

                            <div id="multiple-frames-results" class="upload-results" style="display: none;">
                                <h4>Upload Results:</h4>
                                <div class="results-content"></div>
                            </div>
                        </div>

                        <div class="upload-section">
                            <h3><i class="fas fa-cloud-upload-alt"></i> Multiple Sticker Upload</h3>
                            <form id="multiple-stickers-form" enctype="multipart/form-data" class="upload-form">
                                <div class="form-group">
                                    <label>Name Prefix</label>
                                    <input type="text" name="name_prefix" placeholder="e.g., Love Sticker" value="Sticker" required>
                                </div>
                                <div class="form-group">
                                    <label>Target Layout (Optional)</label>
                                    <select name="layout_id">
                                        <option value="">Universal (All Layouts)</option>
                                        <option value="1">Layout 1</option>
                                        <option value="2">Layout 2</option>
                                        <option value="3">Layout 3</option>
                                        <option value="4">Layout 4</option>
                                        <option value="5">Layout 5</option>
                                        <option value="6">Layout 6</option>
                                    </select>
                                </div>
                                <div class="form-group full-width">
                                    <label>Select Multiple Sticker Images</label>
                                    <input type="file" name="sticker_images[]" accept="image/*" multiple required>
                                    <small>Hold Ctrl/Cmd to select multiple files. Max 5MB per file.</small>
                                </div>
                                <button type="submit" class="upload-btn">
                                    <i class="fas fa-cloud-upload-alt"></i> Upload Multiple Stickers
                                </button>
                            </form>

                            <div id="multiple-stickers-progress" class="upload-progress" style="display: none;">
                                <div class="progress-bar">
                                    <div class="progress-fill"></div>
                                </div>
                                <div class="progress-text">Uploading...</div>
                            </div>

                            <div id="multiple-stickers-results" class="upload-results" style="display: none;">
                                <h4>Upload Results:</h4>
                                <div class="results-content"></div>
                            </div>
                        </div>
                    </div>
                <?php elseif ($currentSection === 'layout-frames'): ?>
                    <!-- Layout-Specific Frames Management -->
                    <div class="layout-tabs">
                        <?php for ($i = 1; $i <= 6; $i++): ?>
                            <button class="tab-btn <?= $i === 1 ? 'active' : '' ?>" onclick="switchLayoutTab(<?= $i ?>)">
                                <i class="fas fa-th-large"></i> Layout <?= $i ?>
                            </button>
                        <?php endfor; ?>
                    </div>

                    <?php for ($layout = 1; $layout <= 6; $layout++): ?>
                        <div id="layout-<?= $layout ?>-tab" class="tab-content <?= $layout === 1 ? 'active' : '' ?>">
                            <div class="upload-section">
                                <h3><i class="fas fa-upload"></i> Upload Frame for Layout <?= $layout ?></h3>
                                <form action="api/upload-frame-layout.php" method="POST" enctype="multipart/form-data" class="upload-form">
                                    <input type="hidden" name="layout_id" value="<?= $layout ?>">
                                    <div class="form-group">
                                        <label>Frame Name</label>
                                        <input type="text" name="nama" placeholder="e.g., Layout <?= $layout ?> Frame" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Frame Color</label>
                                        <input type="color" name="warna" value="#FFFFFF" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Frame Image</label>
                                        <input type="file" name="frame" accept="image/*" required>
                                    </div>
                                    <button type="submit" class="upload-btn">
                                        <i class="fas fa-upload"></i> Upload to Layout <?= $layout ?>
                                    </button>
                                </form>
                            </div>

                            <div class="upload-section">
                                <h3><i class="fas fa-cloud-upload-alt"></i> Multiple Upload for Layout <?= $layout ?></h3>
                                <form id="multiple-layout-<?= $layout ?>-frames-form" enctype="multipart/form-data" class="upload-form">
                                    <input type="hidden" name="layout_id" value="<?= $layout ?>">
                                    <div class="form-group">
                                        <label>Name Prefix</label>
                                        <input type="text" name="name_prefix" placeholder="e.g., Layout <?= $layout ?> Frame" value="Layout <?= $layout ?> Frame" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Default Color</label>
                                        <input type="color" name="default_color" value="#FFFFFF" required>
                                    </div>
                                    <div class="form-group full-width">
                                        <label>Select Multiple Frame Images</label>
                                        <input type="file" name="frame_images[]" accept="image/*" multiple required>
                                        <small>Hold Ctrl/Cmd to select multiple files. Max 5MB per file.</small>
                                    </div>
                                    <button type="submit" class="upload-btn">
                                        <i class="fas fa-cloud-upload-alt"></i> Upload Multiple to Layout <?= $layout ?>
                                    </button>
                                </form>

                                <div id="multiple-layout-<?= $layout ?>-frames-progress" class="upload-progress" style="display: none;">
                                    <div class="progress-bar">
                                        <div class="progress-fill"></div>
                                    </div>
                                    <div class="progress-text">Uploading...</div>
                                </div>

                                <div id="multiple-layout-<?= $layout ?>-frames-results" class="upload-results" style="display: none;">
                                    <h4>Upload Results:</h4>
                                    <div class="results-content"></div>
                                </div>
                            </div>

                            <h3>Layout <?= $layout ?> Frames (<?= count($framesByLayout[$layout] ?? []) ?>)</h3>
                            <div class="gallery-grid">
                                <?php foreach ($framesByLayout[$layout] ?? [] as $frame): ?>
                                    <div class="gallery-item">
                                        <img
                                            src="<?= htmlspecialchars($frame['file_path']) ?>"
                                            alt="<?= htmlspecialchars($frame['nama']) ?>"
                                            onclick="openImageModal('<?= htmlspecialchars($frame['file_path']) ?>', '<?= htmlspecialchars($frame['nama']) ?>', 'Layout <?= $layout ?> Frame', '<?= htmlspecialchars($frame['filename']) ?>', '<?= round($frame['file_size'] / 1024, 1) ?>', '<?= htmlspecialchars($frame['warna']) ?>')">
                                        <h4><?= htmlspecialchars($frame['nama']) ?></h4>
                                        <div class="meta">
                                            Layout <?= $layout ?> • <?= date('M d, Y', strtotime($frame['created_at'])) ?>
                                        </div>
                                        <div class="color-indicator" style="background-color: <?= htmlspecialchars($frame['warna']) ?>"></div>
                                        <button onclick="deleteLayoutFrame(<?= $layout ?>, <?= $frame['id'] ?>)" class="delete-btn">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endfor; ?>

                <?php elseif ($currentSection === 'layout-stickers'): ?>
                    <!-- Layout-Specific Stickers Management -->
                    <div class="layout-tabs">
                        <?php for ($i = 1; $i <= 6; $i++): ?>
                            <button class="tab-btn <?= $i === 1 ? 'active' : '' ?>" onclick="switchLayoutTab(<?= $i ?>)">
                                <i class="fas fa-th-large"></i> Layout <?= $i ?>
                            </button>
                        <?php endfor; ?>
                    </div>

                    <?php for ($layout = 1; $layout <= 6; $layout++): ?>
                        <div id="layout-<?= $layout ?>-tab" class="tab-content <?= $layout === 1 ? 'active' : '' ?>">
                            <div class="upload-section">
                                <h3><i class="fas fa-upload"></i> Upload Sticker for Layout <?= $layout ?></h3>
                                <form action="api/upload-layout-sticker.php" method="POST" enctype="multipart/form-data" class="upload-form">
                                    <input type="hidden" name="layout_id" value="<?= $layout ?>">
                                    <div class="form-group">
                                        <label>Sticker Name</label>
                                        <input type="text" name="nama" placeholder="e.g., Layout <?= $layout ?> Sticker" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Sticker Image</label>
                                        <input type="file" name="sticker_image" accept="image/*" required>
                                    </div>
                                    <button type="submit" class="upload-btn">
                                        <i class="fas fa-upload"></i> Upload to Layout <?= $layout ?>
                                    </button>
                                </form>
                            </div>

                            <h3>Layout <?= $layout ?> Stickers</h3>
                            <div class="gallery-grid">
                                <?php
                                try {
                                    $layoutStickers = Database::getStickersByLayout($layout);
                                    foreach ($layoutStickers as $sticker):
                                        if ($sticker['layout_id'] == $layout): // Only show layout-specific stickers
                                ?>
                                            <div class="gallery-item">
                                                <img
                                                    src="<?= htmlspecialchars($sticker['file_path']) ?>"
                                                    alt="<?= htmlspecialchars($sticker['nama']) ?>"
                                                    onclick="openImageModal('<?= htmlspecialchars($sticker['file_path']) ?>', '<?= htmlspecialchars($sticker['nama']) ?>', 'Layout <?= $layout ?> Sticker', '<?= htmlspecialchars($sticker['filename']) ?>', '<?= round($sticker['file_size'] / 1024, 1) ?>', '')">
                                                <h4><?= htmlspecialchars($sticker['nama']) ?></h4>
                                                <div class="meta">
                                                    Layout <?= $layout ?> • <?= date('M d, Y', strtotime($sticker['created_at'])) ?>
                                                </div>
                                                <button onclick="deleteLayoutSticker(<?= $sticker['id'] ?>)" class="delete-btn">
                                                    <i class="fas fa-trash"></i> Delete
                                                </button>
                                            </div>
                                <?php
                                        endif;
                                    endforeach;
                                } catch (Exception $e) {
                                    echo "<p>Error loading stickers: " . $e->getMessage() . "</p>";
                                }
                                ?>
                            </div>
                        </div>
                    <?php endfor; ?>

                <?php elseif ($currentSection === 'frame-sticker-combos'): ?>
                    <!-- Frame & Sticker Combos Management -->
                    <div class="layout-tabs">
                        <?php for ($i = 1; $i <= 6; $i++): ?>
                            <button class="tab-btn <?= $i === 1 ? 'active' : '' ?>" onclick="switchLayoutTab(<?= $i ?>)">
                                <i class="fas fa-th-large"></i> Layout <?= $i ?>
                            </button>
                        <?php endfor; ?>
                    </div>

                    <?php for ($layout = 1; $layout <= 6; $layout++): ?>
                        <div id="layout-<?= $layout ?>-tab" class="tab-content <?= $layout === 1 ? 'active' : '' ?>">
                            <div class="upload-section">
                                <h3><i class="fas fa-upload"></i> Upload Frame & Sticker Combo for Layout <?= $layout ?></h3>
                                <form action="api/upload-frame-sticker-combo.php" method="POST" enctype="multipart/form-data" class="upload-form">
                                    <input type="hidden" name="layout_id" value="<?= $layout ?>">
                                    <div class="form-group">
                                        <label>Combo Name</label>
                                        <input type="text" name="nama" placeholder="e.g., Layout <?= $layout ?> Frame & Sticker Combo" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Combo Image (Frame + Sticker)</label>
                                        <input type="file" name="combo_image" accept="image/*" required>
                                    </div>
                                    <button type="submit" class="upload-btn">
                                        <i class="fas fa-upload"></i> Upload to Layout <?= $layout ?>
                                    </button>
                                </form>
                            </div>

                            <div class="upload-section">
                                <h3><i class="fas fa-cloud-upload-alt"></i> Multiple Upload Frame & Sticker Combos for Layout <?= $layout ?></h3>
                                <form id="multiple-combo-layout-<?= $layout ?>-form" enctype="multipart/form-data" class="upload-form">
                                    <input type="hidden" name="layout_id" value="<?= $layout ?>">
                                    <div class="form-group">
                                        <label>Name Prefix</label>
                                        <input type="text" name="name_prefix" placeholder="e.g., Wedding Combo" value="Layout <?= $layout ?> Combo" required>
                                    </div>
                                    <div class="form-group full-width">
                                        <label>Select Multiple Combo Images (Frame + Sticker)</label>
                                        <input type="file" name="combo_images[]" accept="image/*" multiple required>
                                        <small>Hold Ctrl/Cmd to select multiple files. Max 5MB per file. These should be complete frame+sticker combinations.</small>
                                    </div>
                                    <button type="submit" class="upload-btn">
                                        <i class="fas fa-cloud-upload-alt"></i> Upload Multiple Combos to Layout <?= $layout ?>
                                    </button>
                                </form>

                                <div id="multiple-combo-layout-<?= $layout ?>-progress" class="upload-progress" style="display: none;">
                                    <div class="progress-bar">
                                        <div class="progress-fill"></div>
                                    </div>
                                    <div class="progress-text">Uploading...</div>
                                </div>

                                <div id="multiple-combo-layout-<?= $layout ?>-results" class="upload-results" style="display: none;">
                                    <h4>Upload Results:</h4>
                                    <div class="results-content"></div>
                                </div>
                            </div>

                            <h3>Layout <?= $layout ?> Frame & Sticker Combos</h3>
                            <div class="gallery-grid">
                                <?php
                                try {
                                    $layoutCombos = Database::getFrameStickerComboByLayout($layout);
                                    foreach ($layoutCombos as $combo):
                                ?>
                                        <div class="gallery-item">
                                            <img
                                                src="<?= htmlspecialchars($combo['file_path']) ?>"
                                                alt="<?= htmlspecialchars($combo['nama']) ?>"
                                                onclick="openImageModal('<?= htmlspecialchars($combo['file_path']) ?>', '<?= htmlspecialchars($combo['nama']) ?>', 'Layout <?= $layout ?> Frame & Sticker Combo', '<?= htmlspecialchars($combo['filename']) ?>', '<?= round($combo['file_size'] / 1024, 1) ?>', '')">
                                            <h4><?= htmlspecialchars($combo['nama']) ?></h4>
                                            <div class="meta">
                                                Layout <?= $layout ?> • <?= date('M d, Y', strtotime($combo['created_at'])) ?>
                                            </div>
                                            <button onclick="deleteFrameStickerCombo(<?= $combo['id'] ?>)" class="delete-btn">
                                                <i class="fas fa-trash"></i> Delete
                                            </button>
                                        </div>
                                <?php
                                    endforeach;
                                } catch (Exception $e) {
                                    echo "<p>Error loading combos: " . $e->getMessage() . "</p>";
                                }
                                ?>
                            </div>
                        </div>
                    <?php endfor; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Image Modal -->
    <div id="imageModal" class="image-modal">
        <span class="close-modal" onclick="closeImageModal()">&times;</span>
        <div class="modal-content">
            <button class="modal-nav-btn modal-prev" onclick="navigateModal(-1)">
                <i class="fas fa-chevron-left"></i>
            </button>
            <img id="modalImage" class="modal-image" src="" alt="">
            <button class="modal-nav-btn modal-next" onclick="navigateModal(1)">
                <i class="fas fa-chevron-right"></i>
            </button>
            <div class="modal-info">
                <h3 id="modalTitle"></h3>
                <p id="modalType"></p>
                <p id="modalFilename"></p>
                <p id="modalFilesize"></p>
                <p id="modalColor" style="display: none;"></p>
                <div class="modal-counter">
                    <span id="modalCounter">1 / 10</span>
                </div>
            </div>
        </div>
    </div>

    <script>
        function switchTab(tabName) {
            // Hide all tabs
            document.querySelectorAll('.tab-content').forEach(tab => {
                tab.classList.remove('active');
            });

            // Remove active class from all buttons
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('active');
            });

            // Show selected tab
            document.getElementById(tabName + '-tab').classList.add('active');

            // Add active class to clicked button
            event.target.classList.add('active');
        }

        function switchLayoutTab(layoutId) {
            // Hide all layout tabs
            document.querySelectorAll('[id^="layout-"][id$="-tab"]').forEach(tab => {
                tab.classList.remove('active');
            });

            // Remove active class from all layout buttons
            document.querySelectorAll('.layout-tabs .tab-btn').forEach(btn => {
                btn.classList.remove('active');
            });

            // Show selected layout tab
            document.getElementById('layout-' + layoutId + '-tab').classList.add('active');

            // Add active class to clicked button
            event.target.classList.add('active');
        }

        function deleteAsset(type, id) {
            if (confirm('Are you sure you want to delete this ' + type + '?')) {
                window.location.href = `api/delete-${type}.php?id=${id}`;
            }
        }

        function deleteLayoutFrame(layoutId, frameId) {
            if (confirm('Are you sure you want to delete this frame from Layout ' + layoutId + '?')) {
                // Create form to send POST request
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = 'api/delete-frame-layout.php';

                const layoutInput = document.createElement('input');
                layoutInput.type = 'hidden';
                layoutInput.name = 'layout_id';
                layoutInput.value = layoutId;

                const frameInput = document.createElement('input');
                frameInput.type = 'hidden';
                frameInput.name = 'frame_id';
                frameInput.value = frameId;

                form.appendChild(layoutInput);
                form.appendChild(frameInput);
                document.body.appendChild(form);
                form.submit();
            }
        }

        function deleteLayoutSticker(stickerId) {
            if (confirm('Are you sure you want to delete this layout sticker?')) {
                window.location.href = `api/delete-sticker.php?id=${stickerId}`;
            }
        }

        function deleteFrameStickerCombo(comboId) {
            if (confirm('Are you sure you want to delete this frame & sticker combo?')) {
                window.location.href = `api/delete-frame-sticker-combo.php?id=${comboId}`;
            }
        }

        // Image Modal Functions
        let currentGallery = [];
        let currentImageIndex = 0;

        function openImageModal(imageSrc, title, type, filename, filesize, color) {
            // Get all gallery items from the current active tab/section
            const activeTab = document.querySelector('.tab-content.active') || document.querySelector('[id*="tab"].active') || document.body;
            const galleryItems = activeTab.querySelectorAll('.gallery-item img');

            // Build gallery array
            currentGallery = Array.from(galleryItems).map(img => ({
                src: img.src,
                title: img.alt,
                type: img.closest('.gallery-item').querySelector('h4').textContent,
                filename: img.src.split('/').pop(),
                filesize: getFilesizeFromOnclick(img.getAttribute('onclick')),
                color: getColorFromOnclick(img.getAttribute('onclick'))
            }));

            // Find current image index
            currentImageIndex = currentGallery.findIndex(item => item.src === imageSrc);
            if (currentImageIndex === -1) currentImageIndex = 0;

            // Show modal
            displayCurrentImage();
        }

        function displayCurrentImage() {
            if (currentGallery.length === 0) return;

            const currentItem = currentGallery[currentImageIndex];
            const modal = document.getElementById('imageModal');
            const modalImage = document.getElementById('modalImage');
            const modalTitle = document.getElementById('modalTitle');
            const modalType = document.getElementById('modalType');
            const modalFilename = document.getElementById('modalFilename');
            const modalFilesize = document.getElementById('modalFilesize');
            const modalColor = document.getElementById('modalColor');
            const modalCounter = document.getElementById('modalCounter');

            modal.style.display = 'block';
            modalImage.src = currentItem.src;
            modalImage.alt = currentItem.title;
            modalTitle.textContent = currentItem.title;
            modalType.textContent = `Type: ${currentItem.type}`;
            modalFilename.textContent = `File: ${currentItem.filename}`;
            modalFilesize.textContent = `Size: ${currentItem.filesize} KB`;
            modalCounter.textContent = `${currentImageIndex + 1} / ${currentGallery.length}`;

            if (currentItem.color && currentItem.color !== '') {
                modalColor.innerHTML = `Color: <span style="display: inline-block; width: 20px; height: 20px; background: ${currentItem.color}; border: 1px solid #ccc; vertical-align: middle; margin-left: 5px; border-radius: 3px;"></span> ${currentItem.color}`;
                modalColor.style.display = 'block';
            } else {
                modalColor.style.display = 'none';
            }

            // Update navigation buttons
            const prevBtn = document.querySelector('.modal-prev');
            const nextBtn = document.querySelector('.modal-next');

            if (prevBtn) prevBtn.disabled = (currentImageIndex === 0);
            if (nextBtn) nextBtn.disabled = (currentImageIndex === currentGallery.length - 1);

            // Prevent body scrolling when modal is open
            document.body.style.overflow = 'hidden';
        }

        function navigateModal(direction) {
            if (currentGallery.length === 0) return;

            const newIndex = currentImageIndex + direction;

            if (newIndex >= 0 && newIndex < currentGallery.length) {
                currentImageIndex = newIndex;
                displayCurrentImage();
            }
        }

        function getFilesizeFromOnclick(onclickStr) {
            if (!onclickStr) return '0';
            const match = onclickStr.match(/'([^']*)',\s*'[^']*'\s*\)$/);
            if (match && match[1]) {
                const parts = onclickStr.split("'");
                for (let i = 0; i < parts.length; i++) {
                    if (parts[i].includes('.') && !isNaN(parseFloat(parts[i]))) {
                        return parts[i];
                    }
                }
            }
            return '0';
        }

        function getColorFromOnclick(onclickStr) {
            if (!onclickStr) return '';
            const parts = onclickStr.split("'");
            return parts[parts.length - 2] || '';
        }

        function closeImageModal() {
            const modal = document.getElementById('imageModal');
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
            currentGallery = [];
            currentImageIndex = 0;
        }

        // Close modal when clicking outside the image
        document.getElementById('imageModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeImageModal();
            }
        });

        // Enhanced keyboard navigation
        document.addEventListener('keydown', function(e) {
            const modal = document.getElementById('imageModal');
            if (modal.style.display === 'block') {
                switch (e.key) {
                    case 'Escape':
                        closeImageModal();
                        break;
                    case 'ArrowLeft':
                        e.preventDefault();
                        navigateModal(-1);
                        break;
                    case 'ArrowRight':
                        e.preventDefault();
                        navigateModal(1);
                        break;
                }
            }
        });

        // Auto-hide messages after 5 seconds
        setTimeout(() => {
            const message = document.querySelector('.message');
            if (message) {
                message.style.opacity = '0';
                message.style.transition = 'opacity 0.5s ease';
                setTimeout(() => message.remove(), 500);
            }
        }, 5000);

        // Enhanced upload with progress feedback
        document.addEventListener('DOMContentLoaded', function() {
            const uploadForms = document.querySelectorAll('.upload-form');
            uploadForms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    const submitBtn = form.querySelector('.upload-btn');
                    const originalText = submitBtn.innerHTML;

                    // Show upload progress
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Uploading...';
                    submitBtn.disabled = true;

                    // Reset button after upload (fallback)
                    setTimeout(() => {
                        if (submitBtn.disabled) {
                            submitBtn.innerHTML = originalText;
                            submitBtn.disabled = false;
                        }
                    }, 10000);
                });
            });

            // Multiple Frames Upload Handler
            const multipleFramesForm = document.getElementById('multiple-frames-form');
            if (multipleFramesForm) {
                multipleFramesForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    handleMultipleUpload('frames', this);
                });
            }

            // Multiple Stickers Upload Handler
            const multipleStickersForm = document.getElementById('multiple-stickers-form');
            if (multipleStickersForm) {
                multipleStickersForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    handleMultipleUpload('stickers', this);
                });
            }

            // Multiple Layout Frames Upload Handlers
            for (let i = 1; i <= 6; i++) {
                const multipleLayoutFramesForm = document.getElementById(`multiple-layout-${i}-frames-form`);
                if (multipleLayoutFramesForm) {
                    multipleLayoutFramesForm.addEventListener('submit', function(e) {
                        e.preventDefault();
                        handleMultipleLayoutUpload(i, this);
                    });
                }

                // Multiple Frame-Sticker-Combos Upload Handlers
                const multipleComboForm = document.getElementById(`multiple-combo-layout-${i}-form`);
                if (multipleComboForm) {
                    multipleComboForm.addEventListener('submit', function(e) {
                        e.preventDefault();
                        handleMultipleComboUpload(i, this);
                    });
                }
            }
        });

        function handleMultipleUpload(type, form) {
            const formData = new FormData(form);
            const submitBtn = form.querySelector('.upload-btn');
            const originalText = submitBtn.innerHTML;
            const progressDiv = document.getElementById(`multiple-${type}-progress`);
            const resultsDiv = document.getElementById(`multiple-${type}-results`);
            const progressFill = progressDiv.querySelector('.progress-fill');
            const progressText = progressDiv.querySelector('.progress-text');

            // Reset UI
            resultsDiv.style.display = 'none';
            progressDiv.style.display = 'block';
            progressFill.style.width = '0%';
            progressText.textContent = 'Preparing upload...';

            // Disable submit button
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Uploading...';
            submitBtn.disabled = true;

            // Simulate progress animation
            let progress = 0;
            const progressInterval = setInterval(() => {
                progress += Math.random() * 15;
                if (progress > 90) progress = 90;
                progressFill.style.width = progress + '%';
                progressText.textContent = `Uploading... ${Math.round(progress)}%`;
            }, 200);

            // Make the upload request
            const apiEndpoint = type === 'frames' ? 'api/upload-multiple-frames.php' : 'api/upload-multiple-stickers.php';

            fetch(apiEndpoint, {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    clearInterval(progressInterval);
                    progressFill.style.width = '100%';
                    progressText.textContent = 'Upload complete!';

                    setTimeout(() => {
                        progressDiv.style.display = 'none';
                        displayUploadResults(type, data);

                        // Reset form if successful
                        if (data.success) {
                            form.reset();
                            // Reload page after a delay to show new uploads
                            setTimeout(() => {
                                window.location.reload();
                            }, 3000);
                        }
                    }, 1000);
                })
                .catch(error => {
                    clearInterval(progressInterval);
                    progressDiv.style.display = 'none';

                    const errorData = {
                        success: false,
                        message: 'Network error occurred',
                        uploaded: 0,
                        failed: 0,
                        details: []
                    };
                    displayUploadResults(type, errorData);
                    console.error('Upload error:', error);
                })
                .finally(() => {
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                });
        }

        function displayUploadResults(type, data) {
            const resultsDiv = document.getElementById(`multiple-${type}-results`);
            const resultsContent = resultsDiv.querySelector('.results-content');

            let html = '';

            // Summary
            html += '<div class="result-summary">';
            html += '<h5>Upload Summary</h5>';
            html += '<div class="summary-stats">';
            html += `<div class="summary-stat success"><i class="fas fa-check-circle"></i> ${data.uploaded} successful</div>`;
            html += `<div class="summary-stat error"><i class="fas fa-times-circle"></i> ${data.failed} failed</div>`;
            html += '</div>';
            html += `<p><strong>Message:</strong> ${data.message}</p>`;
            html += '</div>';

            // Individual file results
            if (data.details && data.details.length > 0) {
                html += '<div class="result-details">';
                data.details.forEach(detail => {
                    const cssClass = detail.success ? 'success' : 'error';
                    const icon = detail.success ? 'fa-check-circle' : 'fa-times-circle';

                    html += `<div class="result-item ${cssClass}">`;
                    html += `<i class="fas ${icon}"></i>`;
                    html += '<div>';
                    html += `<strong>${detail.original_name}</strong><br>`;
                    html += `${detail.message}`;
                    if (detail.success && detail.frame_name) {
                        html += `<br><small>Saved as: ${detail.frame_name}</small>`;
                    }
                    if (detail.success && detail.sticker_name) {
                        html += `<br><small>Saved as: ${detail.sticker_name}</small>`;
                        if (detail.layout_id) {
                            html += ` (Layout ${detail.layout_id})`;
                        } else {
                            html += ' (Universal)';
                        }
                    }
                    html += '</div>';
                    html += '</div>';
                });
                html += '</div>';
            }

            resultsContent.innerHTML = html;
            resultsDiv.style.display = 'block';
        }

        function handleMultipleLayoutUpload(layoutId, form) {
            const formData = new FormData(form);
            const submitBtn = form.querySelector('.upload-btn');
            const originalText = submitBtn.innerHTML;
            const progressDiv = document.getElementById(`multiple-layout-${layoutId}-frames-progress`);
            const resultsDiv = document.getElementById(`multiple-layout-${layoutId}-frames-results`);
            const progressFill = progressDiv.querySelector('.progress-fill');
            const progressText = progressDiv.querySelector('.progress-text');

            // Reset UI
            resultsDiv.style.display = 'none';
            progressDiv.style.display = 'block';
            progressFill.style.width = '0%';
            progressText.textContent = 'Preparing upload...';

            // Disable submit button
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Uploading...';
            submitBtn.disabled = true;

            // Simulate progress animation
            let progress = 0;
            const progressInterval = setInterval(() => {
                progress += Math.random() * 15;
                if (progress > 90) progress = 90;
                progressFill.style.width = progress + '%';
                progressText.textContent = `Uploading to Layout ${layoutId}... ${Math.round(progress)}%`;
            }, 200);

            // Make the upload request
            fetch('api/upload-multiple-layout-frames.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    clearInterval(progressInterval);
                    progressFill.style.width = '100%';
                    progressText.textContent = 'Upload complete!';

                    setTimeout(() => {
                        progressDiv.style.display = 'none';
                        displayLayoutUploadResults(layoutId, data);

                        // Reset form if successful
                        if (data.success) {
                            form.reset();
                            form.querySelector('input[name="name_prefix"]').value = `Layout ${layoutId} Frame`;
                            form.querySelector('input[name="default_color"]').value = '#FFFFFF';
                            // Reload page after a delay to show new uploads
                            setTimeout(() => {
                                window.location.reload();
                            }, 3000);
                        }
                    }, 1000);
                })
                .catch(error => {
                    clearInterval(progressInterval);
                    progressDiv.style.display = 'none';

                    const errorData = {
                        success: false,
                        message: 'Network error occurred',
                        uploaded: 0,
                        failed: 0,
                        details: []
                    };
                    displayLayoutUploadResults(layoutId, errorData);
                    console.error('Upload error:', error);
                })
                .finally(() => {
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                });
        }

        function displayLayoutUploadResults(layoutId, data) {
            const resultsDiv = document.getElementById(`multiple-layout-${layoutId}-frames-results`);
            const resultsContent = resultsDiv.querySelector('.results-content');

            let html = '';

            // Summary
            html += '<div class="result-summary">';
            html += `<h5>Layout ${layoutId} Upload Summary</h5>`;
            html += '<div class="summary-stats">';
            html += `<div class="summary-stat success"><i class="fas fa-check-circle"></i> ${data.uploaded} successful</div>`;
            html += `<div class="summary-stat error"><i class="fas fa-times-circle"></i> ${data.failed} failed</div>`;
            html += '</div>';
            html += `<p><strong>Message:</strong> ${data.message}</p>`;
            html += '</div>';

            // Individual file results
            if (data.details && data.details.length > 0) {
                html += '<div class="result-details">';
                data.details.forEach(detail => {
                    const cssClass = detail.success ? 'success' : 'error';
                    const icon = detail.success ? 'fa-check-circle' : 'fa-times-circle';

                    html += `<div class="result-item ${cssClass}">`;
                    html += `<i class="fas ${icon}"></i>`;
                    html += '<div>';
                    html += `<strong>${detail.original_name}</strong><br>`;
                    html += `${detail.message}`;
                    if (detail.success && detail.frame_name) {
                        html += `<br><small>Saved as: ${detail.frame_name}</small>`;
                    }
                    html += '</div>';
                    html += '</div>';
                });
                html += '</div>';
            }

            resultsContent.innerHTML = html;
            resultsDiv.style.display = 'block';
        }

        function handleMultipleComboUpload(layoutId, form) {
            const formData = new FormData(form);

            // Ensure layout_id is explicitly set
            formData.set('layout_id', layoutId);

            const submitBtn = form.querySelector('.upload-btn');
            const originalText = submitBtn.innerHTML;
            const progressDiv = document.getElementById(`multiple-combo-layout-${layoutId}-progress`);
            const resultsDiv = document.getElementById(`multiple-combo-layout-${layoutId}-results`);
            const progressFill = progressDiv.querySelector('.progress-fill');
            const progressText = progressDiv.querySelector('.progress-text');

            // Reset UI
            resultsDiv.style.display = 'none';
            progressDiv.style.display = 'block';
            progressFill.style.width = '0%';
            progressText.textContent = 'Preparing upload...';

            // Disable submit button
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Uploading...';
            submitBtn.disabled = true;

            // Simulate progress animation
            let progress = 0;
            const progressInterval = setInterval(() => {
                progress += Math.random() * 15;
                if (progress > 90) progress = 90;
                progressFill.style.width = progress + '%';
                progressText.textContent = `Uploading combos to Layout ${layoutId}... ${Math.round(progress)}%`;
            }, 200);

            // Make the upload request
            fetch('api/upload-multiple-frame-sticker-combos.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    clearInterval(progressInterval);
                    progressFill.style.width = '100%';
                    progressText.textContent = 'Upload complete!';

                    setTimeout(() => {
                        progressDiv.style.display = 'none';
                        displayComboUploadResults(layoutId, data);

                        // Reset form if successful
                        if (data.success) {
                            form.reset();
                            form.querySelector('input[name="name_prefix"]').value = `Layout ${layoutId} Combo`;
                            // Reload page after a delay to show new uploads
                            setTimeout(() => {
                                window.location.reload();
                            }, 3000);
                        }
                    }, 1000);
                })
                .catch(error => {
                    clearInterval(progressInterval);
                    progressDiv.style.display = 'none';

                    console.error('Upload error details:', error);

                    const errorData = {
                        success: false,
                        message: 'Network error: ' + error.message,
                        uploaded: 0,
                        failed: 0,
                        details: []
                    };
                    displayComboUploadResults(layoutId, errorData);
                })
                .finally(() => {
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                });
        }

        function displayComboUploadResults(layoutId, data) {
            const resultsDiv = document.getElementById(`multiple-combo-layout-${layoutId}-results`);
            const resultsContent = resultsDiv.querySelector('.results-content');

            let html = '';

            // Summary
            html += '<div class="result-summary">';
            html += `<h5>Layout ${layoutId} Frame-Sticker-Combos Upload Summary</h5>`;
            html += '<div class="summary-stats">';
            html += `<div class="summary-stat success"><i class="fas fa-check-circle"></i> ${data.uploaded} successful</div>`;
            html += `<div class="summary-stat error"><i class="fas fa-times-circle"></i> ${data.failed} failed</div>`;
            html += '</div>';
            html += `<p><strong>Message:</strong> ${data.message}</p>`;
            html += '</div>';

            // Individual file results
            if (data.details && data.details.length > 0) {
                html += '<div class="result-details">';
                data.details.forEach(detail => {
                    const cssClass = detail.success ? 'success' : 'error';
                    const icon = detail.success ? 'fa-check-circle' : 'fa-times-circle';

                    html += `<div class="result-item ${cssClass}">`;
                    html += `<i class="fas ${icon}"></i>`;
                    html += '<div>';
                    html += `<strong>${detail.original_name}</strong><br>`;
                    html += `${detail.message}`;
                    if (detail.success && detail.combo_name) {
                        html += `<br><small>Saved as: ${detail.combo_name}</small>`;
                    }
                    html += '</div>';
                    html += '</div>';
                });
                html += '</div>';
            }

            resultsContent.innerHTML = html;
            resultsDiv.style.display = 'block';
        }
    </script>
</body>

</html>