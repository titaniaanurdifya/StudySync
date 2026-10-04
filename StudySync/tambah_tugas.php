<?php
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $course_id = $_POST['course_id'];
    $task_title = $_POST['task_title'];
    $deadline = $_POST['deadline'];

    // Query untuk memasukkan data tugas baru dengan status belum selesai (0)
    $query = "INSERT INTO tasks (course_id, task_title, deadline, is_completed) 
              VALUES ('$course_id', '$task_title', '$deadline', 0)";
    
    if (mysqli_query($conn, $query)) {
        header("Location: index.php"); // Kembali ke dashboard setelah berhasil
    } else {
        echo "Gagal menyimpan tugas: " . mysqli_error($conn);
    }
}
?>