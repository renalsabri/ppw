<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "data";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection Error: " . $conn->connect_error);
}

$email = isset($_POST['email']) ? $_POST['email'] : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';

if (!empty($email) && !empty($password)) {
    $stmt = $conn->prepare("SELECT password FROM user WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $hashed_password = $row['password'];

        if (password_verify($password, $hashed_password)) {
            echo "<script>alert('Login successful!');</script>";
            header("Location: ../beranda/beranda.html");
            exit();
        } else {
            echo "<script>alert('Incorrect password. Please try again.');</script>";
        }
    } else {
        echo "<script>alert('Email not found. Please register first.');</script>";
    }
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Commerce - Login</title>
    <link rel="stylesheet" href="style_login.css">
    <link rel="icon" type="image/x-icon" href="https://clubmate.fish/wp-content/uploads/2021/06/eCommerce-Icon-1.png">
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
            <a href="" class="btn-login">Login</a>
            <a href="../register/register.php" class="btn-register">Register</a>
        </div>
    </header>

    <div class="login-container">
        <div class="login-box">
            <h2>Login</h2>
            <form action="login.php" method="POST">
                <div class="input-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="Enter your email" required>
                </div>
                <div class="input-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Enter your password" required>
                </div>
                <div class="input-group remember-me">
                    <input type="checkbox" id="remember" name="remember">
                    <label for="remember">Remember me</label>
                </div>
                <div class="input-group">
                    <button type="submit" name="login" class="btn-login">Log In</button>
                </div>
                <div class="input-group">
                    <p class="signup-text">Don't have an account? <a href="../register/register.php">Sign Up</a></p>
                </div>
            </form>
        </div>
    </div>

    <footer>
        <p>E-Commerce © 2024</p>
    </footer>
</body>
</html>