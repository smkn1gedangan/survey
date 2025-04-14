<?php
require_once(__DIR__ . '/lib/db.class.php');
$databaseClass = new DB();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Pastikan id ada di POST request dan sanitasi
    $id = $_POST['id'];

    if (isset($id)) {
        // Gunakan koneksi PDO dari class DB
        $conn = $databaseClass->conn(); // Pastikan DB class punya method untuk mendapatkan koneksi PDO

        // Persiapkan query untuk menghapus data berdasarkan ID
        $query = "DELETE FROM psb_data_siswa WHERE data_id = :id";

        // Siapkan statement PDO
        $stmt = $conn->prepare($query);

        // Ikatkan parameter dengan nilai ID
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        // Eksekusi query
        $result = $stmt->execute();
        
        if ($result) {
            // Jika berhasil menghapus data, redirect ke halaman dengan pesan sukses
            $_SESSION['informasi_formulir'] = "Sukses menghapus user!";
            header("Location: dashboard.php");
            exit;
        } else {
                // Jika berhasil menghapus data, redirect ke halaman dengan pesan sukses
                $_SESSION['informasi_formulir'] = 'Gagal menghapus user!';
                header("Location: dashboard.php");
            exit;
        }
    } else {
        echo "ID tidak ditemukan!";
    }
}
?>
