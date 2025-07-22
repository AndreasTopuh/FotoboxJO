<?php
// Session start untuk PHP functionality jika diperlukan
session_start();
// Set session untuk bypass payment check (untuk testing)
$_SESSION['has_paid'] = true;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Gofotobox - Landing Page</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            background: linear-gradient(135deg, #6dd5ed, #2193b0);
            color: #fff;
            flex-direction: column;
            text-align: center;
        }

        h1 {
            font-size: 3em;
            margin-bottom: 0.5em;
        }

        p {
            font-size: 1.2em;
            margin-bottom: 2em;
        }

        .button {
            padding: 1em 2em;
            font-size: 1em;
            background-color: #ffffff;
            color: #2193b0;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .button:hover {
            background-color: #eee;
            transform: scale(1.05);
        }
    </style>
</head>

<body>

    <h1>Selamat Datang di Gofotobox!</h1>
    <p>Klik tombol di bawah ini untuk mulai memilih layout fotomu.</p>

    <a href="./src/pages/selectlayout.php" class="button">Mulai Sekarang</a>

</body>

</html>