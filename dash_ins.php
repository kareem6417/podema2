<?php
session_start();

if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
    header("location: ./admin/login.php");
    exit();
}

require_once 'admin/config.php';

try {
    $conn = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Koneksi ke database gagal: " . $e->getMessage();
    exit();
}

try {
    $stmt = $conn->query("SELECT COUNT(*) FROM form_inspeksi"); 
    $totalRows = $stmt->fetchColumn();

    $limit = isset($_GET['limit']) ? $_GET['limit'] : 10;
        if ($limit == 999999) {
            $totalRows = $conn->query("SELECT COUNT(*) FROM form_inspeksi")->fetchColumn();
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

    $stmt = $conn->prepare("SELECT * FROM form_inspeksi ORDER BY no ASC LIMIT :limit OFFSET :offset");
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
    <title>Daashboard Device Inspection</title>
    <link rel="stylesheet" href="css/styleadmin.css">
    <link rel="stylesheet" href="css/style-dash.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="icon" type="image/png" href="./favicon_io/iconfav.png">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="js/dash-ins.js"></script>
<body>
<div class="container">
    <div class="menu">
    <ul>
        <li><a href="./admin/admin.php"><i class="fas fa-user"></i> Administrator</a></li>
        <li class="dropdown">
            <a href="#" class="dropbtn"><i class="fas fa-chart-line"></i> Dashboard &#9662;</a>
            <div class="dropdown-content">
                <a href="dash_lap.php"><i class="fas fa-chart-area"></i> Dashboard Assessment Laptop</a>
                <a href="dash_pc.php"><i class="fas fa-chart-bar"></i> Dashboard Assessment PC</a>
                <a href="dash_ins.php"><i class="fas fa-chart-pie"></i> Dashboard Inspeksi</a>
            </div>
        </li>
        <li class="dropdown">
            <a href="#" class="dropbtn"><i class="fas fa-search"></i> Evaluation Portal &#9662;</a>
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
        <h2>Dashboard Inspection Device</h2>
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
                    <th>Name</th>
                    <th>Company</th>
                    <th>Issue</th>
                    <th>Recommendation</th>
                    <th>Detail</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($results as $count => $row) { ?>
                <tr>
                    <td><?php echo (($currentPage - 1) * $limit) + $count + 1; ?></td>
                    <td><?php echo $row['nama_user']; ?></td>
                    <td><?php echo $row['status']; ?></td>
                    <td>
                        <?php
                        $issues = explode("\n", $row['informasi_keluhan']);
                        foreach ($issues as $issue) {
                            echo $issue . "<br>";
                        }
                        ?>
                    </td>
                    <td>
                        <?php
                        $recommendations = explode("\n", $row['rekomendasi']);
                        foreach ($recommendations as $recommendation) {
                            echo $recommendation . "<br>";
                        }
                        ?>
                    </td>
                    <td>
                        <span class="popup-trigger"
                            data-date="<?php echo $row['date']; ?>"
                            data-jenis="<?php echo $row['jenis']; ?>"
                            data-nama_user="<?php echo $row['nama_user']; ?>"
                            data-status="<?php echo $row['status']; ?>"
                            data-merk="<?php echo $row['merk']; ?>"
                            data-serialnumber="<?php echo $row['serialnumber']; ?>"
                            data-lokasi="<?php echo $row['lokasi']; ?>"
                            data-informasi_keluhan="<?php echo $row['informasi_keluhan']; ?>"
                            data-hasil_pemeriksaan="<?php echo $row['hasil_pemeriksaan']; ?>"
                            data-rekomendasi="<?php echo $row['rekomendasi']; ?>">
                            <i class="fas fa-eye blue-eye"></i>
                        </span>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
        <div class="popup-overlay"></div>
        <div class="popup-content">
            <h3>Detail Devices Inspection</h3>
            <table>
                <tr>
                    <td>Date:</td>
                    <td id="popup-date"></td>
                </tr>
                <tr>
                    <td>Name:</td>
                    <td id="popup-nama_user"></td>
                </tr>
                <tr>
                    <td>Device Type:</td>
                    <td id="popup-jenis"></td>
                </tr>
                <tr>
                    <td>Merk:</td>
                    <td id="popup-merk"></td>
                </tr>
                <tr>
                    <td>Serial Number:</td>
                    <td id="popup-serialnumber"></td>
                </tr>
                <tr>
                    <td>Position/Department:</td>
                    <td id="popup-status"></td>
                </tr>
                <tr>
                    <td>Location:</td>
                    <td id="popup-lokasi"></td>
                </tr>
                <tr>
                    <td>Information on Complaints / Issues reported:</td>
                    <td id="popup-informasi_keluhan"></td>
                </tr>
                <tr>
                    <td>Examination/Findings:</td>
                    <td id="popup-hasil_pemeriksaan"></td>
                </tr>
                <tr>
                    <td>Recommendation:</td>
                    <td id="popup-rekomendasi"></td>
                </tr>
            </table>
        </div>
    <div class="scroll-to-top">
        <i class="fas fa-arrow-up"></i>
    </div>
</body>
</html>
