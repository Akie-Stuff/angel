<?php
// Nama file JSON untuk menyimpan data pengguna
$file = 'users.json';

// Pastikan file JSON ada, jika tidak buat file kosong
if (!file_exists($file)) {
    file_put_contents($file, json_encode([]));
}

// Fungsi untuk membaca data pengguna dari file JSON
function getUsers() {
    global $file;
    return json_decode(file_get_contents($file), true);
}

// Fungsi untuk menyimpan data pengguna ke file JSON
function saveUsers($users) {
    global $file;
    file_put_contents($file, json_encode($users, JSON_PRETTY_PRINT));
}

// Proses Login
if (isset($_POST['action']) && $_POST['action'] === 'login') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $users = getUsers();

    foreach ($users as $user) {
        if ($user['email'] === $email && password_verify($password, $user['password'])) {
            echo "Login berhasil! Selamat datang, {$user['name']}!";
            exit;
        }
    }
    echo "Login gagal! Email atau password salah.";
}

// Proses Register
if (isset($_POST['action']) && $_POST['action'] === 'register') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $users = getUsers();

    foreach ($users as $user) {
        if ($user['email'] === $email) {
            echo "Registrasi gagal! Email sudah terdaftar.";
            exit;
        }
    }

    $users[] = [
        'name' => $name,
        'email' => $email,
        'password' => $password,
    ];

    saveUsers($users);
    echo "Registrasi berhasil! Silakan login.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login & Register</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 400px;
            margin: 50px auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        label {
            margin-bottom: 5px;
            font-weight: bold;
        }
        input {
            margin-bottom: 15px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        button {
            padding: 10px;
            background: #007BFF;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background: #0056b3;
        }
        .toggle {
            text-align: center;
            margin-top: 10px;
        }
        .toggle a {
            color: #007BFF;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php if (!isset($_GET['action']) || $_GET['action'] === 'login'): ?>
            <!-- Halaman Login -->
            <h1>Login</h1>
            <form method="POST">
                <input type="hidden" name="action" value="login">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
                <button type="submit">Login</button>
            </form>
            <div class="toggle">
                Belum punya akun? <a href="?action=register">Daftar di sini</a>
            </div>
        <?php else: ?>
            <!-- Halaman Register -->
            <h1>Register</h1>
            <form method="POST">
                <input type="hidden" name="action" value="register">
                <label for="name">Nama</label>
                <input type="text" id="name" name="name" required>
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
                <button type="submit">Register</button>
            </form>
            <div class="toggle">
                Sudah punya akun? <a href="?action=login">Login di sini</a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>

