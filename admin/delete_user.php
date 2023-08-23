<?php
session_start();

if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
    header("location: login.php");
    exit;
}

require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['user_id'])) {
    $userId = $_GET['user_id'];

    $stmt = $conn->prepare("DELETE FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $userId);

    if ($stmt->execute()) {
        $_SESSION['notification'] = "Berhasil menghapus pengguna.";
    } else {
        $_SESSION['notification'] = "Gagal menghapus pengguna.";
        echo "Error deleting user: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();

header("Location: admin.php");
exit();
?>
