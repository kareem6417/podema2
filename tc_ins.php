<?php
session_start();

if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
    header("location: ./admin/login.php");
    exit();
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Inspection Devices</title>
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
        <h1 style="justify-self: center;">Inspection Devices</h1>
    </div>
    <div class="terms-popup" id="termsPopup">
    <div class="terms-content">
    <h2>Syarat & Ketentuan</h2>
        <p>Form inspeksi Perangkat ini bertujuan sebagai salah satu wujud tim ITE dalam menjaga performa, pencegahan kerusakan, keamanan data, pembaruan perangkat
            dan memungkinkan untuk memperpanjang usia perangkat kerja dilingukan Mandirigroup. 
            Serta sebagai salah satu syarat dalam penggantian perangkat kerja.</p>
        <p><b>Penggunaan Form Inspeksi Perangkat:</b></p>
        <ol>
            <li>Pengguna harus menginformasikan mengenai keluhan yang terjadi pada perangkat kerjanya dengan informasi yang akurat dan lengkap.</li>
            <li>Setiap pengisian form inspeksi harus dilakukan dengan itikad baik dan tidak boleh memasukkan informasi yang menyesatkan atau tidak benar.</li>
            <li>Divisi ITE akan memberikan rekomendasi langsung pada form inspeksi perangkat berdasarkan informasi keluhan serta hasil pemeriksaannya. </li>
        </ol>
        <p><b>Tanggung Jawab Pengguna:</b></p>
        <ol>
            <li>Pengguna bertanggung jawab sepenuhnya atas kebenaran dan keakuratan informasi yang diberikan dalam form inspeksi bagian informasi keluhan.</li>
            <li>Pengguna tidak diperkenankan menggunakan form inspeksi untuk tujuan penggantian perangkat kerja tanpa adanya rekomendasi dari divisi ITE</li>
        </ol>
        <p><b>Kebijakan Privasi:</b></p>
        <ol>
            <li>Informasi yang diberikan dalam form inspeksi akan dijaga kerahasiaannya sesuai dengan kebijakan privasi yang berlaku di divisi ITE.</li>
            <li>Informasi yang dikumpulkan melalui form inspeksi hanya akan digunakan untuk keperluan penilaian penggantian atau upgrade perangkat kerja, 
                dan tidak akan dibagikan kepada manapun yang tidak berkaitan oleh divisi ITE.</li>
        </ol>
        <p><b>Penilaian dan Penggantian:</b></p>
        <ol>
            <li>Berdasarkan hasil inspeksi perangkat yang diperoleh melalui form berikut, penggantian perangkat akan dilakukan jika ada rekomendasi penggantian perangkat dari divisi ITE.</li>
            <li>Jika rekomendasi dari divisi ITE tidak menyertakan penggantian perangkat, namun menyertakan upgrade perangkat maka divisi ITE bertanggung jawab untuk melakukan upgrade perangkat.</li>
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
        <form id="assessmentForm" action="inspeksi.php" method="POST">
            <button type="button" onclick="submitForm()">Mulai Inspeksi</button>
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
        <form id="assessmentForm" method="post" action="submit_inspeksi.php" class="content" enctype="multipart/form-data">

            <div style="display: flex; flex-wrap: wrap; gap: 30px;">
                <div style="flex: 1;">
                    <label for="date">Date<span style="color: crimson;">*</span></label>
                    <input type="date" id="date" name="date" style="height: 25px; width: 80%; font-family: Arial, sans-serif; font-size: 13.5px;" required>
                    <br>
                    <label for="jenis">Device Type<span style="color: crimson;">*</span></label>
                    <select id="jenis" name="jenis" style="height: 35px; width: 83%;" required>
                        <option value="">- Select Device Type -</option>
                        <option value="laptop">Laptop</option>
                        <option value="pc">PC Desktop</option>
                        <option value="monitor">Monitor</option>
                        <option value="printer">Printer</option>
                    </select>
                    <br>
                    <label for="merk">Brand<span style="color: crimson;">*</span></label>
                    <input type="text" id="merk" name="merk" style="height: 20px; width: 80%;" required>
                    <br>
                    <label for="lokasi">Location/Work Area of Device Usage<span style="color: crimson;">*</span></label>
                    <input type="text" id="lokasi" name="lokasi" style="height: 20px; width: 188%;">
                </div>
                <div style="flex: 1;">
                    <label for="nama_user">Name<span style="color: crimson;">*</span></label>
                    <input type="text" id="nama_user" name="nama_user" style="height: 20px; width: 80%;" required>
                    <br>
                    <label for="status">Position/Department<span style="color: crimson;">*</span></label>
                    <input type="text" id="status" name="status" style="height: 20px; width: 80%;" required>
                    <br>
                    <label for="serialnumber">Serial Number<span style="color: crimson;">*</span></label>
                    <input type="text" id="serialnumber" name="serialnumber" style="height: 20px; width: 80%;" required>
                </div>
            </div>        
            <label for="upload_file" style="margin-bottom: 10px;">Upload File<span style="color: crimson;">*</span></label>
            <input type="file" id="upload_file" name="upload_file" style="height: 40px; width: 80%;" accept=".zip, .rar" required>
            <small style="display: block;">*Note: <br> Sebagai bahan verifikasi mohon upload file berformat .zip atau .rar dari hasil Belarc, <br>dan file tidak lebih dari 100 KB</small>
            <br>
            <input type="submit" value="Submit">
            <input type="reset" value="Reset">
        </form>
    </div>
</body>
</html>
