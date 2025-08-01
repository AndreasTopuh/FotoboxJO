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
    <link rel="stylesheet" href="home-styles.css">
    <style>
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            color: #333;
        }

        .main-wrapper {
            max-width: 1140px;
            margin: auto;
            /* padding: 1.5rem; */
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
        }

        .header {
            text-align: center;
            /* margin-bottom: 2rem; */
            margin-top: 1.5rem;
        }

        .header img {
            width: 70px;
            border-radius: 14px;
            margin-bottom: 1rem;
        }

        .header h1 {
            font-size: 2.2rem;
            margin: 0;
        }

        .header p {
            color: #666;
            font-size: 0.9rem;
        }

        .card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .step {
            display: flex;
            align-items: flex-start;
            margin-bottom: 1.2rem;
            padding: 1rem;
            background: linear-gradient(135deg, rgba(226, 133, 133, 0.08), rgba(255, 255, 255, 0.1));
            border-radius: 12px;
            border-left: 4px solid #E28585;
            transition: all 0.3s ease;
        }

        .step:hover {
            transform: translateX(5px);
            box-shadow: 0 5px 15px rgba(226, 133, 133, 0.2);
        }

        .step-number {
            background: linear-gradient(135deg, #E28585, #FF6B9D);
            color: #fff;
            width: 35px;
            height: 35px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.9rem;
            font-weight: bold;
            margin-right: 1.2rem;
            box-shadow: 0 3px 10px rgba(226, 133, 133, 0.3);
            flex-shrink: 0;
        }

        .step-text h3 {
            margin: 0 0 0.3rem 0;
            font-size: 1.1rem;
            font-weight: 600;
            color: #333;
        }

        .step-text p {
            margin: 0;
            font-size: 0.9rem;
            color: #666;
            line-height: 1.4;
        }

        .layout-preview-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1.2rem;
            margin-bottom: 1.5rem;
        }

        .layout-box {
            background: linear-gradient(135deg, rgba(226, 133, 133, 0.08), rgba(255, 255, 255, 0.1));
            border-radius: 12px;
            text-align: center;
            padding: 1rem;
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }

        .layout-box:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(226, 133, 133, 0.2);
            border-color: rgba(226, 133, 133, 0.3);
        }

        .layout-box img {
            width: 100%;
            max-width: 70px;
            margin-bottom: 0.8rem;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .layout-box h4 {
            margin: 0;
            font-size: 0.85rem;
            color: #333;
            font-weight: 600;
        }

        .layout-section-title {
            text-align: center;
            margin-bottom: 1.5rem;
            color: #333;
            font-size: 1.3rem;
            font-weight: 700;
            position: relative;
        }

        .layout-section-title::after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 50%;
            transform: translateX(-50%);
            width: 50px;
            height: 3px;
            background: linear-gradient(135deg, #E28585, #FF6B9D);
            border-radius: 2px;
        }

        .timer-info {
            background: linear-gradient(135deg, rgba(226, 133, 133, 0.1), rgba(255, 255, 255, 0.1));
            padding: 1rem;
            border-radius: 12px;
            border-left: 4px solid #E28585;
            margin-top: 1rem;
        }

        .timer-info p {
            margin: 0;
            font-size: 0.85rem;
            text-align: center;
            color: #666;
            line-height: 1.5;
        }

        .footer-buttons {
            display: flex;
            gap: 1rem;
            justify-content: space-between;
            margin-top: 2rem;
        }

        .btn {
            padding: 10px 20px;
            border-radius: 30px;
            font-weight: 600;
            text-decoration: none;
            text-align: center;
            font-size: 0.9rem;
            flex: 1;
        }

        .btn-primary {
            background: linear-gradient(135deg, #E28585, #FF6B9D);
            color: white;
        }

        .btn-outline-new {
            background: #fff;
            border: 2px solid var(--pink-secondary);
            color: var(--pink-primary);
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .btn-outline-new:hover {
            background: var(--pink-primary);
            border: 2px solid #fff;
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(233, 30, 99, 0.3);
        }

        @media (max-width: 768px) {
            .main-wrapper {
                grid-template-columns: 1fr;
                padding: 1rem;
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
                    <p>Akan memulaikan sesi foto selama 20 menit terhitung dari pembayaran</p>
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
                    <h3>Kirim ke Email atau Cetak </h3>
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
                    ⏰ Waktu total: 20 menit setelah pembayaran<br>
                    📸 Setiap foto memiliki countdown 3 detik
                </p>
            </div>
        </div>
    </div>

    <div class="footer-buttons" style="max-width: 1140px; margin: 1rem auto;">
        <a href="/" class="btn btn-outline-new">← Kembali</a>
        <a href="selectpayment.php" class="btn btn-outline-new">Lanjutkan</a>
    </div>
</body>

</html>