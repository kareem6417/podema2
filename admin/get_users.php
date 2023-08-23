<?php

require_once 'config.php';

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
