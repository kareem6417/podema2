<?php
define('DB_HOST_LAPTOP', 'localhost');
define('DB_USERNAME_LAPTOP', 'root');
define('DB_PASSWORD_LAPTOP', '');
define('DB_NAME_LAPTOP', 'podema');

define('DB_HOST_PC', 'localhost');
define('DB_USERNAME_PC', 'root');
define('DB_PASSWORD_PC', '');
define('DB_NAME_PC', 'podema');

define('DB_HOST_INSPEKSI', 'localhost');
define('DB_USERNAME_INSPEKSI', 'root');
define('DB_PASSWORD_INSPEKSI', '');
define('DB_NAME_INSPEKSI', 'podema');

try {
    $db_laptop = new PDO("mysql:host=".DB_HOST_LAPTOP.";dbname=".DB_NAME_LAPTOP, DB_USERNAME_LAPTOP, DB_PASSWORD_LAPTOP);
    $db_laptop->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Koneksi database podema gagal: " . $e->getMessage());
}

try {
    $db_pc = new PDO("mysql:host=".DB_HOST_PC.";dbname=".DB_NAME_PC, DB_USERNAME_PC, DB_PASSWORD_PC);
    $db_pc->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Koneksi database podema gagal: " . $e->getMessage());
}

try {
    $db_inspeksi = new PDO("mysql:host=".DB_HOST_INSPEKSI.";dbname=".DB_NAME_INSPEKSI, DB_USERNAME_INSPEKSI, DB_PASSWORD_INSPEKSI);
    $db_inspeksi->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Koneksi database podema gagal: " . $e->getMessage());
}

// Tambahkan koneksi untuk tabel pctype
try {
    $db_pctype = new PDO("mysql:host=".DB_HOST_PC.";dbname=".DB_NAME_PC, DB_USERNAME_PC, DB_PASSWORD_PC);
    $db_pctype->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Koneksi database podema gagal: " . $e->getMessage());
}
?>
