<?php
session_start();

// Data pengguna (hardcoded)
$users = [
    [
        "username" => "admin",
        "password" => "admin123",
        "role" => "admin"
    ],
    [
        "username" => "user",
        "password" => "user123",
        "role" => "user"
    ]
];

// Fungsi untuk login
function login($username, $password, $users) {
    foreach ($users as $user) {
        if ($user['username'] === $username && $user['password'] === $password) {
            return $user;
        }
    }
    return null;
}

// Proses logout
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_destroy();
    header('Location: ?page=dashboard');
    exit();
}

// Proses login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $user = login($username, $password, $users);

    if ($user) {
        $_SESSION['user'] = $user;
        // Redirect ke halaman sesuai role
        if ($user['role'] === 'admin') {
            header('Location: ?page=admin');
            exit();
        } else {
            header('Location: ?page=user');
            exit();
        }
    } else {
        $error = "Username atau password salah!";
    }
}

// Halaman yang diminta
$page = $_GET['page'] ?? 'dashboard';

// Jika user tidak login, arahkan ke halaman login untuk admin/user
if (!isset($_SESSION['user']) && in_array($page, ['admin', 'user'])) {
    header('Location: ?page=login');
    exit();
}

// Render halaman
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= ucfirst($page) ?> - GameStream</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: #1a1a2e;
            color: #eaeaea;
        }
        header {
            background: #0f3460;
            padding: 20px;
            text-align: center;
            color: white;
            font-size: 24px;
        }
        nav {
            display: flex;
            justify-content: center;
            background: #16213e;
            padding: 10px 0;
        }
        nav a {
            margin: 0 15px;
            text-decoration: none;
            color: white;
            font-size: 18px;
        }
        nav a:hover {
            text-decoration: underline;
        }
        section {
            padding: 20px;
            text-align: center;
        }
        footer {
            background: #0f3460;
            color: white;
            text-align: center;
            padding: 10px 0;
            position: fixed;
            bottom: 0;
            width: 100%;
        }
        .hero {
            background: url('https://via.placeholder.com/1500x400/0f3460/ffffff?text=Streaming+Game+Platform') no-repeat center center;
            background-size: cover;
            color: white;
            padding: 100px 20px;
            text-align: center;
        }
        .hero h1 {
            font-size: 3em;
            margin: 0;
        }
        .hero p {
            font-size: 1.2em;
            margin: 20px 0;
        }
        form input {
            margin: 10px 0;
            padding: 10px;
            width: 200px;
        }
        form button {
            padding: 10px 20px;
            background: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        form button:hover {
            background: #218838;
        }
        .gallery img {
            width: 300px;
            margin: 10px;
            border-radius: 10px;
        }
    </style>
</head>
<body>
    <header>GameStream - Pemesanan Jasa Streaming Game</header>
    <nav>
        <a href="?page=dashboard">Dashboard</a>
        <a href="?page=about">About Us</a>
        <a href="?page=gallery">Gallery</a>
        <a href="?page=content">Isi Pokok</a>
        <a href="?page=login">Login</a>
    </nav>
    <section>
        <?php if ($page === 'dashboard'): ?>
            <div class="hero">
                <h1>Selamat Datang di GameStream</h1>
                <p>Platform terbaik untuk memesan jasa streaming game dari gamer favorit Anda!</p>
            </div>
        <?php elseif ($page === 'about'): ?>
            <h1>About Us</h1>
            <p>GameStream adalah platform modern untuk memesan jasa streaming game. Kami bekerja dengan streamer terbaik untuk memberikan pengalaman gaming yang seru!</p>
        <?php elseif ($page === 'gallery'): ?>
            <h1>Gallery</h1>
            <div class="gallery">
                <img src="https://via.placeholder.com/300x200" alt="Game 1">
                <img src="https://via.placeholder.com/300x200" alt="Game 2">
                <img src="https://via.placeholder.com/300x200" alt="Game 3">
            </div>
        <?php elseif ($page === 'content'): ?>
            <h1>Isi Pokok Website</h1>
            <p>Di GameStream, Anda dapat menemukan streamer favorit, memesan layanan streaming, dan menikmati pengalaman gaming interaktif.</p>
        <?php elseif ($page === 'login'): ?>
            <h1>Login</h1>
            <?php if (!empty($error)): ?>
                <div class="error"><?= $error ?></div>
            <?php endif; ?>
            <form method="POST">
                <input type="text" name="username" placeholder="Username" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit" name="login">Login</button>
            </form>
        <?php elseif ($page === 'admin'): ?>
            <h1>Welcome, Admin!</h1>
            <p>Ini adalah halaman admin. Anda dapat mengelola data pengguna dan pemesanan di sini.</p>
            <a href="?action=logout">Logout</a>
        <?php elseif ($page === 'user'): ?>
            <h1>Welcome, User!</h1>
            <p>Terima kasih telah menggunakan jasa streaming kami. Selamat menikmati!</p>
            <a href="?action=logout">Logout</a>
        <?php endif; ?>
    </section>
    <footer>© 2025 GameStream. All Rights Reserved.</footer>
</body>
</html>
