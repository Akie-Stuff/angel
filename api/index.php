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
    header('Location: ?page=landing');
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
$page = $_GET['page'] ?? 'landing';

// Jika user tidak login, arahkan ke halaman login untuk admin/user
if (!isset($_SESSION['user']) && $page !== 'landing' && $page !== 'login') {
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
    <title><?= ucfirst($page) ?> Page</title>
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
        section {
            padding: 20px;
            text-align: center;
        }
        a {
            display: inline-block;
            margin: 10px 0;
            text-decoration: none;
            color: white;
            background: #007BFF;
            padding: 10px 20px;
            border-radius: 5px;
        }
        a:hover {
            background: #0056b3;
        }
        .error {
            color: red;
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
        footer {
            background: #0f3460;
            color: white;
            text-align: center;
            padding: 10px 0;
            position: fixed;
            bottom: 0;
            width: 100%;
        }
    </style>
</head>
<body>
    <?php if ($page === 'landing'): ?>
        <header>GameStream - Pemesanan Jasa Streaming Game</header>
        <div class="hero">
            <h1>Selamat Datang di GameStream</h1>
            <p>Platform terbaik untuk memesan jasa streaming game dari gamer favorit Anda!</p>
            <a href="?page=login">Login</a>
        </div>
        <section>
            <h2>Apa yang Kami Tawarkan?</h2>
            <p>Temukan streamer favorit Anda, nikmati pengalaman gaming yang seru, dan pesan jasa streaming game dengan mudah!</p>
        </section>
        <footer>© 2025 GameStream. All Rights Reserved.</footer>
    <?php elseif ($page === 'login'): ?>
        <header>Login ke GameStream</header>
        <section>
            <h1>Login</h1>
            <?php if (!empty($error)): ?>
                <div class="error"><?= $error ?></div>
            <?php endif; ?>
            <form method="POST">
                <input type="text" name="username" placeholder="Username" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit" name="login">Login</button>
            </form>
        </section>
    <?php elseif ($page === 'admin'): ?>
        <header>GameStream - Halaman Admin</header>
        <section>
            <h1>Welcome, Admin!</h1>
            <p>Ini adalah halaman admin. Anda dapat mengelola data pengguna dan pemesanan di sini.</p>
            <a href="?action=logout">Logout</a>
        </section>
    <?php elseif ($page === 'user'): ?>
        <header>GameStream - Halaman User</header>
        <section>
            <h1>Welcome, User!</h1>
            <p>Terima kasih telah menggunakan jasa streaming kami. Selamat menikmati!</p>
            <a href="?action=logout">Logout</a>
        </section>
    <?php endif; ?>
</body>
</html>
