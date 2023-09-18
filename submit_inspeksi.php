<?php
$date = $_POST['date'];
$jenis = $_POST["jenis"];
$merk = $_POST["merk"];
$lokasi = $_POST["lokasi"];
$status = $_POST["status"];
$serialnumber = $_POST["serialnumber"];
$informasi_keluhan = $_POST["informasi_keluhan"];
$casing_lap = $_POST["casing_lap"];
$layar_lap = $_POST["layar_lap"];
$engsel_lap = $_POST["engsel_lap"];
$keyboard_lap = $_POST["keyboard_lap"];
$touchpad_lap = $_POST["touchpad_lap"];
$booting_lap = $_POST["booting_lap"];
$multi_lap = $_POST["multi_lap"];
$tampung_lap = $_POST["tampung_lap"];
$isi_lap = $_POST["isi_lap"];
$port_lap = $_POST["port_lap"];
$audio_lap = $_POST["audio_lap"];
$software_lap = $_POST["software_lap"];
$ink_pad = $_POST["ink_pad"];
$rekomendasi = $_POST["rekomendasi"];
$score = $casing_lap + $layar_lap + $engsel_lap + $keyboard_lap + $touchpad_lap + $booting_lap + $multi_lap + $tampung_lap + $isi_lap + +$port + $audio_lap + $software_lap + $ink_pad;

$nama_user = isset($_POST["nama_user"]) ? $_POST["nama_user"] : '';

$host = "mandiricoal.net";
$user = "podema"; 
$pass = "Jam10pagi#"; 
$db = "podema";

$conn = new mysqli($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi gagal");
} else {
    echo "Koneksi Berhasil";
}

$sql = "INSERT INTO form_inspeksi (date, jenis, merk, lokasi, nama_user, status, serialnumber, informasi_keluhan, rekomendasi, casing_lap, layar_lap, engsel_lap, keyboard_lap, touchpad_lap, booting_lap, multi_lap, tampung_lap, isi_lap, port_lap, audio_lap, software_lap + ink_pad, score)
        VALUES ('$date', '$jenis', '$merk', '$lokasi', '$nama_user', '$status', '$serialnumber', '$informasi_keluhan', '$rekomendasi', '$casing_lap', '$layar_lap', '$engsel_lap', '$keyboard_lap', '$touchpad_lap', '$booting_lap', '$multi_lap', '$tampung_lap', '$isi_lap', '$port_lap', '$audio_lap', '$software_lap', '$ink_pad', '$score')";


if ($conn->query($sql) === TRUE) {
    // Menentukan direktori untuk file upload inspeksi
    $target_dir = $_SERVER['DOCUMENT_ROOT'] . "/dev-podema/File Upload Inspeksi/";
    
    // Menentukan direktori untuk screenshot inspeksi
    $screenshot_dir = $_SERVER['DOCUMENT_ROOT'] . "/dev-podema/Screenshot Inspeksi/";

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
            echo '<meta http-equiv="refresh" content="0;url=viewinspeksi.php">';
            exit();
        } else {
            echo "Terjadi kesalahan saat mengunggah file.";
        }
    }

    // Upload screenshot
    $screenshot_file = $_FILES['screenshot']['name'];
    $screenshot_path = pathinfo($screenshot_file);
    $screenshot_filename = $screenshot_path['filename'];
    $screenshot_ext = $screenshot_path['extension'];
    $screenshot_temp_name = $_FILES['screenshot']['tmp_name'];
    $screenshot_path_filename_ext = $screenshot_dir . $screenshot_filename . '.' . $screenshot_ext;

    if (file_exists($screenshot_path_filename_ext)) {
        echo "Maaf, screenshot sudah ada.";
    } else {
        if (move_uploaded_file($screenshot_temp_name, $screenshot_path_filename_ext)) {
            echo "Screenshot Anda berhasil diunggah.";
            echo '<meta http-equiv="refresh" content="0;url=viewinspeksi.php">';
            exit();
        } else {
            echo "Terjadi kesalahan saat mengunggah screenshot.";
        }
    }
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>