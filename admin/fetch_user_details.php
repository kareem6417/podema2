<?php

$conn = mysqli_connect("mandiricoal.net", "podema", "podema2024@", "podema");
if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

$user_id = $_GET['user_id'];

$sql = "SELECT * FROM users WHERE user_id = '$user_id'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();

    echo json_encode($user);
} else {
    echo "User not found";
}

$conn->close();
?>
