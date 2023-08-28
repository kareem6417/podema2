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
    <title>Assessment for PC Desktop Replacement</title>
    <link rel="icon" type="image/png" href="./favicon_io/iconfav.png">
    <link rel="stylesheet" href="css/view-pc.css">
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
        <h1 style="justify-self: center;">Assessment for PC Desktop Replacement</h1>
    </div>
    <?php 

        $host = "mandiricoal.net";
        $user = "podema"; 
        $pass = "Jam10pagi#"; 
        $db = "podema"; 
        
        $conn = new mysqli($host, $user, $pass, $db);
        if ($conn->connect_error) {
            die("Koneksi database gagal: " . $conn->connect_error);
        }
        
        $query = mysqli_fetch_array($conn->query("SELECT * FROM assess_pc ORDER BY id DESC"));
    ?>
    <form id="assessmentForm" method="post" action="submitpc.php">
    <h1>Score Hasil Assessment: <?= $query['score'] ?></h1>
        <br>
        <label for="date"><strong>Tanggal Pemeriksaan</strong></label>
        <p id="date" name="date" style="height: 13%;" required><?= $query['date'] ?></p>
        <br>
        <label for="name"><strong>Name</strong></label>
        <p id="name" name="name" style="height: 13%;" required><?= $query['name']?></p>
        <br>
        <label for="company"><strong>Company</strong></label>
        <p id="company" name="company" style="height: 13%;" required><?= $query['company']?></p>
        <br>
        <label for="divisi"><strong>Department</strong></label>
        <p id="divisi" name="divisi" style="height: 13%;" required><?= $query['divisi']?></p>
        <br>
        <label for="serialnumber"><strong>Serial Number</strong></label>
        <p id="serialnumber" name="serialnumber" style="height: 13%;"><?= $query['serialnumber']?></p>
        <br>
        <label for="merk"><strong>Type/Merk</strong></label>
        <p id="merk" name="merk" style="height: 13%;"><?= $query['merk']?></p>
        <br>
        <label for="pctype_pc"><strong>PC Type</strong></label>
        <?php
            $pctype_id = $query['typepc'];
            $result = $conn->query("SELECT pctype_name FROM pctype_pc WHERE pctype_score = '$pctype_id'");
            if ($result->num_rows > 0) {
                $pctype_pc = $result->fetch_assoc();
                $pctype_name = $pctype_pc['pctype_name'];
            } else {
                $pctype_name = "Unknown";
            }
        ?>
        <p id="pctype_pc" name="pctype_pc" style="height: 13%;" required><?= $pctype_name ?></p>

        <?php
        if (isset($_POST['back'])) {
            header('Location: assess_pc.php');
            exit();
        }
        ?>
        <?php if ($query['score'] > 99): ?>
            <p style="font-weight: bold; text-decoration: underline; font-style: italic; animation: blinking 2s infinite;">Perangkat Anda sudah seharusnya dilakukan penggantian.</p>
        <?php else: ?>
            <p style="font-weight: bold; text-decoration: underline; font-style: italic; animation: blinking 2s infinite;">Perangkat Anda masih layak pakai, namun tim IT akan melakukan upgrade pada perangkatmu jika dibutuhkan.</p>
        <?php endif; ?>
       
        <div class="button-container">
            <input type="button" value="Back" onclick="window.location.href='assess_pc.php';">
            <a href="downloadpc.php" download><input type="button" value="Download"></a>
        </div>
    </form>
    <h2 id="result"></h2>
</body>
</html>
