<?php
if (isset($_GET['name'])) {
    $conn_podema = mysqli_connect("localhost", "root", "", "podema");

    if (!$conn_podema) {
        die("Koneksi database userdata gagal: " . mysqli_connect_error());
    }

    $name = $_GET['name'];
    $query = "SELECT company, department FROM users WHERE name = '$name'";
    $result = mysqli_query($conn_podema, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $userData = mysqli_fetch_assoc($result);
        echo json_encode($userData);
    } else {
        echo json_encode(array());
    }

    mysqli_close($conn_podema);
}
?>
