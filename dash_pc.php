<?php
session_start();

if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
    header("location: ./admin/login.php");
    exit();
}

$host = "mandiricoal.net";
$db   = "podema";
$user = "podema";
$pass = "podema2024@";

try {
    $conn = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Koneksi ke database gagal: " . $e->getMessage();
    exit();
}

try {
    $stmt = $conn->query("SELECT COUNT(*) FROM assess_pc"); 
    $totalRows = $stmt->fetchColumn();

    $limit = isset($_GET['limit']) ? $_GET['limit'] : 10;
        if ($limit == 999999) {
            $totalRows = $conn->query("SELECT COUNT(*) FROM assess_pc")->fetchColumn();
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

    $stmt = $conn->prepare("SELECT assess_pc.*, pctype_pc.pctype_name, pctype_pc.pctype_score, operating_sistem_pc.os_name, operating_sistem_pc.os_score, processor_pc.processor_name, processor_pc.processor_score, vga_pc.vga_name, 
                        vga_pc.vga_score, device_age_pc.age_name, device_age_pc.age_score, issue_software_pc.issue_name, issue_software_pc.issue_score, ram_pc.ram_name, ram_pc.ram_score, 
                        storage_pc.storage_name, storage_pc.storage_score, typemonitor_pc.monitor_name, typemonitor_pc.monitor_score, sizemonitor_pc.size_name, sizemonitor_pc.size_score
                        FROM assess_pc
                        LEFT JOIN pctype_pc ON assess_pc.typepc = pctype_pc.pctype_score
                        LEFT JOIN operating_sistem_pc ON assess_pc.os = operating_sistem_pc.os_score
                        LEFT JOIN processor_pc ON assess_pc.processor = processor_pc.processor_score
                        LEFT JOIN vga_pc ON assess_pc.vga = vga_pc.vga_score
                        LEFT JOIN device_age_pc ON assess_pc.age = device_age_pc.age_score
                        LEFT JOIN issue_software_pc ON assess_pc.issue = issue_software_pc.issue_score
                        LEFT JOIN ram_pc ON assess_pc.ram = ram_pc.ram_score
                        LEFT JOIN storage_pc ON assess_pc.storage = storage_pc.storage_score
                        LEFT JOIN typemonitor_pc ON assess_pc.typemonitor = typemonitor_pc.monitor_score
                        LEFT JOIN sizemonitor_pc ON assess_pc.sizemonitor = sizemonitor_pc.size_score
                        ORDER BY assess_pc.id DESC
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
    <title>Dashboard PC Desktop</title>
    <link rel="stylesheet" href="css/styleadmin.css">
    <link rel="stylesheet" href="css/style-dash.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css">
    <link rel="icon" type="image/png" href="./favicon_io/iconfav.png">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="js/dash-pc.js"></script>
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

        <h2>Dashboard Assessment PC Desktop</h2>
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
                <th>PC Type</th>
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
                        <td><?php echo $row['pctype_name']; ?></td>
                        <td><?php echo $row['score']; ?></td>
                        <td>
                        <span class="popup-trigger"
                            data-id="<?php echo $row['id']; ?>"
                            data-name="<?php echo $row['name']; ?>"
                            data-company="<?php echo $row['company'] . ' / ' . $row['divisi']; ?>"
                            data-merk="<?php echo $row['merk']; ?>"
                            data-serialnumber="<?php echo $row['serialnumber']; ?>"
                            data-pctype_name="<?php echo $row['pctype_name']; ?>"
                            data-pctype_score="<?php echo $row['pctype_score']; ?>"
                            data-os_name="<?php echo $row['os_name']; ?>"
                            data-os_score="<?php echo $row['os_score']; ?>"
                            data-processor_name="<?php echo $row['processor_name']; ?>"
                            data-processor_score="<?php echo $row['processor_score']; ?>"
                            data-vga_name="<?php echo $row['vga_name']; ?>"
                            data-vga_score="<?php echo $row['vga_score']; ?>"
                            data-age_name="<?php echo $row['age_name']; ?>"
                            data-age_score="<?php echo $row['age_score']; ?>"
                            data-issue_name="<?php echo $row['issue_name']; ?>"
                            data-issue_score="<?php echo $row['issue_score']; ?>"
                            data-ram_name="<?php echo $row['ram_name']; ?>"
                            data-ram_score="<?php echo $row['ram_score']; ?>"
                            data-storage_name="<?php echo $row['storage_name']; ?>"
                            data-storage_score="<?php echo $row['storage_score']; ?>"
                            data-monitor_name="<?php echo $row['monitor_name']; ?>"
                            data-monitor_score="<?php echo $row['monitor_score']; ?>"
                            data-size_name="<?php echo $row['size_name']; ?>"
                            data-size_score="<?php echo $row['size_score']; ?>"
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
            <h3>Detail Assessment PC Desktop</h3>
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
                    <td>Merk:</td>
                    <td id="popup-merk"></td>
                </tr>
                <tr>
                    <td>Serialnumber:</td>
                    <td id="popup-serialnumber"></td>
                </tr>
                <tr>
                    <td>PC Type:</td>
                    <td><span id="popup-pctype_name"></span><span id="popup-pctype_score"></span></td>
                </tr>
                <tr>
                    <td>Operating System:</td>
                    <td><span id="popup-os_name"></span><span id="popup-os_score"></span></td>
                </tr>
                <tr>
                    <td>Processor:</td>
                    <td><span id="popup-processor_name"></span><span id="popup-processor_score"></span></td>
                </tr>
                <tr>
                    <td>VGA:</td>
                    <td><span id="popup-vga_name"></span><span id="popup-vga_score"></span></td>
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
                    <td>Type Monitor:</td>
                    <td><span id="popup-monitor_name"></span><span id="popup-monitor_score"></span></td>
                </tr>
                <tr>
                    <td>Size Monitor:</td>
                    <td><span id="popup-size_name"></span><span id="popup-size_score"></span></td>
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
