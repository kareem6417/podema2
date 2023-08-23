<?php
session_start();

if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
    header("location: login.php");
    exit;
}

require_once 'config.php';

$host = "localhost";
$db   = "podema";
$user = "root";
$pass = "";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$limit = isset($_GET['limit']) ? $_GET['limit'] : 10;

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $query = "SELECT * FROM users LIMIT $limit";
    $result = $conn->query($query);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $roles_id = $_POST['roles_id'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $name = $_POST['name'];
    $nik = $_POST['nik'];
    $company = $_POST['company'];
    $department = $_POST['department'];
    $email = $_POST['email'];

    $stmt = $conn->prepare("INSERT INTO users (roles_id, username, password, name, nik, company, department, email) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssssss", $roles_id, $username, $password, $name, $nik, $company, $department, $email);
    if ($stmt->execute()) {
        $_SESSION['notification'] = "Berhasil! Anda telah menambahkan pengguna baru.";
        header("Location: admin.php");
        exit();
    } else {
        $_SESSION['notification'] = "Tidak berhasil! Mohon diperiksa kembali, kemungkinan ada yang tidak sesuai ketentuan.";
        echo "Gagal menambahkan pengguna. Error: " . $stmt->error;
    }
}

if (isset($_SESSION['notification'])) {
    $userCreated = $_SESSION['notification'];
    unset($_SESSION['notification']);
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Management</title>
    <link rel="stylesheet" href="../css/casual.css">  
    <link rel="stylesheet" href="../css/search-usr.css">
    <link rel="stylesheet" href="../css/popup-a.css">
    <link rel="icon" type="image/png" href="../favicon_io/iconfav.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script>
    <script src="../js/admin-js.js"></script>
</head>
<body>
    <div class="container">
        <div class="menu">
            <ul>
                <li><a href="admin.php"><i class="fas fa-user"></i> Administrator</a></li>
                <li class="dropdown">
                    <a href="#" class="dropbtn"><i class="fas fa-chart-line"></i> Dashboard &#9662;</a>
                    <div class="dropdown-content">
                        <a href="../dash_lap.php"><i class="fas fa-chart-area"></i> Dashboard Assessment Laptop</a>
                        <a href="../dash_pc.php"><i class="fas fa-chart-bar"></i> Dashboard Assessment PC</a>
                        <a href="../dash_ins.php"><i class="fas fa-chart-pie"></i> Dashboard Inspeksi</a>
                    </div>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropbtn"><i class="fas fa-search"></i> Evaluation Portal &#9662;</a>
                    <div class="dropdown-content">
                        <a href="../tc_lap.php"><i class="fas fa-laptop"></i> Assessment Laptop</a>
                        <a href="../tc_pc.php"><i class="fas fa-desktop"></i> Assessment PC</a>
                        <a href="../tc_ins.php"><i class="fas fa-cogs"></i> Devices Inspection</a>
                    </div>
                </li>
                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </div>
        <h1>User Management</h1>
        <div class="filter-row">
        <div class="search-form">
            <label for="search" class="search-label">Search Users</label>
            <input type="text" name="search" id="search" onkeyup="searchUsers()" oninput="handleSearchInput()">
        </div>
        <button class="add-user-button" onclick="showPopup()">Add User</button>
        <form class="filter-form" method="get">
            <label for="limit">Rows per page:</label>
            <select id="limit" name="limit">
            <option value="10" <?= ($limit == 10) ? 'selected' : '' ?>>10</option>
            <option value="25" <?= ($limit == 25) ? 'selected' : '' ?>>25</option>
            <option value="50" <?= ($limit == 50) ? 'selected' : '' ?>>50</option>
            <option value="100" <?= ($limit == 100) ? 'selected' : '' ?>>100</option>
            <option value="999999" <?= ($limit == 999999) ? 'selected' : '' ?>>All</option>
            </select>
            <button class="apply-button" type="submit">Apply</button>
        </form>
        </div>
        <div id="notification" class="notification"></div>
        <?php if (isset($userCreated)): ?>
            <script>
                window.onload = function() {
                    var notification = document.getElementById("notification");
                    notification.textContent = "<?php echo $userCreated; ?>";
                    notification.style.display = "block";
                    setTimeout(function() {
                        notification.style.display = "none";
                    }, 3000);
                }
            </script>
        <?php endif; ?>
        <table>
    <thead>
        <tr>
            <th>Username</th>
            <th>Name</th>
            <th>NIK</th>
            <th>Company</th>
            <th>Department</th>
            <th>Actions</th>
        </tr>
    </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['username'] . "</td>";
                    echo "<td>" . $row['name'] . "</td>";
                    echo "<td>" . $row['nik'] . "</td>";
                    echo "<td>" . $row['company'] . "</td>";
                    echo "<td>" . $row['department'] . "</td>";
                    echo "<td style='text-align: center;'>";
                    echo "<a class='edit-icon' style='margin-right: 10px;' href='user_detail.php?user_id=" . $row['user_id'] . "&name=" . urlencode($row['name']) . "'><i class='fas fa-edit'></i></a>";
                    echo "<a class='delete-icon' href='delete_user.php?user_id=" . $row['user_id'] . "'><i class='fas fa-trash'></i></a>";
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6'>No users found</td></tr>";
            }
            ?>
        </tbody>
</table>
    </div>
    <div id="popup" class="popup">
        <div class="popup-content">
            <span class="popup-close" onclick="closePopup()">&times;</span>
            <h2 class="popup-title">Add User</h2>
            <form class="popup-form" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                <label for="roles_id">Roles ID:</label>
                <select name="roles_id" id="roles_id">
                    <option value="1">Admin</option>
                    <option value="2">End-user</option>
                </select>
                <label for="username">Username:</label>
                <input type="text" name="username" id="username" required>
                <label for="name">Name:</label>
                <input type="text" name="name" id="name" required>
                <label for="nik">NIK:</label>
                <input type="text" name="nik" id="nik" required>
                <label for="email">Email:</label>
                <input type="text" name="email" id="email" required>
                <label for="company">Company:</label>
                <select name="company" id="company">
                    <option value="PT Prima Andalan Mandiri">PT Prima Andalan Mandiri</option>
                    <optgroup label="PT Mandiri Intiperkasa">
                        <option value="PT Mandiri Intiperkasa HO">PT Mandiri Intiperkasa - HO</option>
                        <option value="PT Mandiri Intiperkasa Site">PT Mandiri Intiperkasa - Site</option>
                    </optgroup>
                    <optgroup label="PT Mandala Karya Prima">
                        <option value="PT Mandala Karya Prima HO">PT Mandala Karya Prima - HO</option>
                        <option value="PT Mandala Karya Prima Site">PT Mandala Karya Prima - Site</option>
                    </optgroup>
                    <optgroup label="PT Maritim Prima Mandiri">
                        <option value="PT Maritim Prima Mandiri HO">PT Maritim Prima Mandiri - HO</option>
                        <option value="PT Maritim Prima Mandiri Site">PT Maritim Prima Mandiri - Site</option>
                    </optgroup>
                </select>
                <label for="department">Department:</label>
                <input type="text" name="department" id="department" required>
                <br>
                <button type="submit">Submit</button>
                <button type="button" onclick="closePopup()">Cancel</button>
            </form>
        </div>
    </div>
    <div class="scroll-to-top" onclick="scrollToTop()">
        <i class="fas fa-arrow-up"></i>
    </div>
</body>
</html>