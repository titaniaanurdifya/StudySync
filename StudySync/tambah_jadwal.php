<?php
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $lecturer = $_POST['lecturer'];
    $day = $_POST['day'];
    $start_time = $_POST['start_time'];
    $room = $_POST['room'];

    $query = "INSERT INTO courses (name, lecturer, day, start_time, room) 
              VALUES ('$name', '$lecturer', '$day', '$start_time', '$room')";
    
    if (mysqli_query($conn, $query)) {
        header("Location: index.php");
    } else {
        echo "Gagal tambah jadwal: " . mysqli_error($conn);
    }
}
?>