<?php
session_start();

// File JSON dengan data pengguna
$usersFile = 'users.json';

// Fungsi untuk mendapatkan data pengguna
function getUsers($file) {
    return json_decode(file_get_contents($file), true);
}

// Fungsi untuk login
function login($username, $password, $file) {
    $users = getUsers($file);
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
    $user = login($username, $password, $usersFile);

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
if ($page === 'login'): ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                background-color: #f0f0f0;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
            }
            .login-container {
                background: white;
                padding: 20px;
                border-radius: 8px;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                width: 300px;
            }
            .login-container h1 {
                margin-bottom: 20px;
                text-align: center;
            }
            .login-container input[type="text"],
            .login-container input[type="password"] {
                width: 100%;
                padding: 10px;
                margin-bottom: 10px;
                border: 1px solid #ccc;
                border-radius: 4px;
            }
            .login-container button {
                width: 100%;
                padding: 10px;
                background-color: #007BFF;
                color: white;
                border: none;
                border-radius: 4px;
                cursor: pointer;
            }
            .login-container button:hover {
                background-color: #0056b3;
            }
            .error {
                color: red;
                text-align: center;
                margin-bottom: 10px;
            }
        </style>
    </head>
    <body>
        <div class="login-container">
            <h1>Login</h1>
            <?php if (!empty($error)): ?>
                <div class="error"><?= $error ?></div>
            <?php endif; ?>
            <form method="POST">
                <input type="text" name="username" placeholder="Username" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit" name="login">Login</button>
            </form>
        </div>
    </body>
    </html>

<?php elseif ($page === 'admin'): ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Admin Page</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                background-color: #333;
                color: white;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                flex-direction: column;
            }
            a {
                color: #007BFF;
                text-decoration: none;
                margin-top: 20px;
            }
        </style>
    </head>
    <body>
        <h1>Welcome, Admin!</h1>
        <a href="?action=logout">Logout</a>
    </body>
    </html>

<?php elseif ($page === 'user'): ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>User Page</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                background-color: #4CAF50;
                color: white;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                flex-direction: column;
            }
            a {
                color: #007BFF;
                text-decoration: none;
                margin-top: 20px;
            }
        </style>
    </head>
    <body>
        <h1>Welcome, User!</h1>
        <a href="?action=logout">Logout</a>
    </body>
    </html>
<?php endif; ?>
