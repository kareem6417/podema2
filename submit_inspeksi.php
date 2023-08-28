<?php
$date = $_POST['date'];
$jenis = $_POST["jenis"];
$merk = $_POST["merk"];
$lokasi = $_POST["lokasi"];
$status = $_POST["status"];
$serialnumber = $_POST["serialnumber"];
$informasi_keluhan = $_POST["informasi_keluhan"];
$hasil_pemeriksaan = $_POST["hasil_pemeriksaan"];
$rekomendasi = $_POST["rekomendasi"];

$nama_user = isset($_POST["nama_user"]) ? $_POST["nama_user"] : '';

$host = "mandiricoal.net";
$user = "podema"; 
$pass = "Jam10pagi#"; 
$db = "podema";

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$sql = "INSERT INTO form_inspeksi (date, jenis, merk, lokasi, nama_user, status, serialnumber, informasi_keluhan, hasil_pemeriksaan, rekomendasi)
        VALUES ('$date', '$jenis', '$merk', '$lokasi', '$nama_user', '$status', '$serialnumber', '$informasi_keluhan', '$hasil_pemeriksaan', '$rekomendasi')";

if ($conn->query($sql) === TRUE) {
    $target_dir = $_SERVER['DOCUMENT_ROOT'] . "/dev-podema/File Upload Inspeksi/";
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
            echo '<meta http-equiv="refresh" content="0;url=view.php">';
            exit();
        } else {
            echo "Terjadi kesalahan saat mengunggah file.";
        }
    }
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
