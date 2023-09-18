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

function fetchData($table) {
    global $conn_podema;
    $data = array();
    $result = mysqli_query($conn_podema, "SELECT * FROM $table");
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
        mysqli_free_result($result);
    }
    return $data;
}

$userInfos = array();
$users = fetchData("users");
foreach ($users as $user) {
    $userInfos[$user['name']] = array(
        'company' => $user['company'],
        'divisi' => $user['department']
    );
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
            <label for="informasi_keluhan">Informasi Keluhan/Permasalahan yang disampaikan:<span style="color: crimson;">*</span></label>
            <textarea id="informasi_keluhan" name="informasi_keluhan" style="height: 75px; width: 98%;" required></textarea>
            <br>

            <label for="casing_lap">Casing<span style="color: crimson;">*</span></label>
            <select id="casing_lap" name="casing_lap" style="height: 35px; width: 98%;" required>
                <option value="">--- Pilih ---</option>
                <?php
                $casingOptions = fetchData("ins_casing_lap");
                foreach ($casingOptions as $casingOption) {
                    echo '<option value="' . $casingOption['casing_lap_score'] . '">' . $casingOption['casing_lap_name'] . '</option>';
                }
                ?>
            </select>
            <br>

            <label for="layar_lap">Layar<span style="color: crimson;">*</span></label>
            <select id="layar_lap" name="layar_lap" style="height: 35px; width: 98%;" required>
                <option value="">--- Pilih ---</option>
                <?php
                $layarOptions = fetchData("ins_layar_lap");
                foreach ($layarOptions as $layarOption) {
                    echo '<option value="' . $layarOption['layar_lap_score'] . '">' . $layarOption['layar_lap_name'] . '</option>';
                }
                ?>
            </select>
            <br>

            <label for="engsel_lap">Engsel<span style="color: crimson;">*</span></label>
            <select id="engsel_lap" name="engsel_lap" style="height: 35px; width: 98%;" required>
                <option value="">--- Pilih ---</option>
                <?php
                $engselOptions = fetchData("ins_engsel_lap");
                foreach ($engselOptions as $engselOption) {
                    echo '<option value="' . $engselOption['engsel_lap_score'] . '">' . $engselOption['engsel_lap_name'] . '</option>';
                }
                ?>
            </select>
            <br>

            <label for="keyboard_lap">Keyboard<span style="color: crimson;">*</span></label>
            <select id="keyboard_lap" name="keyboard_lap" style="height: 35px; width: 98%;" required>
                <option value="">--- Pilih ---</option>
                <?php
                $keyboardOptions = fetchData("ins_keyboard_lap");
                foreach ($keyboardOptions as $keyboardOption) {
                    echo '<option value="' . $keyboardOption['keyboard_lap_score'] . '">' . $keyboardOption['keyboard_lap_name'] . '</option>';
                }
                ?>
            </select>
            <br>

            <label for="touchpad_lap">Touchpad<span style="color: crimson;">*</span></label>
            <select id="touchpad_lap" name="touchpad_lap" style="height: 35px; width:98%;" required>
                <option value="">--- Pilih ---</option>
                <?php
                $touchpadOptions = fetchData("ins_touchpad_lap");
                foreach ($touchpadOptions as $touchpadOption) {
                    echo '<option value="' . $touchpadOption['touchpad_lap_score'] . '">' . $touchpadOption['touchpad_lap_name'] . '</option>';
                }
                ?>
            </select>
            <br>

            <label for="booting_lap">Proses Booting<span style="color: crimson;">*</span></label>
            <select id="booting_lap" name="booting_lap" style="height: 35px; width:98%;" required>
                <option value="">--- Pilih ---</option>
                <?php
                $bootingOptions = fetchData("ins_booting_lap");
                foreach ($bootingOptions as $bootingOption) {
                    echo '<option value="' . $bootingOption['booting_lap_score'] . '">' . $bootingOption['booting_lap_name'] . '</option>';
                }
                ?>
            </select>
            <br>

            <label for="multi_lap">Multitasking Apps<span style="color: crimson;">*</span></label>
            <select id="multi_lap" name="multi_lap" style="height: 35px; width:98%;" required>
                <option value="">--- Pilih ---</option>
                <?php
                $multiOptions = fetchData("ins_multi_lap");
                foreach ($multiOptions as $multiOption) {
                    echo '<option value="' . $multiOption['multi_lap_score'] . '">' . $multiOption['multi_lap_name'] . '</option>';
                }
                ?>
            </select>
            <br>

            <label for="tampung_lap">Kapasitas Baterai<span style="color: crimson;">*</span></label>
            <select id="tampung_lap" name="tampung_lap" style="height: 35px; width:98%;" required>
                <option value="">--- Pilih ---</option>
                <?php
                $tampungOptions = fetchData("ins_tampung_lap");
                foreach ($tampungOptions as $tampungOption) {
                    echo '<option value="' . $tampungOption['tampung_lap_score'] . '">' . $tampungOption['tampung_lap_name'] . '</option>';
                }
                ?>
            </select>
            <br>

            <label for="isi_lap">Waktu Pengisian Baterai<span style="color: crimson;">*</span></label>
            <select id="isi_lap" name="isi_lap" style="height: 35px; width:98%;" required>
                <option value="">--- Pilih ---</option>
                <?php
                $isiOptions = fetchData("ins_isi_lap");
                foreach ($isiOptions as $isiOption) {
                    echo '<option value="' . $isiOption['isi_lap_score'] . '">' . $isiOption['isi_lap_name'] . '</option>';
                }
                ?>
            </select>
            <br>

            <label for="port_lap">Port<span style="color: crimson;">*</span></label>
            <select id="port_lap" name="port_lap" style="height: 35px; width:98%;" required>
                <option value="">--- Pilih ---</option>
                <?php
                $portOptions = fetchData("ins_port_lap");
                foreach ($portOptions as $portOption) {
                    echo '<option value="' . $portOption['port_lap_score'] . '">' . $portOption['port_lap_name'] . '</option>';
                }
                ?>
            </select>
            <br>

            <label for="audio_lap">Audio<span style="color: crimson;">*</span></label>
            <select id="audio_lap" name="audio_lap" style="height: 35px; width:98%;" required>
                <option value="">--- Pilih ---</option>
                <?php
                $audioOptions = fetchData("ins_audio_lap");
                foreach ($audioOptions as $audioOption) {
                    echo '<option value="' . $audioOption['audio_lap_score'] . '">' . $audioOption['audio_lap_name'] . '</option>';
                }
                ?>
            </select>
            <br>

            <label for="ink_pad">Ink Pad<span style="color: crimson;">*</span></label>
            <select id="ink_pad" name="ink_pad" style="height: 35px; width:98%;" required>
                <option value="">--- Pilih ---</option>
                <?php
                $inkpadOptions = fetchData("ins_ink_pad");
                foreach ($inkpadOptions as $inkpadOption) {
                    echo '<option value="' . $inkpadOption['ink_pad_score'] . '">' . $inkpadOption['ink_pad_name'] . '</option>';
                }
                ?>
            </select>
            <br>

            <label for="hasil_pemeriksaan">Hasil Pemeriksaan Lainnya:<span style="color: crimson;">*</span></label>
            <textarea id="hasil_pemeriksaan" name="hasil_pemeriksaan" style="height: 75px; width: 98%;" required></textarea>
            <br>

            <label for="screenshot">Screenshot</label>
            <input type="file" id="screenshot" name="screenshot" accept="image/*">
            <img id="preview" src="#" alt="Preview" style="max-width: 200px; max-height: 200px; display: none;">
            <br>

            <script>
                document.getElementById('jenis').addEventListener('change', function() {
                    var jenisPerangkat = this.value;
                    var casingLabel = document.getElementById('casingLabel');
                    var casingSelect = document.getElementById('casing_lap');
                    var layarLabel = document.getElementById('layarLabel');
                    var layarSelect = document.getElementById('layar_lap');
                    var engselLabel = document.getElementById('engselLabel');
                    var engselSelect = document.getElementById('engsel_lap');
                    var keyboardLabel = document.getElementById('keyboardLabel');
                    var keyboardSelect = document.getElementById('keyboard_lap');
                    var touchpadLabel = document.getElementById('touchpadLabel');
                    var touchpadSelect = document.getElementById('touchpad_lap');
                    var bootingLabel = document.getElementById('bootingLabel');
                    var bootingSelect = document.getElementById('booting_lap');
                    var multiLabel = document.getElementById('multiLabel');
                    var multiSelect = document.getElementById('multi_lap');
                    var tampungLabel = document.getElementById('tampungLabel');
                    var tampungSelect = document.getElementById('tampung_lap');
                    var isiLabel = document.getElementById('isiLabel');
                    var isiSelect = document.getElementById('isi_lap');
                    var portLabel = document.getElementById('portLabel');
                    var portSelect = document.getElementById('port_lap');
                    var softwareLabel = document.getElementById('softwareLabel');
                    var softwareSelect = document.getElementById('software_lap');
                    var audioLabel = document.getElementById('audioLabel');
                    var audioSelect = document.getElementById('audio_lap');
                    var hasilLabel = document.getElementById('hasilLabel');
                    var hasilTextarea = document.getElementById('hasil_pemeriksaan');
                    var screenshotLabel = document.getElementById('screenshotLabel');
                    var screenshotInput = document.getElementById('screenshot');
                    var rekomendasiLabel = document.getElementById('rekomendasiLabel');
                    var rekomendasiTextarea = document.getElementById('rekomendasi');

                    if (jenisPerangkat === 'Laptop' || jenisPerangkat === 'PC Desktop') {
                        casingLabel.style.display = 'block';
                        casingSelect.style.display = 'block';
                        layarLabel.style.display = 'block';
                        layarSelect.style.display = 'block';
                        engselLabel.style.display = 'block';
                        engselSelect.style.display = 'block';
                        keyboardLabel.style.display = 'block';
                        keyboardSelect.style.display = 'block';
                        touchpadLabel.style.display = 'block';
                        touchpadSelect.style.display = 'block';
                        bootingLabel.style.display = 'block';
                        bootingSelect.style.display = 'block';
                        multiLabel.style.display = 'block';
                        multiSelect.style.display = 'block';
                        tampungLabel.style.display = 'block';
                        tampungSelect.style.display = 'block';
                        isiLabel.style.display = 'block';
                        isiSelect.style.display = 'block';
                        portLabel.style.display = 'block';
                        portSelect.style.display = 'block';
                        softwareLabel.style.display = 'block';
                        softwareSelect.style.display = 'block';
                        audioLabel.style.display = 'block';
                        audioSelect.style.display = 'block';
                        hasilLabel.style.display = 'block';
                        hasilTextarea.style.display = 'block';
                        screenshotLabel.style.display = 'block';
                        screenshotInput.style.display = 'block';
                        rekomendasiLabel.style.display = 'block';
                        rekomendasiTextarea.style.display = 'block';
                    } else {
                        casingLabel.style.display = 'none';
                        casingSelect.style.display = 'none';
                        layarLabel.style.display = 'none';
                        layarSelect.style.display = 'none';
                        engselLabel.style.display = 'none';
                        engselSelect.style.display = 'none';
                        keyboardLabel.style.display = 'none';
                        keyboardSelect.style.display = 'none';
                        touchpadLabel.style.display = 'none';
                        touchpadSelect.style.display = 'none';
                        bootingLabel.style.display = 'none';
                        bootingSelect.style.display = 'none';
                        multiLabel.style.display = 'none';
                        multiSelect.style.display = 'none';
                        tampungLabel.style.display = 'none';
                        tampungSelect.style.display = 'none';
                        isiLabel.style.display = 'none';
                        isiSelect.style.display = 'none';
                        portLabel.style.display = 'none';
                        portSelect.style.display = 'none';
                        softwareLabel.style.display = 'none';
                        softwareSelect.style.display = 'none';
                        audioLabel.style.display = 'none';
                        audioSelect.style.display = 'none';
                        hasilLabel.style.display = 'none';
                        hasilTextarea.style.display = 'none';
                        screenshotLabel.style.display = 'none';
                        screenshotInput.style.display = 'none';
                        rekomendasiLabel.style.display = 'none';
                        rekomendasiTextarea.style.display = 'none';
                    }
                });
            </script>


            <label for="rekomendasi">Rekomendasi:<span style="color: crimson;">*</span></label>
            <textarea id="rekomendasi" name="rekomendasi" style="height: 75px; width: 98%;" required></textarea>
            <br>
            <label for="upload_file" style="margin-bottom: 10px;">Upload File<span style="color: crimson;">*</span></label></label>
            <input type="file" id="upload_file" name="upload_file" style="height: 40px; width: 80%;" accept=".zip, .rar" required>
            <small style="display: block;">*Note: <br> Sebagai bahan verifikasi mohon upload file berformat .zip atau .rar dari hasil Belarc, <br>dan file tidak lebih dari 100 KB</small>
            <br>
            <input type="submit" value="Submit">
            <input type="reset" value="Reset">
        </form>
    </div>
</body>
</html>
