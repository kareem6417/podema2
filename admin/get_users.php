<?php

$conn = mysqli_connect("mandiricoal.net", "podema", "Jam10pagi#", "podema");
if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

$searchQuery = $_GET['search'];
$query = "SELECT * FROM users WHERE nama LIKE '%$searchQuery%'";
$result = mysqli_query($conn, $query);

$users = array();
while ($row = mysqli_fetch_assoc($result)) {
    $user = array(
        'nama' => $row['nama'],
        'username' => $row['username']
    );
    $users[] = $user;
}

header('Content-Type: application/json');
echo json_encode($users);

mysqli_close($conn);
?>
