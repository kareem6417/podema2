<?php
$date = $_POST['date'];
$name = $_POST['name'];
$company = $_POST["company"];
$divisi = $_POST["divisi"];
$type = $_POST["type"];
$serialnumber = $_POST["serialnumber"];
$os = $_POST["os"];
$processor = $_POST["processor"];
$batterylife = $_POST["batterylife"];
$age = $_POST["age"];
$issue = $_POST["issue"];
$ram = $_POST["ram"];
$storage = $_POST["storage"];
$keyboard = $_POST["keyboard"];
$screen = $_POST["screen"];
$touchpad = $_POST["touchpad"];
$audio = $_POST["audio"];
$body = $_POST["body"];
$score = $os + $processor + $batterylife + $age + $issue + $ram + $storage + $keyboard + $screen + $touchpad + $audio + $body;

require_once('/admin/config.php');

if (!$conn) {
    die("Koneksi gagal");
} else {
    echo "Koneksi Berhasil";
}

$sql = "INSERT INTO assess_laptop (date, name, company, divisi, type, serialnumber, os, processor, batterylife, age, issue, ram, storage, keyboard, screen, touchpad, audio, body, score)
        VALUES ('$date', '$name', '$company', '$divisi', '$type', '$serialnumber', '$os', '$processor', '$batterylife', '$age', '$issue', '$ram', '$storage', '$keyboard', '$screen', '$touchpad', '$audio', '$body', '$score')";

if ($conn->query($sql) === TRUE) {
    $target_dir = $_SERVER['DOCUMENT_ROOT'] . "../Assessment/File Upload Laptop/";
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

        header('Location: view.php');
        exit();
    }
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>