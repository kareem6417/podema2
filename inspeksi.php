<?php
session_start();

if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
    header("location: ./admin/login.php");
    exit();
}

$conn_podema = mysqli_connect("mandiricoal.net", "podema", "Jam10pagi#", "podema");

if (!$conn_podema) {
    die("Koneksi database podema gagal: " . mysqli_connect_error());
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Inspection Devices</title>
    <link rel="stylesheet" type="text/css" href="css/styleins.css">
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
            <h1 style="justify-self: center;">Inspeksi Perangkat Kerja</h1>
        </div>
        <form id="assessmentForm" method="post" action="submit_inspeksi.php" class="content" enctype="multipart/form-data">
            <div style="display: flex; flex-wrap: wrap; gap: 30px;">
                <div style="flex: 1;">
                    <label for="date">Tanggal Pemeriksaan<span style="color: crimson;">*</span></label>
                    <input type="date" id="date" name="date" style="height: 25px; width: 83%; font-family: Arial, sans-serif; font-size: 13.5px;" required>
                    <br>
                    <label for="jenis">Jenis Perangkat<span style="color: crimson;">*</span></label>
                    <select id="jenis" name="jenis" style="height: 35px; width: 84%;" required>
                        <option value="">- Pilih Perangkat -</option>
                        <option value="Laptop">Laptop</option>
                        <option value="PC Desktop">PC Desktop</option>
                        <option value="Monitor">Monitor</option>
                        <option value="Printer">Printer</option>
                    </select>
                    <br>
                    <label for="merk">Merk Perangkat<span style="color: crimson;">*</span></label>
                    <input type="text" id="merk" name="merk" style="height: 20px; width: 80%;" required>
                    <br>
                    <label for="lokasi">Lokasi/Area Kerja Penggunaan Perangkat<span style="color: crimson;">*</span></label>
                    <input type="text" id="lokasi" name="lokasi" style="height: 20px; width: 188%;" readonly>
                </div>
                <div style="flex: 1;">
                    <label for="nama_user">Nama Pengguna<span style="color: crimson;">*</span></label>
                    <?php
                    
                        $conn_podema = mysqli_connect("mandiricoal.net", "podema", "Jam10pagi#", "podema");

                        if (!$conn_podema) {
                            die("Koneksi database podema gagal: " . mysqli_connect_error());
                        }

                        $result = mysqli_query($conn_podema, "SELECT * FROM users ORDER BY name ASC");
                        if ($result) {
                            echo '<select id="name" name="nama_user" style="height: 38px; width: 84%;" required>';
                            echo '<option value="">--- Pilih ---</option>';
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo '<option value="' . $row['name'] . '">' . $row['name'] . '</option>';
                            }
                            echo '</select>';
                            mysqli_free_result($result);
                        }
                    ?>
                    <br>
                    <label for="status">Posisi/Divisi<span style="color: crimson;">*</span></label>
                    <input type="text" id="status" name="status" style="height: 20px; width: 80%;" readonly>
                    <br>
                    <label for="serialnumber">Nomor Serial<span style="color: crimson;">*</span></label>
                    <input type="text" id="serialnumber" name="serialnumber" style="height: 20px; width: 80%;" required>
                </div>
                <script>
                    document.getElementById('name').addEventListener('change', function() {
                        var selectedName = this.value;
                        var xhr = new XMLHttpRequest();
                        xhr.onreadystatechange = function() {
                            if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                                var response = JSON.parse(xhr.responseText);
                                document.getElementById('status').value = response.department;
                                document.getElementById('lokasi').value = response.company;
                            }
                        };
                        xhr.open('GET', 'get_userdata_ins.php?name=' + encodeURIComponent(selectedName));
                        xhr.send();
                    });
                </script>
            </div>        
            <br>
            <div id="infoDiv">
                <label for="informasi_keluhan">Informasi Keluhan/Permasalahan yang disampaikan:<span style="color: crimson;">*</span></label>
                <textarea id="informasi_keluhan" name="informasi_keluhan" style="height: 75px; width: 98%;" required></textarea>
            </div>
            <div id="casingDiv" style="display:none;">
            <label for="casing_lap">Casing<span style="color: crimson;">*</span></label>
                <select id="casing_lap" name="casing_lap" style="height: 35px; width: 84%;" required>
                    <option value="">--- Pilih ---</option>
                    <?php
                        $result = mysqli_query($conn_podema, "SELECT * FROM ins_casing_lap");
                        if ($result) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<option value='" . $row['casing_lap_score'] . "'>" . $row['casing_lap_name'] . "</option>";
                            }
                            mysqli_free_result($result);
                        }
                    ?>
                </select>
            </div>
            <div id="layarDiv" style="display:none;">
                <label for="layar_lap">Layar:<span style="color: crimson;">*</span></label>
                <select id="layar_lap" name="layar_lap" style="height: 40px; width:98%;" required>
                    <option value="">--- Pilih ---</option>
                    <?php
                        $result = mysqli_query($conn_podema, "SELECT * FROM ins_layar_lap");
                        if ($result) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<option value='" . $row['layar_lap_score'] . "'>" . $row['layar_lap_name'] . "</option>";
                            }
                            mysqli_free_result($result);
                        }
                    ?>
                </select>
            </div>
            <div id="engselDiv" style="display:none;">
                <label for="engsel_lap">Engsel:<span style="color: crimson;">*</span></label>
                <select id="engsel_lap" name="engsel_lap" style="height: 40px; width:98%;" required>
                    <option value="">--- Pilih ---</option>
                    <?php
                        $result = mysqli_query($conn_podema, "SELECT * FROM ins_engsel_lap");
                        if ($result) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<option value='" . $row['engsel_lap_score'] . "'>" . $row['engsel_lap_name'] . "</option>";
                            }
                            mysqli_free_result($result);
                        }
                    ?>
                </select>
            </div>
            <div id="keyboardDiv" style="display:none;">
                <label for="keyboard_lap">Keyboard:<span style="color: crimson;">*</span></label>
                <select id="keyboard_lap" name="keyboard_lap" style="height: 40px; width:98%;" required>
                    <option value="">--- Pilih ---</option>
                    <?php
                        $result = mysqli_query($conn_podema, "SELECT * FROM ins_keyboard_lap");
                        if ($result) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<option value='" . $row['keyboard_lap_score'] . "'>" . $row['keyboard_lap_name'] . "</option>";
                            }
                            mysqli_free_result($result);
                        }
                    ?>
                </select>
            </div>
            <div id="touchpadDiv" style="display:none;">
                <label for="touchpad_lap">Touchpad:<span style="color: crimson;">*</span></label>
                <select id="touchpad_lap" name="touchpad_lap" style="height: 40px; width:98%;" required>
                    <option value="">--- Pilih ---</option>
                    <?php
                        $result = mysqli_query($conn_podema, "SELECT * FROM ins_touchpad_lap");
                        if ($result) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<option value='" . $row['touchpad_lap_score'] . "'>" . $row['touchpad_lap_name'] . "</option>";
                            }
                            mysqli_free_result($result);
                        }
                    ?>
                </select>
            </div>
            <div id="bootingDiv" style="display:none;">
                <label for="booting_lap">Proses Booting:<span style="color: crimson;">*</span></label>
                <select id="booting_lap" name="booting_lap" style="height: 40px; width:98%;" required>
                    <option value="">--- Pilih ---</option>
                    <?php
                        $result = mysqli_query($conn_podema, "SELECT * FROM ins_booting_lap");
                        if ($result) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<option value='" . $row['booting_lap_score'] . "'>" . $row['booting_lap_name'] . "</option>";
                            }
                            mysqli_free_result($result);
                        }
                    ?>
                </select>
            </div>
            <div id="multiDiv" style="display:none;">
                <label for="multi_lap">Multitasking Apps:<span style="color: crimson;">*</span></label>
                <select id="multi_lap" name="multi_lap" style="height: 40px; width:98%;" required>
                    <option value="">--- Pilih ---</option>
                    <?php
                        $result = mysqli_query($conn_podema, "SELECT * FROM ins_multi_lap");
                        if ($result) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<option value='" . $row['multi_lap_score'] . "'>" . $row['multi_lap_name'] . "</option>";
                            }
                            mysqli_free_result($result);
                        }
                    ?>
                </select>
            </div>
            <div id="tampungDiv" style="display:none;">
                <label for="tampung_lap">Daya Baterai:<span style="color: crimson;">*</span></label>
                <select id="tampung_lap" name="tampung_lap" style="height: 40px; width:98%;" required>
                    <option value="">--- Pilih ---</option>
                    <?php
                        $result = mysqli_query($conn_podema, "SELECT * FROM ins_tampung_lap");
                        if ($result) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<option value='" . $row['tampung_lap_score'] . "'>" . $row['tampung_lap_name'] . "</option>";
                            }
                            mysqli_free_result($result);
                        }
                    ?>
                </select>
            </div>
            <div id="isiDiv" style="display:none;">
                <label for="isi_lap">Waktu Pengisian Baterai:<span style="color: crimson;">*</span></label>
                <select id="isi_lap" name="isi_lap" style="height: 40px; width:98%;" required>
                    <option value="">--- Pilih ---</option>
                    <?php
                        $result = mysqli_query($conn_podema, "SELECT * FROM ins_isi_lap");
                        if ($result) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<option value='" . $row['isi_lap_score'] . "'>" . $row['isi_lap_name'] . "</option>";
                            }
                            mysqli_free_result($result);
                        }
                    ?>
                </select>
            </div>
            <div id="portDiv" style="display:none;">
                <label for="port_lap">Port:<span style="color: crimson;">*</span></label>
                <select id="port_lap" name="port_lap" style="height: 40px; width:98%;" required>
                    <option value="">--- Pilih ---</option>
                    <?php
                        $result = mysqli_query($conn_podema, "SELECT * FROM ins_port_lap");
                        if ($result) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<option value='" . $row['port_lap_score'] . "'>" . $row['port_lap_name'] . "</option>";
                            }
                            mysqli_free_result($result);
                        }
                    ?>
                </select>
            </div>
            <div id="audioDiv" style="display:none;">
                <label for="audio_lap">Audio:<span style="color: crimson;">*</span></label>
                <select id="audio_lap" name="audio_lap" style="height: 40px; width:98%;" required>
                    <option value="">--- Pilih ---</option>
                    <?php
                        $result = mysqli_query($conn_podema, "SELECT * FROM ins_audio_lap");
                        if ($result) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<option value='" . $row['audio_lap_score'] . "'>" . $row['audio_lap_name'] . "</option>";
                            }
                            mysqli_free_result($result);
                        }
                    ?>
                </select>
            </div>
            <div id="softwareDiv" style="display:none;">
                <label for="software_lap">Software:<span style="color: crimson;">*</span></label>
                <select id="software_lap" name="software_lap" style="height: 40px; width:98%;" required>
                    <option value="">--- Pilih ---</option>
                    <?php
                        $result = mysqli_query($conn_podema, "SELECT * FROM ins_software_lap");
                        if ($result) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<option value='" . $row['software_lap_score'] . "'>" . $row['software_lap_name'] . "</option>";
                            }
                            mysqli_free_result($result);
                        }
                    ?>
                </select>
            </div>
            <div id="inkpadDiv" style="display:none;">
                <label for="ink_pad">Ink Pad:<span style="color: crimson;">*</span></label>
                <select id="ink_pad" name="ink_pad" style="height: 40px; width=98%" required>
                    <option value="">--- Pilih ---</option>
                    <?php
                        $result = mysqli_query($conn_podema, "SELECT * FROM ink_pad");
                        if ($result) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<option value='" . $row['ink_pad_score'] . "'>" . $row['ink_pad_name'] . "</option>";
                            }
                            mysqli_free_result($result);
                        }
                    ?>
                </select>
            </div>
            <script src="js/ins.js"></script>
            <div id="rekomDiv">
                <label for="rekomendasi">Rekomendasi:<span style="color: crimson;">*</span></label>
                <textarea id="rekomendasi" name="rekomendasi" style="height: 75px; width: 98%;" required></textarea>    
            </div>
            <br>
            <label for="upload_file" style="margin-bottom: 10px;">Unggah File<span style="color: crimson;">*</span></label></label>
            <input type="file" id="upload_file" name="upload_file" style="height: 40px; width: 80%;" accept=".zip, .rar" required>
            <small style="display: block;">*Note: <br> Sebagai bahan verifikasi mohon upload file berformat .zip atau .rar dari hasil Belarc, <br>dan file tidak lebih dari 100 KB</small>
            <br>
            <input type="submit" value="Submit">
            <input type="reset" value="Reset">
        </form>
    </div>
</body>
</html>
