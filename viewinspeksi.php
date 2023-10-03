<?php
session_start();

if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
    header("location: ./admin/login.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>View Inspeksi</title>
    <link rel="icon" type="image/png" href="./favicon_io/iconfav.png">
    <link rel="stylesheet" href="css/view-ins.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
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
        <div class="content">
        <h2>Ringkasan Hasil Inspeksi Perangkat Anda</h2>
        <br>
    <?php
    
    $host = "mandiricoal.net";
    $user = "podema"; 
    $pass = "Jam10pagi#"; 
    $db = "podema"; 
    
    $conn = new mysqli($host, $user, $pass, $db);
    if ($conn->connect_error) {
        die("Koneksi database gagal: " . $conn->connect_error);
    }
    
    if(isset($_POST['jenis_perangkat'])){
        $jenis_perangkat = $_POST['jenis_perangkat'];
        
        $sql = "SELECT * FROM form_inspeksi WHERE jenis = '$jenis_perangkat' ORDER BY no DESC LIMIT 1";
        $result = $conn->query($sql);
    
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();

            echo "<div class='flex-container'>";
            echo "<div class='column'>";
            echo "<div class='row'><span class='label'>Nomor</span><span class='value'>" . $row["no"] . "</span></div>";
            echo "<div class='row'><span class='label'>Tipe Perangkat</span><span class='value'>" . $row["jenis"] . "</span></div>";
            echo "<div class='row'><span class='label'>Merk</span><span class='value'>" . $row["merk"] . "</span></div>";
            echo "<div class='row'><span class='label'>Nomor Serial</span><span class='value'>" . $row["serialnumber"] . "</span></div>";
            echo "</div>";
            
            echo "<div class='column'>";
            echo "<div class='row'><span class='label'>Tanggal</span><span class='value'>" . $row["date"] . "</span></div>";
            echo "<div class='row'><span class='label'>Nama</span><span class='value'>" . $row["nama_user"] . "</span></div>";
            echo "<div class='row'><span class='label'>Posisi/Divisi</span><span class='value'>" . $row["status"] . "</span></div>";
            echo "<div class='row'><span class='label'>Lokasi/Area Kerja Perangkat</span><span class='value'>" . $row["lokasi"] . "</span></div>";
            echo "</div>";
            
            echo "</div>";
        } else {
            echo "Tidak ada data yang ditemukan.";
        }
    } else {
        echo "Tidak ada data yang ditemukan.";
    }
    
    $conn->close();
    ?>

    <br>
    <h4><span class="blink" style="font-weight: bold; text-decoration: underline; font-style: italic;">Click "Download" to view the details.</span></h4>
    
    <div class="button-container">
        <input type="button" value="Back" onclick="window.location.href='inspeksi.php';">
        <a href="downloadinspeksi.php" download><input type="button" value="Download"></a>
    </div>
</div>
</body>
</html>
