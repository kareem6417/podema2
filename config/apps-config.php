<?php
$hostname = "mandiricoal.co.id";
$username = "mandiricoal";
$password = "Mandiricoal2022!";
$database = "podema";

$conn_podema = mysqli_connect($hostname, $username, $password, $database);

if (!$conn_podema) {
    die("Koneksi database userdata gagal: " . mysqli_connect_error());
}
?>
