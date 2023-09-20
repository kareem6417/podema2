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
<html lang="en">

<head>
    <title>Inspection Devices</title>
    <link rel="stylesheet" type="text/css" href="css/styleins.css">
    <link rel="icon" type="image/png" href="./favicon_io/iconfav.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
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
                    <input type="date" id="date" name="date" style="height: 35px; width: 84%; font-family: Arial, sans-serif; font-size: 13.5px;" required>
                    <br>
                    <label for="jenis">Jenis Perangkat<span style="color: crimson;">*</span></label>
                    <select id="jenis" name="jenis" style="height: 35px; width: 84%;" required>
                        <option value="Perangkat">- Pilih Perangkat -</option>
                        <option value="Laptop">Laptop</option>
                        <option value="PC Desktop">PC Desktop</option>
                        <option value="Monitor">Monitor</option>
                        <option value="Printer">Printer</option>
                    </select>
                    <br>
                    <label for="merk">Merk Perangkat<span style="color: crimson;">*</span></label>
                    <input type="text" id="merk" name="merk" style="height: 35px; width: 84%;" required>
                    <br>
                    <label for="lokasi">Lokasi/Area Kerja Penggunaan Perangkat<span style="color: crimson;">*</span></label>
                    <input type="text" id="lokasi" name="lokasi" style="height: 35px; width: 192%;" readonly>
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
                    <input type="text" id="status" name="status" style="height: 35px; width: 84%;" readonly>
                    <br>
                    <label for="serialnumber">Nomor Serial<span style="color: crimson;">*</span></label>
                    <input type="text" id="serialnumber" name="serialnumber" style="height: 35px; width: 84%;" required>
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
            <label for="informasi_keluhan" id="informasi_keluhan_label" class="device-label">Informasi Keluhan/Permasalahan yang disampaikan:<span style="color: crimson;">*</span></label>
            <textarea id="informasi_keluhan" name="informasi_keluhan" style="height: 85px; width: 98%;" required class="device-select"></textarea>

            <label for="casing_lap" id="casing_lap_label" class="device-label">Casing<span style="color: crimson;">*</span></label>
            <select id="casing_lap" name="casing_lap" style="height: 35px; width: 98%;" required class="device-select">
                <option value="">--- Pilih ---</option>
                <?php
                $casingOptions = fetchData("ins_casing_lap");
                foreach ($casingOptions as $casingOption) {
                    echo '<option value="' . $casingOption['casing_lap_score'] . '">' . $casingOption['casing_lap_name'] . '</option>';
                }
                ?>
            </select>

            <label for="layar_lap" id="layar_lap_label" class="device-label">Layar<span style="color: crimson;">*</span></label>
            <select id="layar_lap" name="layar_lap" style="height: 35px; width: 98%;" required class="device-select">
                <option value="">--- Pilih ---</option>
                <?php
                $layarOptions = fetchData("ins_layar_lap");
                foreach ($layarOptions as $layarOption) {
                    echo '<option value="' . $layarOption['layar_lap_score'] . '">' . $layarOption['layar_lap_name'] . '</option>';
                }
                ?>
            </select>

            <label for="engsel_lap" id="engsel_lap_label" class="device-label">Engsel<span style="color: crimson;">*</span></label>
            <select id="engsel_lap" name="engsel_lap" style="height: 35px; width: 98%;" required class="device-select">
                <option value="">--- Pilih ---</option>
                <?php
                $engselOptions = fetchData("ins_engsel_lap");
                foreach ($engselOptions as $engselOption) {
                    echo '<option value="' . $engselOption['engsel_lap_score'] . '">' . $engselOption['engsel_lap_name'] . '</option>';
                }
                ?>
            </select>

            <label for="keyboard_lap" id="keyboard_lap_label" class="device-label">Keyboard<span style="color: crimson;">*</span></label>
            <select id="keyboard_lap" name="keyboard_lap" style="height: 35px; width: 98%;" required class="device-select">
                <option value="">--- Pilih ---</option>
                <?php
                $keyboardOptions = fetchData("ins_keyboard_lap");
                foreach ($keyboardOptions as $keyboardOption) {
                    echo '<option value="' . $keyboardOption['keyboard_lap_score'] . '">' . $keyboardOption['keyboard_lap_name'] . '</option>';
                }
                ?>
            </select>

            <label for="touchpad_lap" id="touchpad_lap_label" class="device-label">Touchpad<span style="color: crimson;">*</span></label>
            <select id="touchpad_lap" name="touchpad_lap" style="height: 35px; width:98%;" required class="device-select">
                <option value="">--- Pilih ---</option>
                <?php
                $touchpadOptions = fetchData("ins_touchpad_lap");
                foreach ($touchpadOptions as $touchpadOption) {
                    echo '<option value="' . $touchpadOption['touchpad_lap_score'] . '">' . $touchpadOption['touchpad_lap_name'] . '</option>';
                }
                ?>
            </select>

            <label for="booting_lap" id="booting_lap_label" class="device-label">Proses Booting<span style="color: crimson;">*</span></label>
            <select id="booting_lap" name="booting_lap" style="height: 35px; width:98%;" required class="device-select">
                <option value="">--- Pilih ---</option>
                <?php
                $bootingOptions = fetchData("ins_booting_lap");
                foreach ($bootingOptions as $bootingOption) {
                    echo '<option value="' . $bootingOption['booting_lap_score'] . '">' . $bootingOption['booting_lap_name'] . '</option>';
                }
                ?>
            </select>

            <label for="multi_lap" id="multi_lap_label" class="device-label">Multitasking Apps<span style="color: crimson;">*</span></label>
            <select id="multi_lap" name="multi_lap" style="height: 35px; width:98%;" required class="device-select">
                <option value="">--- Pilih ---</option>
                <?php
                $multiOptions = fetchData("ins_multi_lap");
                foreach ($multiOptions as $multiOption) {
                    echo '<option value="' . $multiOption['multi_lap_score'] . '">' . $multiOption['multi_lap_name'] . '</option>';
                }
                ?>
            </select>

            <label for="tampung_lap" id="tampung_lap_label" class="device-label">Kapasitas Baterai<span style="color: crimson;">*</span></label>
            <select id="tampung_lap" name="tampung_lap" style="height: 35px; width:98%;" required class="device-select">
                <option value="">--- Pilih ---</option>
                <?php
                $tampungOptions = fetchData("ins_tampung_lap");
                foreach ($tampungOptions as $tampungOption) {
                    echo '<option value="' . $tampungOption['tampung_lap_score'] . '">' . $tampungOption['tampung_lap_name'] . '</option>';
                }
                ?>
            </select>

            <label for="isi_lap" id="isi_lap_label" class="device-label">Waktu Pengisian Baterai<span style="color: crimson;">*</span></label>
            <select id="isi_lap" name="isi_lap" style="height: 35px; width:98%;" required class="device-select">
                <option value="">--- Pilih ---</option>
                <?php
                $isiOptions = fetchData("ins_isi_lap");
                foreach ($isiOptions as $isiOption) {
                    echo '<option value="' . $isiOption['isi_lap_score'] . '">' . $isiOption['isi_lap_name'] . '</option>';
                }
                ?>
            </select>

            <label for="port_lap" id="port_lap_label" class="device-label">Port<span style="color: crimson;">*</span></label>
            <select id="port_lap" name="port_lap" style="height: 35px; width:98%;" required class="device-select">
                <option value="">--- Pilih ---</option>
                <?php
                $portOptions = fetchData("ins_port_lap");
                foreach ($portOptions as $portOption) {
                    echo '<option value="' . $portOption['port_lap_score'] . '">' . $portOption['port_lap_name'] . '</option>';
                }
                ?>
            </select>

            <label for="audio_lap" id="audio_lap_label" class="device-label">Audio<span style="color: crimson;">*</span></label>
            <select id="audio_lap" name="audio_lap" style="height: 35px; width:98%;" required class="device-select">
                <option value="">--- Pilih ---</option>
                <?php
                $audioOptions = fetchData("ins_audio_lap");
                foreach ($audioOptions as $audioOption) {
                    echo '<option value="' . $audioOption['audio_lap_score'] . '">' . $audioOption['audio_lap_name'] . '</option>';
                }
                ?>
            </select>

            <label for="software_lap" id="software_lap_label" class="device-label">Software<span style="color: crimson;">*</span></label>
            <select id="software_lap" name="software_lap" style="height: 35px; width:98%;" required class="device-select">
                <option value="">--- Pilih ---</option>
                <?php
                $softwareOptions = fetchData("ins_software_lap");
                foreach ($softwareOptions as $softwareOption) {
                    echo '<option value="' . $softwareOption['software_lap_score'] . '">' . $softwareOption['software_lap_name'] . '</option>';
                }
                ?>
            </select>

            <label for="ink_pad" id="ink_pad_label" class="device-label">Ink Pad<span style="color: crimson;">*</span></label>
            <select id="ink_pad" name="ink_pad" style="height: 35px; width:98%;" required class="device-select">
                <option value="">--- Pilih ---</option>
                <?php
                $inkpadOptions = fetchData("ins_ink_pad");
                foreach ($inkpadOptions as $inkpadOption) {
                    echo '<option value="' . $inkpadOption['ink_pad_score'] . '">' . $inkpadOption['ink_pad_name'] . '</option>';
                }
                ?>
            </select>

            <label for="hasil_pemeriksaan" id="hasil_pemeriksaan_label" class="device-label">Hasil Pemeriksaan Lainnya:<span style="color: crimson;">*</span></label>
            <div id="hasil_pemeriksaan" style="height: 400px; width: 84%; max-width: 100%; margin: 0;"></div>
            <script>
            document.getElementById('assessmentForm').addEventListener('submit', function(event) {
                var hasilPemeriksaanContent = $('#hasil_pemeriksaan').summernote('code');
                $('#hasil_pemeriksaan').summernote('code', hasilPemeriksaanContent.trim());
            });

                $(document).ready(function() {
                    $('#hasil_pemeriksaan').summernote({
                        toolbar: [
                            ['style', ['bold', 'italic', 'underline']],
                            ['insert', ['picture']]
                        ]
                    });
                });
            </script>
            <label for="rekomendasi" id="rekomendasi_label" class="device-label">Rekomendasi:<span style="color: crimson;">*</span></label>
            <textarea id="rekomendasi" name="rekomendasi" style="height: 75px; width: 98%;" required class="device-select"></textarea>
            
            <br>
            <label for="upload_file" id="upload_file_label" class="device-label">Upload File<span style="color: crimson;">*</span></label></label>
            <input type="file" id="upload_file" name="upload_file" style="height: 40px; width: 80%;" accept=".zip, .rar" required class="device-select">
            <small style="display: block;">*Note: <br> Sebagai bahan verifikasi mohon upload file berformat .zip atau .rar dari hasil Belarc, <br>dan file tidak lebih dari 100 KB</small>
            <br>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    var elementsToShow = ['informasi_keluhan', 'casing_lap', 'layar_lap', 'engsel_lap', 'keyboard_lap', 'touchpad_lap', 'booting_lap', 'multi_lap', 'tampung_lap', 'isi_lap', 'port_lap', 'audio_lap', 'software_lap', 'ink_pad', 'hasil_pemeriksaan', 'rekomendasi', 'upload_file'];

                    // Hide label dan elemen form saat pertama kali dimuat
                    hideAllLabelsAndElements();

                    // Panggil fungsi showHideElements dengan jenis perangkat default
                    showHideElements('Perangkat');

                    document.getElementById('jenis').addEventListener('change', function() {
                        var jenisPerangkat = this.value;
                        showHideElements(jenisPerangkat);
                    });

                    function hideAllLabelsAndElements() {
                        elementsToShow.forEach(function(elementId) {
                            var element = document.getElementById(elementId);
                            var label = document.getElementById(elementId + '_label');
                            if (element && label) {
                                element.style.display = 'none';
                                label.style.display = 'none';
                            }
                        });
                    }

                    function showHideElements(jenisPerangkat) {
                        hideAllLabelsAndElements();

                        if (jenisPerangkat !== 'Perangkat') {
                            // Tampilkan hanya elemen yang relevan untuk jenis perangkat lainnya
                            var relevantElements = getRelevantElements(jenisPerangkat);
                            relevantElements.forEach(function(elementId) {
                                var element = document.getElementById(elementId);
                                var label = document.getElementById(elementId + '_label');
                                if (element && label) {
                                    element.style.display = 'block';
                                    label.style.display = 'block';
                                }
                            });
                        }
                        else {
                            // Tampilkan semua elemen terkait Laptop
                            elementsToShow.forEach(function(elementId) {
                                var element = document.getElementById(elementId);
                                var label = document.getElementById(elementId + '_label');
                                if (element && label) {
                                    element.style.display = 'block';
                                    label.style.display = 'block';
                                }
                            });
                        }
                    }

                    function getRelevantElements(jenisPerangkat) {
                        var relevantElements = {
                            'Laptop': ['informasi_keluhan', 'casing_lap', 'layar_lap', 'engsel_lap', 'keyboard_lap', 'touchpad_lap', 'booting_lap', 'multi_lap', 'tampung_lap', 'isi_lap', 'port_lap', 'audio_lap', 'software_lap', 'hasil_pemeriksaan', 'rekomendasi', 'upload_file'],
                            'PC Desktop': ['informasi_keluhan', 'casing_lap', 'layar_lap', 'keyboard_lap', 'booting_lap', 'multi_lap', 'port_lap', 'software_lap', 'hasil_pemeriksaan', 'rekomendasi', 'upload_file'],
                            'Monitor': ['informasi_keluhan', 'casing_lap', 'layar_lap', 'hasil_pemeriksaan', 'rekomendasi', 'upload_file'],
                            'Printer': ['informasi_keluhan', 'casing_lap', 'ink_pad', 'hasil_pemeriksaan', 'rekomendasi', 'upload_file']
                        };

                        return relevantElements[jenisPerangkat] || [];
                    }
                });
            </script>
            <input type="submit" value="Submit">
            <input type="reset" value="Reset">
        </form>
    </div>
</body>
</html>