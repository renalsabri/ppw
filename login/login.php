<?php
session_start();
require_once 'vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$clientID = $_ENV['GOOGLE_CLIENT_ID'];
$clientSecret = $_ENV['GOOGLE_CLIENT_SECRET'];
$redirectUri = 'http://localhost/ppw/beranda/beranda.php';

$client = new Google_Client();
$client->setClientId($clientID);
$client->setClientSecret($clientSecret);
$client->setRedirectUri($redirectUri);
$client->addScope("email");
$client->addScope("profile");

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "data";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection Error: " . $conn->connect_error);
}

// Proses login dengan Google
if (isset($_GET['code'])) {
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    
    if (!isset($token['error'])) { 
        $client->setAccessToken($token['access_token']);

        $google_oauth = new Google_Service_Oauth2($client);
        $google_account_info = $google_oauth->userinfo->get();
        $email = $google_account_info->email;
        $name = $google_account_info->name;
        $foto = $google_account_info->picture;

        // Periksa apakah email sudah ada di database
        $stmt = $conn->prepare("SELECT * FROM user WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 0) {
            $foto = '../foto/profile.png';  // Set foto default jika tidak ada
            $stmt = $conn->prepare("INSERT INTO user (email, name, foto) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $email, $name, $foto);
            $stmt->execute();
        }

        // Simpan data user di sesi
        $_SESSION['email'] = $email;
        $_SESSION['name'] = $name;
        $_SESSION['foto'] = $foto ? $foto : '../foto/profile.png';

        header("Location: ../beranda/beranda.php");
        exit();
    } else {
        echo "<script>alert('Error fetching token: " . $token['error'] . "');</script>";
    }
}

// Proses login manual
$email = isset($_POST['email']) ? $_POST['email'] : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';
$recaptcha_response = isset($_POST['g-recaptcha-response']) ? $_POST['g-recaptcha-response'] : '';

if (!empty($email) && !empty($password)) {
    if (!empty($recaptcha_response)) {
        $secret_key = "6Lf9ZG0qAAAAANrebi38cpef44RqCxyQ4WHzs-Ih";
        $verify_url = "https://www.google.com/recaptcha/api/siteverify";
        $response = file_get_contents($verify_url . "?secret=" . $secret_key . "&response=" . $recaptcha_response);
        $response_keys = json_decode($response, true);

        if ($response_keys["success"]) {
            // Periksa email dan password di database
            $stmt = $conn->prepare("SELECT password, nama, foto FROM user WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $hashed_password = $row['password'];

                if (password_verify($password, $hashed_password)) {
                    // Simpan email dan data user lainnya di sesi
                    $_SESSION['email'] = $email;
                    $_SESSION['name'] = $row['name'];
                    $_SESSION['foto'] = $row['foto'] ? $row['foto'] : '../foto/profile.png';

                    header("Location: ../beranda/beranda.php");
                    exit();
                } else {
                    echo "<script>alert('Incorrect password. Please try again!');</script>";
                }
            } else {
                echo "<script>alert('Email not found. Please register first!');</script>";
            }
            $stmt->close();
        } else {
            echo "<script>alert('reCAPTCHA verification failed. Please try again!');</script>";
        }
    } else {
        echo "<script>alert('Please complete the reCAPTCHA!');</script>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Commerce | Login</title>
    <link rel="stylesheet" href="style_login.css">
    <link rel="icon" type="image/x-icon" href="image/icon.png">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>
<body>
    <header>
        <div class="header-left">
            <a href="">
            <img src="image/logo.jpg" alt="e-commerce logo">
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
                <div class="g-recaptcha" data-sitekey="6Lf9ZG0qAAAAAMeMJRZzqnnFRp-QP44udJghl6c9"></div>
                <div class="input-group">
                    <button type="submit" name="login" class="btn-login">Log In</button>
                </div>
                <div class="input-group">
                    <p class="signup-text">Don't have an account? <a href="../register/register.php">Sign Up</a></p>
                </div>
                <div class="separator">
                    <span>or login with</span>
                </div>
                <div>
                    <a href="<?php echo $client->createAuthUrl() ?>" class="btn-google">
                        Google
                    </a>
                </div>
            </form>
        </div>
    </div>

    
</body>
</html>