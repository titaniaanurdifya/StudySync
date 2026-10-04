<?php
include 'koneksi.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Update status tugas menjadi 1 (selesai)
    $query = "UPDATE tasks SET is_completed = 1 WHERE id = '$id'";
    
    if (mysqli_query($conn, $query)) {
        header("Location: index.php");
    } else {
        echo "Gagal update status: " . mysqli_error($conn);
    }
}
?>