<?php
include 'koneksi.php';
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE-edge">
        <meta name="viewport" content="width-device-width,
        initial-scale=1.0">
        <title>My Website</title>
        <link rel="stylesheet" href="css/style.css">
    </head>

    <body>
        <div class="wrapper">
            <div class="form-box login">
                <h2>Login</h2>
                <form method="POST" action="">
                    <div class="input-box">
                        <span class="icon"><ion-icon
                        name="mail-outline"></ion-icon></span>
                        <input type="email" name="email" required>
                        <label>Email</label>
                    </div>
                    <div class="input-box">
                        <span class="icon"><ion-icon
                        name="lock-closed-outline"></ion-icon></span>
                        <input type="password" name="password" required>
                        <label>Password</label>
                    </div>
                    <button type="submit" name="submit" class="btn">Login
                    </button>
                    <div class="login-register">
                        <p>Don't have an account? <a href="#"
                        class="register-link">Register</a></p>
                    </div>
                </form>
            </div>

            <div class="form-box register">
                <h2>Registration</h2>
                <form method="POST" action="simpan_register.php">
                    <div class="input-box">
                        <span class="icon"><ion-icon
                            name="person-outline"></ion-icon>
                        </span>
                        <input type="text" name="username" required>
                        <label>Username</label>
                    </div>
                    <div class="input-box">
                        <span class="icon"><ion-icon
                        name="mail-outline"></ion-icon></span>
                        <input type="email" name="email" required>
                        <label>Email</label>
                    </div>
                    <div class="input-box">
                        <span class="icon"><ion-icon
                        name="lock-closed-outline"></ion-icon></span>
                        <input type="password" name="password" required>
                        <label>Password</label>
                    </div>
                    <button type="submit" class="btn">Register
                    </button>
                    <div class="login-register">
                        <p>Already have an account? <a href="#"
                        class="login-link">Login</a>
                        </p>
                    </div>
                </form>
            </div>
        </div>

        <?php

        if (isset($_POST['submit'])) {
            $email = $_POST['email'];
            $password = $_POST['password'];

            $cek_data = mysqli_query($koneksi, "SELECT * FROM login WHERE email = '$email' AND password = '$password'");
            $hasil = mysqli_fetch_array($cek_data);
            $role = $hasil['role'];
            $login_user = $hasil['email'];
            $row = mysqli_num_rows($cek_data);

            if ($row > 0) {
                session_start();
                $_SESSION['login_user'] = $login_user;
                
                if ($role == 'admin') {
                    header('location: admin_page.php');

                } elseif ($role == 'user') {
                    header('location: index.html');
                } 
                        
            } else {
                header('login.php');
            }
                    
        }

        ?>

        <script src="js/script.js"></script>
        <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
        <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    </body>

</html>