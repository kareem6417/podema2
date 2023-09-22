<?php

$host = "mandiricoal.net";
$user = "podema"; 
$pass = "Jam10pagi#"; 
$db = "podema";

$conn = new mysqli($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi database gagal: " . $conn->connect_error);
} else {
    echo "Koneksi Berhasil";
}

$jenis = isset($_POST["jenis"]) ? $_POST["jenis"] : '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $date = isset($_POST["date"]) ? $_POST["date"] : '';
    $merk = isset($_POST["merk"]) ? $_POST["merk"] : '';
    $lokasi = isset($_POST["lokasi"]) ? $_POST["lokasi"] : '';
    $status = isset($_POST["status"]) ? $_POST["status"] : '';
    $serialnumber = isset($_POST["serialnumber"]) ? $_POST["serialnumber"] : '';
    $informasi_keluhan = isset($_POST["informasi_keluhan"]) ? $_POST["informasi_keluhan"] : '';
    $hasil_pemeriksaan = isset($_POST["hasil_pemeriksaan"]) ? $_POST["hasil_pemeriksaan"] : '';
    $rekomendasi = isset($_POST["rekomendasi"]) ? $_POST["rekomendasi"] : '';
    $nama_user = isset($_POST["nama_user"]) ? $_POST["nama_user"] : '';
    $score = 0;

    if ($jenis == "Laptop") {
        // elemen Laptop
        $casing_lap = isset($_POST["casing_lap"]) ? $_POST["casing_lap"] : '';
        $layar_lap = isset($_POST["layar_lap"]) ? $_POST["layar_lap"] : '';
        $engsel_lap = isset($_POST["engsel_lap"]) ? $_POST["engsel_lap"] : '';
        $keyboard_lap = isset($_POST["keyboard_lap"]) ? $_POST["keyboard_lap"] : '';
        $touchpad_lap = isset($_POST["touchpad_lap"]) ? $_POST["touchpad_lap"] : '';
        $booting_lap = isset($_POST["booting_lap"]) ? $_POST["booting_lap"] : '';
        $multi_lap = isset($_POST["multi_lap"]) ? $_POST["multi_lap"] : '';
        $tampung_lap = isset($_POST["tampung_lap"]) ? $_POST["tampung_lap"] : '';
        $isi_lap = isset($_POST["isi_lap"]) ? $_POST["isi_lap"] : '';
        $port_lap = isset($_POST["port_lap"]) ? $_POST["port_lap"] : '';
        $audio_lap = isset($_POST["audio_lap"]) ? $_POST["audio_lap"] : '';
        $software_lap = isset($_POST["software_lap"]) ? $_POST["software_lap"] : '';

        // Hitung skor
        $score = $casing_lap + $layar_lap + $engsel_lap + $keyboard_lap + $touchpad_lap + $booting_lap + $multi_lap + $tampung_lap + $isi_lap + $port_lap + $audio_lap + $software_lap;
        
        $sql = "INSERT INTO form_inspeksi (date, jenis, merk, lokasi, nama_user, status, serialnumber, informasi_keluhan, hasil_pemeriksaan, rekomendasi, casing_lap, layar_lap, engsel_lap, keyboard_lap, touchpad_lap, booting_lap, multi_lap, tampung_lap, isi_lap, port_lap, audio_lap, software_lap, score)
            VALUES ('$date', '$jenis', '$merk', '$lokasi', '$nama_user', '$status', '$serialnumber', '$informasi_keluhan', '$hasil_pemeriksaan', '$rekomendasi', '$casing_lap', '$layar_lap', '$engsel_lap', '$keyboard_lap', '$touchpad_lap', '$booting_lap', '$multi_lap', '$tampung_lap', '$isi_lap', '$port_lap', '$audio_lap', '$software_lap', '$score')";
    }

    if ($jenis == "PC Desktop") {
        // elemen PC Desktop
        $casing_lap = isset($_POST["casing_lap"]) ? $_POST["casing_lap"] : '';
        $layar_lap = isset($_POST["layar_lap"]) ? $_POST["layar_lap"] : '';
        $keyboard_lap = isset($_POST["keyboard_lap"]) ? $_POST["keyboard_lap"] : '';
        $booting_lap = isset($_POST["booting_lap"]) ? $_POST["booting_lap"] : '';
        $multi_lap = isset($_POST["multi_lap"]) ? $_POST["multi_lap"] : '';
        $port_lap = isset($_POST["port_lap"]) ? $_POST["port_lap"] : '';
        $audio_lap = isset($_POST["audio_lap"]) ? $_POST["audio_lap"] : '';
        $software_lap = isset($_POST["software_lap"]) ? $_POST["software_lap"] : '';

        // Hitung skor
        $score = $casing_lap + $layar_lap + $keyboard_lap + $booting_lap + $multi_lap + $port_lap + $audio_lap + $software_lap;
        
        $sql = "INSERT INTO form_inspeksi (date, jenis, merk, lokasi, nama_user, status, serialnumber, informasi_keluhan, hasil_pemeriksaan, rekomendasi, casing_lap, layar_lap, keyboard_lap, booting_lap, multi_lap, port_lap, audio_lap, software_lap, score)
            VALUES ('$date', '$jenis', '$merk', '$lokasi', '$nama_user', '$status', '$serialnumber', '$informasi_keluhan', '$hasil_pemeriksaan', '$rekomendasi', '$casing_lap', '$layar_lap', '$keyboard_lap', '$booting_lap', '$multi_lap', '$port_lap', '$audio_lap', '$software_lap', '$score')";
    }

    if ($jenis == "Monitor") {
        // elemen Monitor
        $casing_lap = isset($_POST["casing_lap"]) ? $_POST["casing_lap"] : '';
        $layar_lap = isset($_POST["layar_lap"]) ? $_POST["layar_lap"] : '';

        // Hitung skor
        $score = $casing_lap + $layar_lap;
 
        $sql = "INSERT INTO form_inspeksi (date, jenis, merk, lokasi, nama_user, status, serialnumber, informasi_keluhan, hasil_pemeriksaan, rekomendasi, casing_lap, layar_lap, score)
            VALUES ('$date', '$jenis', '$merk', '$lokasi', '$nama_user', '$status', '$serialnumber', '$informasi_keluhan', '$hasil_pemeriksaan', '$rekomendasi', '$casing_lap', '$layar_lap', '$score')";
    }

    if ($jenis == "Printer") {
        // elemen printer
        $casing_lap = isset($_POST["casing_lap"]) ? $_POST["casing_lap"] : '';
        $ink_pad = isset($_POST["ink_pad"]) ? $_POST["ink_pad"] : '';
                
        // Hitung skor
        $score = $casing_lap + $ink_pad;
        
        $sql = "INSERT INTO form_inspeksi (date, jenis, merk, lokasi, nama_user, status, serialnumber, informasi_keluhan, hasil_pemeriksaan, rekomendasi, casing_lap, ink_pad, score)
            VALUES ('$date', '$jenis', '$merk', '$lokasi', '$nama_user', '$status', '$serialnumber', '$informasi_keluhan', '$hasil_pemeriksaan', '$rekomendasi', '$casing_lap', '$ink_pad', '$score')";
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $target_dir = $_SERVER['DOCUMENT_ROOT'] . "/dev-podema/File Upload Inspeksi/";
    
        // Upload file lainnya
        $file = $_FILES['upload_file']['name'];
        $path = pathinfo($file);
        $filename = $path['filename'];
        $ext = $path['extension'];
        $temp_name = $_FILES['upload_file']['tmp_name'];
        $path_filename_ext = $target_dir . $filename . '.' . $ext;
    
        if (file_exists($path_filename_ext)) {
            echo "Maaf, file sudah ada.";
        } else {
            if (move_uploaded_file($temp_name, $path_filename_ext)) {
                echo "File Anda berhasil diunggah.";
            } else {
                echo "Terjadi kesalahan saat mengunggah file.";
            }
        }
    
        // Proses upload screenshot
        $target_dir = "dev-podema/screenshot/";
        $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if image file is a actual image or fake image
        if (isset($_POST["submit"])) {
            $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
            if ($check !== false) {
                echo "File adalah gambar - " . $check["mime"] . ".";
                $uploadOk = 1;
            } else {
                echo "File bukan gambar.";
                $uploadOk = 0;
            }
        }

        // Check if file already exists
        if (file_exists($target_file)) {
            echo "Maaf, file sudah ada.";
            $uploadOk = 0;
        }

        // Check file size
        if ($_FILES["fileToUpload"]["size"] > 500000) {
            echo "Maaf, ukuran file terlalu besar.";
            $uploadOk = 0;
        }

        // Allow certain file formats
        $allowedTypes = array("jpg", "jpeg", "png", "gif");
        if (!in_array($imageFileType, $allowedTypes)) {
            echo "Maaf, hanya file JPG, JPEG, PNG & GIF yang diperbolehkan.";
            $uploadOk = 0;
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            echo "Maaf, file Anda tidak dapat diunggah.";
        } else {
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                echo "File Anda berhasil diunggah.";
                echo '<meta http-equiv="refresh" content="0;url=viewinspeksi.php">';
                exit();
            } else {
                echo "Maaf, terjadi kesalahan saat mengunggah file.";
            }
        }
    }    
}

$conn->close();
?>