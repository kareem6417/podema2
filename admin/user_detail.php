<?php
session_start();

require_once 'config.php';

$host = "mandiricoal.net";
$db   = "podema";
$user = "podema";
$pass = "podema2024@";

//assessment_laptop
if (isset($_GET['user_id']) && isset($_GET['name'])) {
    $user_id = $_GET['user_id'];
    $name = $_GET['name'];

    $assessment_sql = "SELECT a.date, a.type, a.serialnumber, 
                            os.os_name AS os, 
                            processor.processor_name AS processor, 
                            batterylife.battery_name AS batterylife, 
                            age.age_name AS age, 
                            issue.issue_name AS issue, 
                            ram.ram_name AS ram, 
                            storage.storage_name AS storage, 
                            keyboard.keyboard_name AS keyboard, 
                            screen.screen_name AS screen, 
                            touchpad.touchpad_name AS touchpad, 
                            audio.audio_name AS audio, 
                            body.body_name AS body, 
                            a.score
                    FROM assess_laptop a
                    LEFT JOIN operating_sistem_laptop os ON a.os = os.os_id
                    LEFT JOIN processor_laptop processor ON a.processor = processor.processor_id
                    LEFT JOIN batterylife_laptop batterylife ON a.batterylife = batterylife.battery_id
                    LEFT JOIN device_age_laptop age ON a.age = age.age_id
                    LEFT JOIN issue_software_laptop issue ON a.issue = issue.issue_id
                    LEFT JOIN ram_laptop ram ON a.ram = ram.ram_id
                    LEFT JOIN storage_laptop storage ON a.storage = storage.storage_id
                    LEFT JOIN keyboard_laptop keyboard ON a.keyboard = keyboard.keyboard_id
                    LEFT JOIN screen_laptop screen ON a.screen = screen.screen_id
                    LEFT JOIN touchpad_laptop touchpad ON a.touchpad = touchpad.touchpad_id
                    LEFT JOIN audio_laptop audio ON a.audio = audio.audio_id
                    LEFT JOIN body_laptop body ON a.body = body.body_id
                    WHERE a.name = ?";

    $assessment_stmt = $conn->prepare($assessment_sql);
    if ($assessment_stmt === false) {
        die("Kesalahan saat menyiapkan query assessment: " . $conn->error);
    }

    $assessment_stmt->bind_param("s", $name);
    if (!$assessment_stmt->execute()) {
        die("Kesalahan saat menjalankan query assessment: " . $assessment_stmt->error);
    }

    $assessment_result = $assessment_stmt->get_result();
    $assessment_stmt->close();
} else {
    echo "Tidak ada ID pengguna atau nama yang diberikan.";
}

//assessment_pc
if (isset($_GET['user_id']) && isset($_GET['name'])) {
    $user_id = $_GET['user_id'];
    $name = $_GET['name'];

    $assessmentpc_sql = "SELECT a.date, a.merk, a.serialnumber, 
                            pctype.pctype_name AS typepc,
                            os.os_name AS os, 
                            processor.processor_name AS processor, 
                            vga.vga_name AS vga, 
                            age.age_name AS age, 
                            issue.issue_name AS issue, 
                            ram.ram_name AS ram, 
                            storage.storage_name AS storage, 
                            typemonitor.monitor_name AS typemonitor, 
                            sizemonitor.size_name AS sizemonitor, 
                            a.score
                    FROM assess_pc a
                    LEFT JOIN pctype_pc pctype ON a.typepc = pctype.pctype_score
                    LEFT JOIN operating_sistem_pc os ON a.os = os.os_score
                    LEFT JOIN processor_pc processor ON a.processor = processor.processor_score
                    LEFT JOIN vga_pc vga ON a.vga = vga.vga_score
                    LEFT JOIN device_age_pc age ON a.age = age.age_score
                    LEFT JOIN issue_software_pc issue ON a.issue = issue.issue_score
                    LEFT JOIN ram_pc ram ON a.ram = ram.ram_score
                    LEFT JOIN storage_pc storage ON a.storage = storage.storage_score
                    LEFT JOIN typemonitor_pc typemonitor ON a.typemonitor = typemonitor.monitor_score
                    LEFT JOIN sizemonitor_pc sizemonitor ON a.sizemonitor = sizemonitor.size_score
                    WHERE a.name = ?";

    $assessmentpc_stmt = $conn->prepare($assessmentpc_sql);
    if ($assessment_stmt === false) {
        die("Kesalahan saat menyiapkan query assessment: " . $conn->error);
    }

    $assessmentpc_stmt->bind_param("s", $name);
    if (!$assessmentpc_stmt->execute()) {
        die("Kesalahan saat menjalankan query assessment PC: " . $assessmentpc_stmt->error);
    }

    $assessmentpc_result = $assessmentpc_stmt->get_result();
    $assessmentpc_stmt->close();
} else {
    echo "Tidak ada ID pengguna atau nama yang diberikan.";
}

//inspeksi
if (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];

    $user_sql = "SELECT users.*, roles.roles_name 
                 FROM users 
                 INNER JOIN roles ON users.roles_id = roles.roles_id 
                 WHERE users.user_id = ?";

    $user_stmt = $conn->prepare($user_sql);
    if ($user_stmt === false) {
        die("Kesalahan saat menyiapkan query pengguna: " . $conn_userdata->error);
    }

    $user_stmt->bind_param("i", $user_id);
    if (!$user_stmt->execute()) {
        die("Kesalahan saat menjalankan query pengguna: " . $user_stmt->error);
    }

    $user_result = $user_stmt->get_result();

    if ($user_result && $user_result->num_rows > 0) {
        $row = $user_result->fetch_assoc();
        $roles = $row["roles_name"];
        $username = $row["username"];
        $name = $row["name"];
        $nik = $row["nik"];
        $company = $row["company"];
        $department = $row["department"];

        $_SESSION['name'] = $name;

        $form_inspeksi_sql = "SELECT * 
                              FROM form_inspeksi 
                              WHERE nama_user = ?";

        $form_inspeksi_stmt = $conn->prepare($form_inspeksi_sql);
        if ($form_inspeksi_stmt === false) {
            die("Kesalahan saat menyiapkan query form inspeksi: " . $conn->error);
        }

        $form_inspeksi_stmt->bind_param("s", $name);
        if (!$form_inspeksi_stmt->execute()) {
            die("Kesalahan saat menjalankan query form inspeksi: " . $form_inspeksi_stmt->error);
        }

        $form_inspeksi_result = $form_inspeksi_stmt->get_result();

        $form_inspeksi_stmt->close();
    } else {
        echo "Pengguna tidak ditemukan.";
    }

    $user_stmt->close();
} else {
    echo "Tidak ada ID pengguna yang diberikan.";
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Detail Pengguna</title>
    <link rel="stylesheet" href="../css/user-dtl.css">
    <link rel="stylesheet" href="../css/user-dtl-tbl-di.css">
    <link rel="icon" type="image/png" href="../favicon_io/iconfav.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script src="../js/admin-expand.js"></script>
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
        <h2>Detail Pengguna, <em><u><?php echo isset($_SESSION['name']) ? $_SESSION['name'] : ""; ?></u></em></h2>
        <table>
            <tr>
                <td>Roles:</td>
                <td><?php echo isset($roles) ? $roles : ""; ?></td>
            </tr>
            <tr>
                <td>Username:</td>
                <td><?php echo isset($username) ? $username : ""; ?></td>
            </tr>
            <tr>
                <td>Name:</td>
                <td><?php echo isset($name) ? $name : ""; ?></td>
            </tr>
            <tr>
                <td>NIK:</td>
                <td><?php echo isset($nik) ? $nik : ""; ?></td>
            </tr>
            <tr>
                <td>Company:</td>
                <td><?php echo isset($company) ? $company : ""; ?></td>
            </tr>
            <tr>
                <td>Department:</td>
                <td><?php echo isset($department) ? $department : ""; ?></td>
            </tr>
        </table>
        <div class="device-info">
            <h3>Informasi Perangkat</h3>
            <?php
            if (isset($assessment_result)) {
                if ($assessment_result->num_rows > 0) {
                    $count = 1;
                    while ($assessment_row = $assessment_result->fetch_assoc()) {
                        echo "<div class='expand-btn' onclick='toggleAssessment(" . $count . ")'>Assessment Laptop ke-" . $count . "</div>";

                        echo "<div class='assessment-content' id='assessment-" . $count . "'>";
                        echo "<table>";
                        echo "<tr><th colspan='2'>" . $assessment_row["type"] . " / " . $assessment_row["serialnumber"] . "</th></tr>";
                        echo "<tr><td>Tanggal</td><td>" . $assessment_row["date"] . "</td</tr>";
                        echo "<tr><td>Sistem Operasi</td><td>" . $assessment_row["os"] . "</td></tr>";
                        echo "<tr><td>Processor</td><td>" . $assessment_row["processor"] . "</td></tr>";
                        echo "<tr><td>Ketahanan Baterai (Tanpa Daya)</td><td>" . $assessment_row["batterylife"] . "</td></tr>";
                        echo "<tr><td>Usia Perangkat</td><td>" . $assessment_row["age"] . "</td></tr>";
                        echo "<tr><td>Isue Terkait Software</td><td>" . $assessment_row["issue"] . "</td></tr>";
                        echo "<tr><td>RAM</td><td>" . $assessment_row["ram"] . "</td></tr>";
                        echo "<tr><td>Penyimpanan</td><td>" . $assessment_row["storage"] . "</td></tr>";
                        echo "<tr><td>Keyboard</td><td>" . $assessment_row["keyboard"] . "</td></tr>";
                        echo "<tr><td>Layar</td><td>" . $assessment_row["screen"] . "</td></tr>";
                        echo "<tr><td>Touchpad</td><td>" . $assessment_row["touchpad"] . "</td></tr>";
                        echo "<tr><td>Audio</td><td>" . $assessment_row["audio"] . "</td></tr>";
                        echo "<tr><td>Rangka</td><td>" . $assessment_row["body"] . "</td></tr>";
                        echo "<tr><td>Score</td><td>" . $assessment_row["score"] . "</td></tr>";
                        echo "</table>";
                        echo "</div>";
                        echo "<br>";

                        $count++;
                    }
                } else {
                    echo "Data Assessment Laptop tidak ditemukan.";
                }
            } else {
                echo "Terjadi kesalahan saat menjalankan query Assessment Laptop.";
            }

            if (isset($assessmentpc_result)) {
                if ($assessmentpc_result->num_rows > 0) {
                    $count_pc = 1;
                    while ($assessmentpc_row = $assessmentpc_result->fetch_assoc()) {
                        echo "<div class='expand-btn' onclick='toggleAssessmentPC(" . $count_pc . ")'>Assessment PC ke-" . $count_pc . "</div>";
        
                        echo "<div class='assessment-content' id='assessment-pc-" . $count_pc . "'>";
                        echo "<table>";
                        echo "<tr><th colspan='2'>" . $assessmentpc_row["merk"] . " / " . $assessmentpc_row["serialnumber"] . "</th></tr>";
                        echo "<tr><td>Tanggal</td><td>" . $assessmentpc_row["date"] . "</td</tr>";
                        echo "<tr><td>Tipe PC</td><td>" . $assessmentpc_row["typepc"] . "</td></tr>";
                        echo "<tr><td>Sistem Operasi</td><td>" . $assessmentpc_row["os"] . "</td></tr>";
                        echo "<tr><td>Processor</td><td>" . $assessmentpc_row["processor"] . "</td></tr>";
                        echo "<tr><td>VGA</td><td>" . $assessmentpc_row["vga"] . "</td></tr>";
                        echo "<tr><td>Usia Perangkat</td><td>" . $assessmentpc_row["age"] . "</td></tr>";
                        echo "<tr><td>Isu Terkait Software</td><td>" . $assessmentpc_row["issue"] . "</td></tr>";
                        echo "<tr><td>RAM</td><td>" . $assessmentpc_row["ram"] . "</td></tr>";
                        echo "<tr><td>Penyimpanan</td><td>" . $assessmentpc_row["storage"] . "</td></tr>";
                        echo "<tr><td>Tipe Monitor</td><td>" . $assessmentpc_row["typemonitor"] . "</td></tr>";
                        echo "<tr><td>Ukuran Monitor</td><td>" . $assessmentpc_row["sizemonitor"] . "</td></tr>";
                        echo "<tr><td>Score</td><td>" . $assessmentpc_row["score"] . "</td></tr>";
                        echo "</table>";
                        echo "</div>";
                        echo "<br>";
        
                        $count_pc++;
                    }
                } else {
                    echo "Data Assessment PC tidak ditemukan.";
                }
            } else {
                echo "Terjadi kesalahan saat menjalankan query Assessment PC.";
            }
            ?>
        </div>
        <div class="inspection-form">
            <h3>Form Inspeksi</h3>
            <?php
            if (isset($form_inspeksi_result)) {
                if ($form_inspeksi_result->num_rows > 0) {
                    $count = 1;
                    while ($form_inspeksi_row = $form_inspeksi_result->fetch_assoc()) {
                        echo "<div class='expand-btn' onclick='toggleInspeksi(" . $count . ")'>Inspeksi ke-" . $count . "</div>";

                        echo "<div class='inspeksi-content' id='inspeksi-" . $count . "'>";
                        echo "<table>";
                        echo "<tr><th colspan='2'>Inspeksi ke-" . $count . "</th></tr>";
                        echo "<tr><td>No</td><td>" . $form_inspeksi_row["no"] . "</td></tr>";
                        echo "<tr><td>Date</td><td>" . $form_inspeksi_row["date"] . "</td></tr>";
                        echo "<tr><td>Nama Pengguna</td><td>" . $form_inspeksi_row["nama_user"] . "</td></tr>";
                        echo "<tr><td>Jenis</td><td>" . $form_inspeksi_row["jenis"] . "</td></tr>";
                        echo "<tr><td>Status</td><td>" . $form_inspeksi_row["status"] . "</td></tr>";
                        echo "<tr><td>Merk</td><td>" . $form_inspeksi_row["merk"] . "</td></tr>";
                        echo "<tr><td>Serial Number</td><td>" . $form_inspeksi_row["serialnumber"] . "</td></tr>";
                        echo "<tr><td>Lokasi</td><td>" . $form_inspeksi_row["lokasi"] . "</td></tr>";
                        echo "<tr><td>Informasi Keluhan</td><td>" . $form_inspeksi_row["informasi_keluhan"] . "</td></tr>";
                        $hasil_pemeriksaan = explode("\n", $form_inspeksi_row["hasil_pemeriksaan"]);
                        $hasil_pemeriksaan_text = implode("<br>", $hasil_pemeriksaan);
                        echo "<tr><td>Hasil Pemeriksaan</td><td>" . $hasil_pemeriksaan_text . "</td></tr>";
                        $rekomendasi = explode("\n", $form_inspeksi_row["rekomendasi"]);
                        $rekomendasi_text = implode("<br>", $rekomendasi);
                        echo "<tr><td>Rekomendasi</td><td>" . $rekomendasi_text . "</td></tr>";
                        echo "</table>";
                        echo "</table>";
                        echo "</div>";
                        echo "<br>";

                        $count++;
                    }
                } else {
                    echo "Data Form Inspeksi tidak ditemukan.";
                }
            } else {
                echo "Terjadi kesalahan saat menjalankan query Form Inspeksi.";
            }
            ?>
        </div>
    </div>
    </div>
</div>
</body>
</html>
