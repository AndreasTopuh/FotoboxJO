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
} catch (Exception $e) {
    $message = "Database error: " . $e->getMessage();
    $frames = [];
    $stickers = [];
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
        .tabs {
            display: flex;
            margin-bottom: 20px;
            background: #f8f9fa;
            padding: 5px;
            border-radius: 10px;
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
                    <div class="tabs">
                        <button class="tab-btn active" onclick="switchTab('frames')">
                            <i class="fas fa-frame"></i> Frames
                        </button>
                        <button class="tab-btn" onclick="switchTab('stickers')">
                            <i class="fas fa-star"></i> Stickers
                        </button>
                    </div>

                    <!-- Frames Tab -->
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
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Image Modal -->
    <div id="imageModal" class="image-modal">
        <span class="close-modal" onclick="closeImageModal()">&times;</span>
        <div class="modal-content">
            <img id="modalImage" class="modal-image" src="" alt="">
            <div class="modal-info">
                <h3 id="modalTitle"></h3>
                <p id="modalType"></p>
                <p id="modalFilename"></p>
                <p id="modalFilesize"></p>
                <p id="modalColor" style="display: none;"></p>
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

        function deleteAsset(type, id) {
            if (confirm('Are you sure you want to delete this ' + type + '?')) {
                window.location.href = `api/delete-${type}.php?id=${id}`;
            }
        }

        // Image Modal Functions
        function openImageModal(imageSrc, title, type, filename, filesize, color) {
            const modal = document.getElementById('imageModal');
            const modalImage = document.getElementById('modalImage');
            const modalTitle = document.getElementById('modalTitle');
            const modalType = document.getElementById('modalType');
            const modalFilename = document.getElementById('modalFilename');
            const modalFilesize = document.getElementById('modalFilesize');
            const modalColor = document.getElementById('modalColor');

            modal.style.display = 'block';
            modalImage.src = imageSrc;
            modalImage.alt = title;
            modalTitle.textContent = title;
            modalType.textContent = `Type: ${type}`;
            modalFilename.textContent = `File: ${filename}`;
            modalFilesize.textContent = `Size: ${filesize} KB`;

            if (color && color !== '') {
                modalColor.innerHTML = `Color: <span style="display: inline-block; width: 20px; height: 20px; background: ${color}; border: 1px solid #ccc; vertical-align: middle; margin-left: 5px; border-radius: 3px;"></span> ${color}`;
                modalColor.style.display = 'block';
            } else {
                modalColor.style.display = 'none';
            }

            // Prevent body scrolling when modal is open
            document.body.style.overflow = 'hidden';
        }

        function closeImageModal() {
            const modal = document.getElementById('imageModal');
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
        }

        // Close modal when clicking outside the image
        document.getElementById('imageModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeImageModal();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeImageModal();
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
        });
    </script>
</body>

</html>