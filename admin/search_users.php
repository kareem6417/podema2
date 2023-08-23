<?php

require_once 'config.php';

$keyword = $_GET['keyword'];

$query = "SELECT * FROM users WHERE username LIKE '%$keyword%' OR name LIKE '%$keyword%' OR nik LIKE '%$keyword%' OR company LIKE '%$keyword%' OR department LIKE '%$keyword%'";
$result = $conn->query($query);

$users = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Menambahkan atribut user_id ke dalam array bersama dengan data pengguna lainnya
        $row['user_id'] = $row['user_id'];
        $users[] = $row;
    }
}

// Mengirimkan hasil pencarian sebagai respons JSON
echo json_encode($users);

$conn->close(); // Menutup koneksi database setelah penggunaan
?>
