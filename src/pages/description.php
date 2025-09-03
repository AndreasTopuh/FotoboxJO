<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Panduan GoFotobox</title>
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#E28585">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-title" content="GoFotobox">
    <link rel="apple-touch-icon" href="/src/assets/icons/logo-gofotobox-new-180.png">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="../../static/css/main.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../../static/css/responsive.css?v=<?php echo time(); ?>">
    <style>
        * {
            overflow: hidden;
        }

        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            color: #333;
        }

        .main-wrapper {
            max-width: 1140px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            padding: 0 1rem;
            min-height: calc(70vh - 100px);
        }

        .header {
            color: white;
            text-align: center;
            margin: 4rem 0 4rem;
        }

        .header img {
            width: 50px;
            border-radius: 10px;
            margin-bottom: 0.5rem;
        }

        .header h1 {
            font-size: 1.9rem;
            margin: 0;
        }

        .header p {
            color: white;
            font-size: 0.85rem;
            margin: 0.2rem 0;
        }

        .card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            padding: 1rem;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            min-height: 500px;
            /* Set a minimum height to fill more vertical space */
            display: flex;
            flex-direction: column;
            justify-content: normal;
            /* Distribute content to use available space */
        }

        .step {
            display: flex;
            align-items: flex-start;
            margin-bottom: 2rem;
            padding: 0.6rem;
            background: linear-gradient(135deg, rgba(226, 133, 133, 0.08), rgba(255, 255, 255, 0.1));
            border-radius: 8px;
            border-left: 3px solid #E28585;
            transition: all 0.3s ease;
        }

        .step:hover {
            transform: translateX(3px);
            box-shadow: 0 3px 10px rgba(226, 133, 133, 0.2);
        }

        .step-number {
            background: linear-gradient(135deg, #E28585, #FF6B9D);
            color: #fff;
            width: 28px;
            height: 28px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            font-weight: bold;
            margin-right: 0.8rem;
            box-shadow: 0 2px 5px rgba(226, 133, 133, 0.3);
            flex-shrink: 0;
        }

        .step-text h3 {
            margin: 0 0 0.2rem 0;
            font-size: 0.95rem;
            font-weight: 600;
            color: #333;
        }

        .step-text p {
            margin: 0;
            font-size: 0.75rem;
            color: #666;
            line-height: 1.3;
        }

        .layout-preview-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 0.8rem;
            margin-bottom: 1rem;
            flex-grow: 1;
            /* Allow the grid to grow and fill space */
        }

        .layout-box {
            background: linear-gradient(135deg, rgba(226, 133, 133, 0.08), rgba(255, 255, 255, 0.1));
            border-radius: 8px;
            text-align: center;
            padding: 0.6rem;
            transition: all 0.3s ease;
            border: 1px solid transparent;
        }

        .layout-box:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(226, 133, 133, 0.2);
            border-color: rgba(226, 133, 133, 0.3);
        }

        .layout-box img {
            width: 100%;
            max-width: 100px;
            margin: 30px 5px 30px;
            border-radius: 6px;
            box-shadow: 0 1px 5px rgba(0, 0, 0, 0.1);
        }

        .layout-box h4 {
            margin: 0;
            font-size: 0.75rem;
            color: #333;
            font-weight: 600;
        }

        .layout-section-title {
            text-align: center;
            margin-bottom: 0.8rem;
            color: #333;
            font-size: 1.05rem;
            font-weight: 700;
            position: relative;
        }

        .layout-section-title::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 50%;
            transform: translateX(-50%);
            width: 40px;
            height: 2px;
            background: linear-gradient(135deg, #E28585, #FF6B9D);
            border-radius: 1px;
        }

        .timer-info {
            background: linear-gradient(135deg, rgba(226, 133, 133, 0.1), rgba(255, 255, 255, 0.1));
            padding: 0.6rem;
            border-radius: 8px;
            border-left: 3px solid #E28585;
            margin-top: 0.6rem;
        }

        .timer-info p {
            margin: 0;
            font-size: 0.75rem;
            text-align: center;
            color: #666;
            line-height: 1.3;
        }

        .footer-buttons {
            display: flex;
            gap: 0.8rem;
            justify-content: space-between;
            margin: 0.8rem auto;
            max-width: 1140px;
            padding: 0 1rem;
        }

        .btn {
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 600;
            text-decoration: none;
            text-align: center;
            font-size: 0.85rem;
            flex: 1;
        }

        .btn-primary {
            background: linear-gradient(135deg, #E28585, #FF6B9D);
            color: white;
        }

        .btn-outline-new {
            background: #fff;
            border: 1px solid var(--pink-secondary, #E28585);
            color: var(--pink-primary, #E28585);
            padding: 8px 16px;
            border-radius: 6px;
            cursor: pointer;
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
            font-size: 0.85rem;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .btn-outline-new:hover {
            background: var(--pink-primary, #E28585);
            border: 1px solid #fff;
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 3px 10px rgba(233, 30, 99, 0.3);
        }

        @media (max-width: 768px) {
            .main-wrapper {
                grid-template-columns: 1fr;
                padding: 0.5rem;
                min-height: auto;
                /* Reset for mobile to avoid excessive height */
            }

            .header h1 {
                font-size: 1.6rem;
            }

            .header p {
                font-size: 0.75rem;
            }

            .card {
                padding: 0.8rem;
                min-height: auto;
                /* Allow cards to adjust naturally on mobile */
            }

            .footer-buttons {
                flex-direction: column;
                gap: 0.5rem;
            }
        }
    </style>
</head>

<body>
    <div class="gradientBgCanvas"></div>

    <div class="header">
        <h1>Cara Menggunakan GoFotobox</h1>
        <p>Ikuti panduan langkah demi langkah dan pilih layout terbaikmu</p>
    </div>

    <div class="main-wrapper">
        <div class="card">
            <div class="step">
                <div class="step-number">1</div>
                <div class="step-text">
                    <h3>Pilih Metode Pembayaran</h3>
                    <p>QRIS atau Virtual Account BCA</p>
                </div>
            </div>
            <div class="step">
                <div class="step-number">2</div>
                <div class="step-text">
                    <h3>Lakukan Pembayaran</h3>
                    <p>Akan memulaikan sesi foto selama 10 menit terhitung dari pembayaran</p>
                </div>
            </div>
            <div class="step">
                <div class="step-number">3</div>
                <div class="step-text">
                    <h3>Pilih Layout</h3>
                    <p>6 pilihan layout foto</p>
                </div>
            </div>
            <div class="step">
                <div class="step-number">4</div>
                <div class="step-text">
                    <h3>Ambil Foto</h3>
                    <p>Jumlah sesuai layout</p>
                </div>
            </div>
            <div class="step">
                <div class="step-number">5</div>
                <div class="step-text">
                    <h3>Edit Foto</h3>
                    <p>Frame dan sticker</p>
                </div>
            </div>
            <div class="step">
                <div class="step-number">6</div>
                <div class="step-text">
                    <h3>Kirim ke Email atau Cetak</h3>
                    <p>Dapat mengirimkan foto tersebut ke email atau bisa langsung di print saja.</p>
                </div>
            </div>
        </div>

        <div class="card">
            <h3 class="layout-section-title">Contoh Layout Foto</h3>
            <div class="layout-preview-grid">
                <div class="layout-box">
                    <img src="../assets/layouts/layout1.png" alt="Layout 1">
                    <h4>Layout 1 (2 Foto)</h4>
                </div>
                <div class="layout-box">
                    <img src="../assets/layouts/layout2.png" alt="Layout 2">
                    <h4>Layout 2 (4 Foto)</h4>
                </div>
                <div class="layout-box">
                    <img src="../assets/layouts/layout3.png" alt="Layout 3">
                    <h4>Layout 3 (6 Foto)</h4>
                </div>
                <div class="layout-box">
                    <img src="../assets/layouts/layout4.png" alt="Layout 4">
                    <h4>Layout 4 (4 Foto)</h4>
                </div>
                <div class="layout-box">
                    <img src="../assets/layouts/layout5.png" alt="Layout 5">
                    <h4>Layout 5 (6 Foto)</h4>
                </div>
                <div class="layout-box">
                    <img src="../assets/layouts/layout6.png" alt="Layout 6">
                    <h4>Layout 6 (8 Foto)</h4>
                </div>
            </div>
            <div class="timer-info">
                <p>
                    ⏰ Waktu total: 10 menit setelah pembayaran<br>
                    📸 Setiap foto boleh diatur countdownnya
                </p>
            </div>
        </div>
    </div>

    <div class="footer-buttons">
        <a href="/" class="btn btn-outline-new">← Kembali</a>
        <a href="selectpayment.php" class="btn btn-outline-new">Lanjutkan</a>
    </div>
</body>

</html>