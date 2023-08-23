<?php
session_start();

if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
    header("location: ./admin/login.php");
    exit();
}

$host = "localhost";
$db   = "podema";
$user = "root";
$pass = "";

try {
    $conn = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Koneksi ke database gagal: " . $e->getMessage();
    exit();
}

try {
    $stmt = $conn->query("SELECT COUNT(*) FROM assess_laptop"); 
    $totalRows = $stmt->fetchColumn();

    $limit = isset($_GET['limit']) ? $_GET['limit'] : 10;
        if ($limit == 999999) {
            $totalRows = $conn->query("SELECT COUNT(*) FROM assess_laptop")->fetchColumn();
        } else {
            $allowedLimits = [10, 25, 50, 100];
            $limit = in_array($limit, $allowedLimits) ? $limit : 10;
            $totalRows = $limit;
        }

    $totalPages = 1;

    $currentPage = isset($_GET['page']) ? $_GET['page'] : 1;
    $currentPage = max(1, $currentPage); 
    $currentPage = min($currentPage, $totalPages); 

    $offset = ($currentPage - 1) * $limit;

    $stmt = $conn->prepare("SELECT assess_laptop.*, operating_sistem_laptop.os_name AS os_name, operating_sistem_laptop.os_score AS os_score,
                            processor_laptop.processor_name AS processor_name, processor_laptop.processor_score AS processor_score,
                            batterylife_laptop.battery_name AS battery_name, batterylife_laptop.battery_score AS battery_score,
                            device_age_laptop.age_name AS age_name, device_age_laptop.age_score AS age_score,
                            issue_software_laptop.issue_name AS issue_name, issue_software_laptop.issue_score AS issue_score,
                            ram_laptop.ram_name AS ram_name, ram_laptop.ram_score AS ram_score,
                            storage_laptop.storage_name AS storage_name, storage_laptop.storage_score AS storage_score,
                            keyboard_laptop.keyboard_name AS keyboard_name, keyboard_laptop.keyboard_score AS keyboard_score,
                            screen_laptop.screen_name AS screen_name, screen_laptop.screen_score AS screen_score,
                            touchpad_laptop.touchpad_name AS touchpad_name, touchpad_laptop.touchpad_score AS touchpad_score,
                            audio_laptop.audio_name AS audio_name, audio_laptop.audio_score AS audio_score,
                            body_laptop.body_name AS body_name, body_laptop.body_score AS body_score
                            FROM assess_laptop
                            LEFT JOIN operating_sistem_laptop ON assess_laptop.os = operating_sistem_laptop.os_score
                            LEFT JOIN processor_laptop ON assess_laptop.processor = processor_laptop.processor_score
                            LEFT JOIN batterylife_laptop ON assess_laptop.batterylife = batterylife_laptop.battery_score
                            LEFT JOIN device_age_laptop ON assess_laptop.age = device_age_laptop.age_score
                            LEFT JOIN issue_software_laptop ON assess_laptop.issue = issue_software_laptop.issue_score
                            LEFT JOIN ram_laptop ON assess_laptop.ram = ram_laptop.ram_score
                            LEFT JOIN storage_laptop ON assess_laptop.storage = storage_laptop.storage_score
                            LEFT JOIN keyboard_laptop ON assess_laptop.keyboard = keyboard_laptop.keyboard_score
                            LEFT JOIN screen_laptop ON assess_laptop.screen = screen_laptop.screen_score
                            LEFT JOIN touchpad_laptop ON assess_laptop.touchpad = touchpad_laptop.touchpad_score
                            LEFT JOIN audio_laptop ON assess_laptop.audio = audio_laptop.audio_score
                            LEFT JOIN body_laptop ON assess_laptop.body = body_laptop.body_score
                            ORDER BY assess_laptop.id DESC
                            LIMIT :limit OFFSET :offset");
                        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
                        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
                        $stmt->execute();

    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Laptop</title>
    <link rel="stylesheet" href="css/styleadmin.css">
    <link rel="stylesheet" href="css/style-dash.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css">
    <link rel="icon" type="image/png" href="./favicon_io/iconfav.png">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="js/dash-lap.js"></script>
</head>
<body>
    <div class="container">
        <div class="menu">
        <ul>
            <li><a href="admin/admin.php"><i class="fas fa-user"></i> Administrator</a></li>
            <li class="dropdown">
            <a href="#" class="dropbtn"><i class="fas fa-chart-line"></i> Dashboard &#9662;</a>
                <div class="dropdown-content">
                    <a href="dash_lap.php"><i class="fas fa-chart-area"></i> Dashboard Assessment Laptop</a>
                    <a href="dash_pc.php"><i class="fas fa-chart-bar"></i> Dashboard Assessment PC</a>
                    <a href="dash_ins.php"><i class="fas fa-chart-pie"></i> Dashboard Inspeksi</a>
                </div>
            </li>
            <li class="dropdown">
                <a href="#" class="dropbtn"><i class="fas fa-search"></i>Evaluation Portal &#9662;</a>
                <div class="dropdown-content">
                    <a href="tc_lap.php"><i class="fas fa-laptop"></i> Assessment Laptop</a>
                    <a href="tc_pc.php"><i class="fas fa-desktop"></i> Assessment PC</a>
                    <a href="tc_ins.php"><i class="fas fa-cogs"></i> Devices Inspection</a>
                </div>
            </li>
            <li><a href="admin/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </div>
        <h1>Hallo, <?php echo $_SESSION['username']; ?></h1>
        <h2>Dashboard Assessment Laptop</h2>
            <div class="filter-form">
                <form method="get">
                    <label for="limit">Rows per page:</label>
                    <select id="limit" name="limit">
                        <option value="10" <?= ($limit == 10) ? 'selected' : '' ?>>10</option>
                        <option value="25" <?= ($limit == 25) ? 'selected' : '' ?>>25</option>
                        <option value="50" <?= ($limit == 50) ? 'selected' : '' ?>>50</option>
                        <option value="100" <?= ($limit == 100) ? 'selected' : '' ?>>100</option>
                        <option value="999999" <?= ($limit == 999999) ? 'selected' : '' ?>>All</option>
                    </select>
                    <button type="submit">Apply</button>
                </form>
            </div>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Company</th>
                    <th>Type/Merk</th>
                    <th>Score</th>
                    <th>Detail</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($results as $count => $row) { ?>
                    <tr>
                        <td><?php echo (($currentPage - 1) * $limit) + $count + 1; ?></td>
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo $row['company'] . ' / ' . $row['divisi']; ?></td>
                        <td><?php echo $row['type']; ?></td>
                        <td><?php echo $row['score']; ?></td>
                        <td>
                        <span class="popup-trigger"
                            data-id="<?php echo $row['id']; ?>"
                            data-name="<?php echo $row['name']; ?>"
                            data-company="<?php echo $row['company'] . ' / ' . $row['divisi']; ?>"
                            data-type="<?php echo $row['type']; ?>"
                            data-serialnumber="<?php echo $row['serialnumber']; ?>"
                            data-os_name="<?php echo $row['os_name']; ?>"
                            data-os_score="<?php echo $row['os_score']; ?>"
                            data-processor_name="<?php echo $row['processor_name']; ?>"
                            data-processor_score="<?php echo $row['processor_score']; ?>"
                            data-battery_name="<?php echo $row['battery_name']; ?>"
                            data-battery_score="<?php echo $row['battery_score']; ?>"
                            data-age_name="<?php echo $row['age_name']; ?>"
                            data-age_score="<?php echo $row['age_score']; ?>"
                            data-issue_name="<?php echo $row['issue_name']; ?>"
                            data-issue_score="<?php echo $row['issue_score']; ?>"
                            data-ram_name="<?php echo $row['ram_name']; ?>"
                            data-ram_score="<?php echo $row['ram_score']; ?>"
                            data-storage_name="<?php echo $row['storage_name']; ?>"
                            data-storage_score="<?php echo $row['storage_score']; ?>"
                            data-keyboard_name="<?php echo $row['keyboard_name']; ?>"
                            data-keyboard_score="<?php echo $row['keyboard_score']; ?>"
                            data-screen_name="<?php echo $row['screen_name']; ?>"
                            data-screen_score="<?php echo $row['screen_score']; ?>"
                            data-touchpad_name="<?php echo $row['touchpad_name']; ?>"
                            data-touchpad_score="<?php echo $row['touchpad_score']; ?>"
                            data-audio_name="<?php echo $row['audio_name']; ?>"
                            data-audio_score="<?php echo $row['audio_score']; ?>"
                            data-body_name="<?php echo $row['body_name']; ?>"
                            data-body_score="<?php echo $row['body_score']; ?>"
                            data-score="<?php echo $row['score']; ?>">
                            <span class="icon-container">
                                <i class="fas fa-eye blue-eye" style="display: flex; justify-content: center; align-items: center;"></i>
                            </span>
                        </span>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <div class="popup-overlay"></div>
        <div class="popup-content">
            <h3>Detail Assessment Laptop</h3>
            <table>
                <tr>
                    <td>ID:</td>
                    <td id="popup-id"></td>
                </tr>
                <tr>
                    <td>Name:</td>
                    <td id="popup-name"></td>
                </tr>
                <tr>
                    <td>Company:</td>
                    <td id="popup-company"></td>
                </tr>
                <tr>
                    <td>Type/Merk:</td>
                    <td id="popup-type"></td>
                </tr>
                <tr>
                    <td>Serialnumber:</td>
                    <td id="popup-serialnumber"></td>
                </tr>
                <tr>
                    <td>Operating System:</td>
                    <td><span id="popup-os_name"></span><span id="popup-os_score"></span></td>
                </tr>
                <tr>
                    <td>Prosesor:</td>
                    <td><span id="popup-processor_name"></span> <span id="popup-processor_score"></span></td>
                </tr>
                <tr>
                    <td>Battery Life:</td>
                    <td><span id="popup-batterylife_name"></span><span id="popup-batterylife_score"></span></td>
                </tr>
                <tr>
                    <td>Device Age:</td>
                    <td><span id="popup-age_name"></span><span id="popup-age_score"></span></td>
                </tr>
                <tr>
                    <td>Issue Related Software:</td>
                    <td><span id="popup-issue_name"></span><span id="popup-issue_score"></span></td>
                </tr>
                <tr>
                    <td>RAM:</td>
                    <td><span id="popup-ram_name"></span><span id="popup-ram_score"></span></td>
                </tr>
                <tr>
                    <td>Storage:</td>
                    <td><span id="popup-storage_name"></span><span id="popup-storage_score"></span></td>
                </tr>
                <tr>
                    <td>Keyboard:</td>
                    <td><span id="popup-keyboard_name"></span><span id="popup-keyboard_score"></span></td>
                </tr>
                <tr>
                    <td>Screen:</td>
                    <td><span id="popup-screen_name"></span><span id="popup-screen_score"></span></td>
                </tr>
                <tr>
                    <td>Touchpad:</td>
                    <td><span id="popup-touchpad_name"></span><span id="popup-touchpad_score"></span></td>
                </tr>
                <tr>
                    <td>Audio:</td>
                    <td><span id="popup-audio_name"></span><span id="popup-audio_score"></span></td>
                </tr>
                <tr>
                    <td>Body:</td>
                    <td><span id="popup-body_name"></span><span id="popup-body_score"></span></td>
                </tr>
                <tr>
                    <td>Score:</td>
                    <td id="popup-score"></td>
                </tr>
            </table>
        </div>
    <div class="scroll-to-top">
        <i class="fas fa-arrow-up"></i>
    </div>
</body>
</html>