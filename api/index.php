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
    header('Location: ?page=login');
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
$page = $_GET['page'] ?? 'login';

// Jika user tidak login, arahkan ke halaman login
if (!isset($_SESSION['user']) && $page !== 'login') {
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
            text-align: center;
            margin-top: 50px;
        }
        h1 {
            color: #333;
        }
        a {
            display: inline-block;
            margin-top: 20px;
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
    </style>
</head>
<body>
    <?php if ($page === 'login'): ?>
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
        <p>Ini adalah halaman admin.</p>
        <a href="?action=logout">Logout</a>
    <?php elseif ($page === 'user'): ?>
        <h1>Welcome, User!</h1>
        <p>Ini adalah halaman user biasa.</p>
        <a href="?action=logout">Logout</a>
    <?php endif; ?>
</body>
</html>
