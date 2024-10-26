<?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "data";

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection Error: " . $conn->connect_error);
    }

    $nama = isset($_POST['nama']) ? $_POST['nama'] : '';
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    if (!empty($nama) && !empty($email) && !empty($password)) {
        $email_check_query = "SELECT email FROM user WHERE email = '$email'";
        $result = $conn->query($email_check_query);

        if ($result->num_rows > 0) {
            echo "Email is already registered. Please use another email.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO user (nama, email, password) VALUES ('$nama', '$email', '$hashed_password')";
            if ($conn->query($sql) === TRUE) {
                echo "Account succesully registered! Please login to your account";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }
    }

    $conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Commerce - Register</title>
    <link rel="stylesheet" href="style2.css">
    <link rel="icon" type="image/png" href="https://clubmate.fish/wp-content/uploads/2021/06/eCommerce-Icon-1.png">
</head>
<body>
   
    <header>
        <div class="header-left">
            <a href="">
                <img src="https://clubmate.fish/wp-content/uploads/2021/06/eCommerce-Icon-1.png" alt="e-commerce logo">
            </a>
        </div>
        <div class="header-center"></div>
        <div class="header-right">
            <a href="../login/login.php" class="btn-login">Login</a>
            <a href="" class="btn-register">Register</a>
        </div>
    </header>

    <div class="login-container">
        <div class="login-box">
            <h2>Sign Up</h2>
            <form action="register.php" method="POST">
                <div class="input-group">
                    <label for="username">Username</label>
                    <input type="text" id="nama" name="nama" placeholder="Enter your username" required>
                </div>
                <div class="input-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="Enter your email" required>
                </div>
                <div class="input-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Enter your password" required>
                </div>
                <div class="input-group">
                    <a href = "login.html">
                    <button type="submit" name= "register" class="btn-register">Sign Up</button>
                </a>
                </div>
                <div class="input-group">
                    <p class="signup-text">Already have an account? <a href="../login/login.php">Log In</a></p>
                </div>
            </form>
        </div>
    </div>

    <footer>
        <p>E-Commerce © 2024</p>
    </footer>
</body>
</html>