<?php
session_start();

if (isset($_SESSION['login_attempt'])) {
    $_SESSION['login_attempt']++;
} else {
    $_SESSION['login_attempt'] = 1;
}

$hostname = "mandiricoal.co.id";
$username = "mandiricoal";
$password = "Mandiricoal2022!";
$database = "podema";

$conn_podema = mysqli_connect($hostname, $username, $password, $database);

if (!$conn_podema) {
    die("Koneksi database userdata gagal: " . mysqli_connect_error());
}

if (isset($_POST['reset'])) {
    unset($_POST['username']);
    unset($_POST['password']);
}

if (isset($_POST['username']) && isset($_POST['password'])) {
    $uname = $_POST['username'];
    $password = $_POST['password'];

    $conn = new mysqli($host, $user, $pass, $db);

    if ($conn->connect_error) {
        die("Koneksi gagal: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("SELECT r.roles_name, u.password
                            FROM users u
                            INNER JOIN roles r ON u.roles_id = r.roles_id
                            WHERE u.username = ?");
    $stmt->bind_param("s", $uname);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if ($password == $row['password']) {
            $_SESSION['username'] = $uname;
            header("Location: admin.php");
            exit();
        }
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Portal Device Management Application</title>
    <link rel="stylesheet" href="../css/stylelogin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css">
    <link rel="icon" type="image/png" href="../favicon_io/iconfav.png">
    <style>
        .green-eye {
            color: green;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="login-form">
            <h2>Login Page</h2>
            <form method="POST" action="login.php">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" value="<?php echo isset($_POST['username']) ? $_POST['username'] : ''; ?>" required>

                <label for="password">Password:</label>
                <div class="password-input">
                    <input type="password" id="password" name="password" required>
                    <i class="fas fa-eye green-eye" id="togglePassword" style="margin-left: -30px; cursor: pointer;"></i>
                </div>

                <div class="button-group">
                    <input type="submit" name="login" value="Login">
                    <input type="reset" value="Reset">
                </div>
            </form>

            <?php
            if (isset($_POST['username']) && isset($_POST['password'])) {
                if (empty($_POST['username']) || empty($_POST['password'])) {
                    echo '<p class="login-fail">Username dan password harus diisi.</p>';
                } elseif (!isset($result) || $result->num_rows == 0) {
                    echo '<p class="login-fail">Login gagal. Silakan cek kembali username dan password Anda.</p>';
                }
            }
            ?>
            <script>
                const togglePassword = document.querySelector('#togglePassword');
                const password = document.querySelector('#password');

                password.setAttribute('type', 'password');
                togglePassword.classList.add('fa-eye-slash');

                togglePassword.addEventListener('click', function (e) {
                    const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                    password.setAttribute('type', type);
                    this.classList.toggle('fa-eye-slash');
                });
            </script>
        </div>
    </div>
</body>
</html>
