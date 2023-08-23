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
    <link rel="stylesheet" type="text/css" href="css/tc_css.css">
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
        <h1 style="justify-self: center;">Assessment for Laptop Replacement</h1>
    </div>
    <div class="terms-popup" id="termsPopup">
    <div class="terms-content">
    <h2>Syarat & Ketentuan</h2>
        <p>Form Asessment laptop ini bertujuan sebagai salah satu wujud tim ITE dalam menjaga performa, pencegahan kerusakan, keamanan data, pembaruan perangkat
            dan memungkinkan untuk memperpanjang usia perangkat kerja karyawan dilingkunganan Mandirigroup. 
            Serta sebagai salah satu syarat dalam penggantian laptop dengan merujuk dari hasil score assessment, Jika score diatas 100 maka divisi ITE wajib
            melakukan penggantian laptop untuk keberlangsungan proses kerja karyawan. Jika score dibawah 100 maka divisi ITE akan melakukan upgarde atau optimalisasi laptop</p>
        <p><b>Penggunaan Form Assessment:</b></p>
        <ol>
            <li>Pengguna dapat melakukan assessment dengan self-service dan pengguna diwajibkan mengisi semua bagian dalam form assessment laptop dengan informasi yang akurat dan lengkap.</li>
            <li>Setiap pengisian form assessment laptop harus dilakukan dengan itikad baik dan tidak boleh memasukkan informasi yang menyesatkan atau tidak benar.</li>
        </ol>
        <p><b>Tanggung Jawab Pengguna:</b></p>
        <ol>
            <li>Pengguna bertanggung jawab sepenuhnya atas kebenaran dan keakuratan informasi yang diberikan dalam form inspeksi bagian informasi keluhan.</li>
            <li>Pengguna tidak diperkenankan menggunakan form assessment laptop untuk tujuan penggantian perangkat kerja tanpa adanya rekomendasi dari divisi ITE</li>
        </ol>
        <p><b>Kebijakan Privasi:</b></p>
        <ol>
            <li>Informasi yang diberikan dalam form assessment laptop akan dijaga kerahasiaannya sesuai dengan kebijakan privasi yang berlaku di divisi ITE.</li>
            <li>Informasi yang dikumpulkan melalui form assessment laptop hanya akan digunakan untuk keperluan penilaian penggantian atau upgrade perangkat kerja, 
                dan tidak akan dibagikan kepada manapun yang tidak berkaitan oleh divisi ITE.</li>
        </ol>
        <p><b>Penilaian dan Penggantian:</b></p>
        <ol>
            <li>Berdasarkan hasil assessment laptop yang diperoleh melalui form berikut, penggantian perangkat akan dilakukan jika score yang didapatkan adalah diatas 100.</li>
            <li>Jika score yang didapatkan berada dibawah 100, maka divisi ITE akan melakukan optimalisasi perangkat, baik itu upgrade/penggantian part.</li>
        </ol>
        <p><b>Perubahan Syarat dan Ketentuan:</b></p>
        <ol>
            <li>Divisi ITE berhak untuk mengubah atau memperbarui syarat dan ketentuan ini sesuai dengan kebijakan perusahaan.</li>
            <li>Setiap perubahan dalam syarat dan ketentuan akan segera berlaku setelah diberlakukan dan pengguna dianggap menyetujui perubahan tersebut dengan melanjutkan penggunaan form inspeksi perangkat.</li>
        </ol>

        <div class="checkbox-container">
            <input type="checkbox" name="agree" id="agreeCheckbox" required>
            <label for="agreeCheckbox">Saya setuju dengan syarat dan ketentuan yang tercantum di atas</label>
        </div>
        <form id="assessmentForm" action="assess_laptop.php" method="POST">
            <button type="button" onclick="submitForm()">Mulai Assessment</button>
        </form>
    </div>
</div>
<script>
        function showTermsPopup() {
            document.getElementById("termsPopup").style.display = "block";
        }

        function closeTermsPopup() {
            document.getElementById("termsPopup").style.display = "none";
        }

        function submitForm() {
            if (document.getElementById("agreeCheckbox").checked) {
                document.getElementById("assessmentForm").submit();
            } else {
                alert("Please agree to the terms and conditions before proceeding.");
            }
        }

        window.onload = function() {
            showTermsPopup();

            document.getElementById("assessmentForm").addEventListener("submit", function(event) {
                event.preventDefault();

                if (document.getElementById("agreeCheckbox").checked) {
                    document.getElementById("termsPopup").style.display = "none";
                    document.getElementById("assessmentForm").submit();
                } else {
                    alert("Please agree to the terms and conditions before proceeding.");
                }
            });
        };
    </script>
    <form id="assessmentForm" method="post" action="submit.php" class="content" enctype="multipart/form-data">
        <div style="display: flex; flex-wrap: wrap; gap: 30px;">
            <div style="flex: 1;">
                <label for="name">Name<span style="color: crimson;">*</span></label>
                <input type="text" id="name" name="name" style="height: 20px; width: 80%;" required>
                <br>
                <label for="company">Company<span style="color: crimson;">*</span></label>
                <input type="text" id="company" name="company" style="height: 20px; width: 80%;" required>
                <br>
                <label for="divisi">Department<span style="color: crimson;">*</span></label>
                <input type="text" id="divisi" name="divisi" style="height: 20px; width: 80%;" required>
            </div>
            <div style="flex: 1;">
                <label for="type">Type/Merk<span style="color: crimson;">*</span></label>
                <input type="text" id="type" name="type" style="height: 20px; width: 80%;" required>
                <br>
                <label for="serialnumber">Serial Number<span style="color: crimson;">*</span></label>
                <input type="text" id="serialnumber" name="serialnumber" style="height: 20px; width: 80%;">
                <br>
            </div>
        </div>        
        <br>
        <label for="os">Operating System<span style="color: crimson;">*</span></label>
        <select id="os" name="os" style="height: 40px;" required>
            <option value="">--- Select ---</option>
            <?php
            $conn = mysqli_connect("localhost", "root", "", "podema");

            if (!$conn) {
                die("Koneksi database gagal: " . mysqli_connect_error());
            }

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
            <option value="">--- Select ---</option>
            <?php
            $conn = mysqli_connect("localhost", "root", "", "podema");

            if (!$conn) {
                die("Koneksi database gagal: " . mysqli_connect_error());
            }

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
        <label for="batterylife">Battery Life (without power)<span style="color: crimson;">*</span></label>
        <select id="batterylife" name="batterylife" style="height: 40px;" required>
            <option value="">--- Select ---</option>
            <?php
            $conn = mysqli_connect("localhost", "root", "", "podema");

            if (!$conn) {
                die("Koneksi database gagal: " . mysqli_connect_error());
            }

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
        <label for="age">Device Age<span style="color: crimson;">*</span></label>
        <select id="age" name="age" style="height: 40px;" required>
            <option value="">--- Select ---</option>
            <?php
            $conn = mysqli_connect("localhost", "root", "", "podema");

            if (!$conn) {
                die("Koneksi database gagal: " . mysqli_connect_error());
            }

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
        <label for="issue">Issue Related to Software<span style="color: crimson;">*</span></label>
        <select id="issue" name="issue" style="height: 40px;" required>
            <option value="">--- Select ---</option>
            <?php
            $conn = mysqli_connect("localhost", "root", "", "podema");

            if (!$conn) {
                die("Koneksi database gagal: " . mysqli_connect_error());
            }

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
            <option value="">--- Select ---</option>
            <?php
            $conn = mysqli_connect("localhost", "root", "", "podema");

            if (!$conn) {
                die("Koneksi database gagal: " . mysqli_connect_error());
            }

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
        <label for="storage">Storage<span style="color: crimson;">*</span></label>
        <select id="storage" name="storage" style="height: 40px;" required>
            <option value="">--- Select ---</option>
            <?php
            $conn = mysqli_connect("localhost", "root", "", "podema");

            if (!$conn) {
                die("Koneksi database gagal: " . mysqli_connect_error());
            }

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
            <option value="">--- Select ---</option>
            <?php
            $conn = mysqli_connect("localhost", "root", "", "podema");

            if (!$conn) {
                die("Koneksi database gagal: " . mysqli_connect_error());
            }

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
        <label for="screen">Screen<span style="color: crimson;">*</span></label>
        <select id="screen" name="screen" style="height: 40px;" required>
            <option value="">--- Select ---</option>
            <?php
            $conn = mysqli_connect("localhost", "root", "", "podema");

            if (!$conn) {
                die("Koneksi database gagal: " . mysqli_connect_error());
            }

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
            <option value="">--- Select ---</option>
            <?php
            $conn = mysqli_connect("localhost", "root", "", "podema");

            if (!$conn) {
                die("Koneksi database gagal: " . mysqli_connect_error());
            }

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
            <option value="">--- Select ---</option>
            <?php
            $conn = mysqli_connect("localhost", "root", "", "podema");

            if (!$conn) {
                die("Koneksi database gagal: " . mysqli_connect_error());
            }

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
        <label for="body">Body<span style="color: crimson;">*</span></label>
        <select id="body" name="body" style="height: 40px;" required>
            <option value="">--- Select ---</option>
            <?php
            $conn = mysqli_connect("localhost", "root", "", "podema");

            if (!$conn) {
                die("Koneksi database gagal: " . mysqli_connect_error());
            }

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
        <label for="upload_file" style="margin-bottom: 10px;">Upload File<span style="color: crimson;">*</span></label>
        <input type="file" id="upload_file" name="upload_file" style="height: 40px; width: 80%;" accept=".zip, .rar" required>
        <small style="display: block;">*Note: <br> Sebagai bahan verifikasi mohon upload file berformat .zip atau .rar dari hasil Belarc, <br>dan file tidak lebih dari 100 KB</small>
        <br>
        <input type="submit" value="Submit">
        <input type="reset" value="Reset">
    </form>
</body>
</html>