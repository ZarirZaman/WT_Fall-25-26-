<?php
// index.php at project root (landing page)
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ElegantWare - Premium Ceramics & Tableware</title>
    <style>
/* ======================
   RESET & BASE - FROM INDEX.CSS
   ====================== */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Helvetica Neue', Arial, sans-serif;
}

body {
    background-color: #f8f9fa;
    color: #333;
    line-height: 1.6;
    height: 100vh;
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
}

.container {
    background: #fff;
    padding: 40px 30px;
    border-radius: 12px;
    text-align: center;
    width: 100%;
    max-width: 420px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
}

/* ======================
   TYPOGRAPHY - MATCHING INDEX.CSS
   ====================== */
h1 {
    font-size: 2.2rem;
    color: #2c3e50;
    margin-bottom: 10px;
    font-weight: 700;
}

p {
    color: #666;
    margin-bottom: 30px;
    font-size: 0.95rem;
    line-height: 1.5;
}

.footer-text {
    margin-top: 20px;
    font-size: 0.85rem;
    color: #777;
}

/* ======================
   BUTTONS - MATCHING INDEX.CSS BUTTON STYLES
   ====================== */
.btn {
    display: block;
    width: 100%;
    padding: 12px 28px;
    margin: 10px 0;
    font-size: 1rem;
    border: none;
    border-radius: 25px;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    text-align: center;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

.btn-primary {
    background: #e74c3c;
    color: white;
}

.btn-primary:hover {
    background: #c0392b;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(231, 76, 60, 0.3);
}

.btn-secondary {
    background: white;
    color: #333;
    border: 2px solid #e5e7eb;
}

.btn-secondary:hover {
    background: rgba(255, 255, 255, 0.9);
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(255, 255, 255, 0.2);
}

/* ======================
   RESPONSIVE DESIGN - FROM INDEX.CSS
   ====================== */
@media (max-width: 480px) {
    .container {
        padding: 30px 20px;
        margin: 0 15px;
    }

    h1 {
        font-size: 1.8rem;
    }

    p {
        font-size: 0.9rem;
    }

    .btn {
        padding: 10px 24px;
    }
}
</style>
</head>
<body>
    <div class="container">
        <h1>ElegantWare</h1>
        <p>Premium Ceramics & Tableware</p>

        <a href="/WT_Fall-25-26-/Project/ElegantWare/User/Customer/Controller/" class="btn btn-primary">Get Started</a>
        <a href="/WT_Fall-25-26-/Project/ElegantWare/User/Customer/Controller/login.php" class="btn btn-secondary">Login / Register</a>

        <div class="footer-text">© 2026 ElegantWare</div>
    </div>
</body>
</html>