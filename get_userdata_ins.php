<?php
$conn_podema = mysqli_connect("mandiricoal.co.id", "podema", "podema2024!", "podema");

if (!$conn_podema) {
    die("Koneksi database podema gagal: " . mysqli_connect_error());
}

if (isset($_GET['name'])) {
    $name = $_GET['name'];
    $query = "SELECT department, company FROM users WHERE name = '$name'";
    $result = mysqli_query($conn_podema, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $data = array('department' => $row['department'], 'company' => $row['company']);
        echo json_encode($data);
    }
}

mysqli_close($conn_podema);
?>
