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
    <link rel="stylesheet" type="text/css" href="css/stylelaptop.css">
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
    <h1 style="justify-self: center;">Evaluasi Penggantian Laptop</h1>
</div>
<form id="assessmentForm" method="post" action="submit.php" class="content" enctype="multipart/form-data">
        <div style="display: flex; flex-wrap: wrap; gap: 30px;">
            <div style="flex: 1;">
                <label for="name">Nama Pengguna<span style="color: crimson;">*</span></label>
                <?php
                    require_once 'config.php';

                    $result = mysqli_query($conn, "SELECT * FROM users ORDER BY name ASC");
                    if ($result) {
                        echo '<select id="name" name="name" style="height: 40px; width: 83.5%;" required>';
                        echo '<option value="">--- Pilih ---</option>';
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo '<option value="' . $row['name'] . '">' . $row['name'] . '</option>';
                        }
                        echo '</select>';
                        mysqli_free_result($result);
                    }

                    mysqli_close($conn);
                ?>
                <br>
                <label for="company">Perusahaan</label>
                <input type="text" id="company" name="company" style="height: 20px; width: 80%;" readonly>
                <br>
                <label for="divisi">Divisi</label>
                <input type="text" id="divisi" name="divisi" style="height: 20px; width: 80%;" readonly>
            </div>
            <div style="flex: 1;">
                <label for="date">Tanggal Pemeriksaan<span style="color: crimson;">*</span></label>
                <input type="date" id="date" name="date" style="height: 35px; width: 83%; font-family: Arial, sans-serif; font-size: 13.5px;" required>
                <br>
                <label for="type">Merk/Tipe Perangkat<span style="color: crimson;">*</span></label>
                <input type="text" id="type" name="type" style="height: 20px; width: 80%;">
                <br>
                <label for="serialnumber">Nomor Serial</label>
                <input type="text" id="serialnumber" name="serialnumber" style="height: 20px; width: 80%;">
                <br>
            </div>
            <script src="js/disabled-ep.js"></script>
        </div>
        <br>
        <label for="os">Sistem Operasi<span style="color: crimson;">*</span></label>
        <select id="os" name="os" style="height: 40px;" required>
            <option value="">--- Pilih ---</option>
            <?php
            
            require_once 'config.php';

            $result = mysqli_query($conn, "SELECT * FROM operating_sistem_laptop");
            if ($result) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<option value='" . $row['os_score'] . "'>" . $row['os_name'] . "</option>";
                }
                mysqli_free_result($result);
            }

            mysqli_close($conn);
            ?>
        </select>
        <br>
        <label for="processor">Processor<span style="color: crimson;">*</span></label>
        <select id="processor" name="processor" style="height: 40px;" required>
            <option value="">--- Pilih ---</option>
            <?php

            require_once 'config.php';

            $result = mysqli_query($conn, "SELECT * FROM processor_laptop");
            if ($result) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<option value='" . $row['processor_score'] . "'>" . $row['processor_name'] . "</option>";
                }
                mysqli_free_result($result);
            }

            mysqli_close($conn);
            ?>
        </select>
        <br>
        <label for="batterylife">Ketahanan Baterai (Tanpa Daya)<span style="color: crimson;">*</span></label>
        <select id="batterylife" name="batterylife" style="height: 40px;" required>
            <option value="">--- Pilih ---</option>
            <?php
            
            require_once 'config.php';

            $result = mysqli_query($conn, "SELECT * FROM batterylife_laptop");
            if ($result) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<option value='" . $row['battery_score'] . "'>" . $row['battery_name'] . "</option>";
                }
                mysqli_free_result($result);
            }

            mysqli_close($conn);
            ?>
        </select>
        <br>
        <label for="age">Usia Perangkat<span style="color: crimson;">*</span></label>
        <select id="age" name="age" style="height: 40px;" required>
            <option value="">--- Pilih ---</option>
            <?php
                        
            require_once 'config.php';

            $result = mysqli_query($conn, "SELECT * FROM device_age_laptop");
            if ($result) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<option value='" . $row['age_score'] . "'>" . $row['age_name'] . "</option>";
                }
                mysqli_free_result($result);
            }

            mysqli_close($conn);
            ?>
        </select>  
        <br>
        <label for="issue">Isu Terkait Software<span style="color: crimson;">*</span></label>
        <select id="issue" name="issue" style="height: 40px;" required>
            <option value="">--- Pilih ---</option>
            <?php
                        
            require_once 'config.php';

            $result = mysqli_query($conn, "SELECT * FROM issue_software_laptop");
            if ($result) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<option value='" . $row['issue_score'] . "'>" . $row['issue_name'] . "</option>";
                }
                mysqli_free_result($result);
            }

            mysqli_close($conn);
            ?>
        </select>  
        <br>
        <label for="ram">RAM<span style="color: crimson;">*</span></label>
        <select id="ram" name="ram" style="height: 40px;" required>
            <option value="">--- Pilih ---</option>
            <?php

            require_once 'config.php';

            $result = mysqli_query($conn, "SELECT * FROM ram_laptop");
            if ($result) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<option value='" . $row['ram_score'] . "'>" . $row['ram_name'] . "</option>";
                }
                mysqli_free_result($result);
            }

            mysqli_close($conn);
            ?>
        </select>  
        <br>
        <label for="storage">Penyimpanan<span style="color: crimson;">*</span></label>
        <select id="storage" name="storage" style="height: 40px;" required>
            <option value="">--- Select ---</option>
            <?php
            
            require_once 'config.php';

            $result = mysqli_query($conn, "SELECT * FROM storage_laptop");
            if ($result) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<option value='" . $row['storage_score'] . "'>" . $row['storage_name'] . "</option>";
                }
                mysqli_free_result($result);
            }

            mysqli_close($conn);
            ?>
        </select> 
        <br>
        <label for="keyboard">Keyboard<span style="color: crimson;">*</span></label>
        <select id="keyboard" name="keyboard" style="height: 40px;" required>
            <option value="">--- Pilih ---</option>
            <?php
            
            require_once 'config.php';

            $result = mysqli_query($conn, "SELECT * FROM keyboard_laptop");
            if ($result) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<option value='" . $row['keyboard_score'] . "'>" . $row['keyboard_name'] . "</option>";
                }
                mysqli_free_result($result);
            }

            mysqli_close($conn);
            ?>
        </select>
        <br>
        <label for="screen">Layar<span style="color: crimson;">*</span></label>
        <select id="screen" name="screen" style="height: 40px;" required>
            <option value="">--- Pilih ---</option>
            <?php
            
            require_once 'config.php';

            $result = mysqli_query($conn, "SELECT * FROM screen_laptop");
            if ($result) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<option value='" . $row['screen_score'] . "'>" . $row['screen_name'] . "</option>";
                }
                mysqli_free_result($result);
            }

            mysqli_close($conn);
            ?>
        </select>
        <br>
        <label for="touchpad">Touchpad<span style="color: crimson;">*</span></label>
        <select id="touchpad" name="touchpad" style="height: 40px;" required>
            <option value="">--- Pilih ---</option>
            <?php
            
            require_once 'config.php';

            $result = mysqli_query($conn, "SELECT * FROM touchpad_laptop");
            if ($result) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<option value='" . $row['touchpad_score'] . "'>" . $row['touchpad_name'] . "</option>";
                }
                mysqli_free_result($result);
            }

            mysqli_close($conn);
            ?>
        </select>
        <br>
        <label for="audio">Audio<span style="color: crimson;">*</span></label>
        <select id="audio" name="audio" style="height: 40px;" required>
            <option value="">--- Pilih ---</option>
            <?php

            require_once 'config.php';

            $result = mysqli_query($conn, "SELECT * FROM audio_laptop");
            if ($result) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<option value='" . $row['audio_score'] . "'>" . $row['audio_name'] . "</option>";
                }
                mysqli_free_result($result);
            }

            mysqli_close($conn);
            ?>
        </select>
        <br>
        <label for="body">Rangka<span style="color: crimson;">*</span></label>
        <select id="body" name="body" style="height: 40px;" required>
            <option value="">--- Pilih ---</option>
            <?php
            
            require_once 'config.php';

            $result = mysqli_query($conn, "SELECT * FROM body_laptop");
            if ($result) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<option value='" . $row['body_score'] . "'>" . $row['body_name'] . "</option>";
                }
                mysqli_free_result($result);
            }

            mysqli_close($conn);
            ?>
        </select>
        <br>
        <label for="upload_file" style="margin-bottom: 10px;">Unggah File<span style="color: crimson;">*</span></label></label>
            <input type="file" id="upload_file" name="upload_file" style="height: 40px; width: 80%;" accept=".zip, .rar" required>
            <small style="display: block;">*Note: <br> Sebagai bahan verifikasi mohon upload file berformat .zip atau .rar dari hasil Belarc, <br>dan file tidak lebih dari 100 KB</small>
        <br>
        <input type="submit" value="Submit">
        <input type="reset" value="Reset">
    </form>
</body>
</html>
