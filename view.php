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
    <title>Assessment for Laptop Replacement</title>
    <link rel="stylesheet" href="css/styleview.css">
    <link rel="icon" type="image/png" href="./favicon_io/iconfav.png">
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
    <div style="display: grid; place-content: center;">
        <h1 style="justify-self: center;">Assessment Penggantian Laptop</h1>
    </div>
    <?php 
        $host = "mandiricoal.net";
        $user = "podema"; 
        $pass = "Jam10pagi#"; 
        $db = "podema";   
        
    $query = mysqli_fetch_array($conn->query("SELECT * FROM assess_laptop ORDER BY id DESC LIMIT 1"));
    ?>
        <h1>Score Hasil Assessment: <?= $query['score'] ?></h1>
        <br>
        <label for="date"><strong>Tanggal Pemeriksaan</strong></label>
        <p id="date" name="date" style="height: 13%;" required><?= $query['date'] ?></p>
        <br>
        <label for="name"><strong>Nama Pengguna</strong></label>
        <p id="name" name="name" style="height: 13%;" required><?= $query['name'] ?></p>
        <br>
        <label for="company"><strong>Perusahaan</strong></label>
        <p id="company" name="company" style="height: 13%;" required><?= $query['company'] ?></p>
        <br>
        <label for="divisi"><strong>Divisi</strong></label>
        <p id="divisi" name="divisi" style="height: 13%;" required><?= $query['divisi'] ?></p>
        <br>
        <label for="type"><strong>Tipe/Merk</strong></label>
        <p id="type" name="type" style="height: 13%;" required><?= $query['type'] ?></p>
        <br>
        <label for="serialnumber"><strong>Serial Number</strong></label>
        <p id="serialnumber" name="serialnumber" style="height: 13%;"><?= $query['serialnumber'] ?></p>
        <br>

    <?php
    if (isset($_POST['back'])) {
        header('Location: assess_laptop.php');
        exit();
    }
    ?>
            
    <?php if ($query['score'] > 99): ?>
        <p style="font-weight: bold; text-decoration: underline; font-style: italic; animation: blinking 2s infinite;">Perangkat Anda sudah seharusnya dilakukan penggantian.</p>
    <?php else: ?>
        <p style="font-weight: bold; text-decoration: underline; font-style: italic; animation: blinking 2s infinite;">Perangkat Anda masih layak pakai, namun tim IT akan melakukan upgrade pada perangkatmu jika dibutuhkan.</p>
    <?php endif; ?>
           
    <div class="button-container">
        <input type="button" value="Back" onclick="window.location.href='assess_laptop.php';">
        <a href="download.php" download><input type="button" value="Download"></a>
    </div>
    </form>
    <h2 id="result"></h2>
</body>
</html>
