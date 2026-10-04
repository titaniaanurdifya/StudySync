<?php
include 'koneksi.php';

// Ambil semua data jadwal, urutkan berdasarkan hari dan jam
$query = "SELECT * FROM courses ORDER BY 
          FIELD(day, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'), 
          start_time ASC";
$result = mysqli_query($conn, $query);

// Kelompokkan data berdasarkan hari agar mudah ditampilkan
$jadwal_per_hari = [];
while ($row = mysqli_fetch_assoc($result)) {
    $jadwal_per_hari[$row['day']][] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Semua Jadwal - StudySync</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #F9F9F9; }
    </style>

    <!-- PWA Meta Tags -->
    <link rel="manifest" href="manifest.json">
    <meta name="theme-color" content="#F472B6">
    <link rel="apple-touch-icon" href="icon-192x192.png">
</head>
<body class="p-6">

    <header class="mb-8 flex items-center gap-4">
        <a href="index.php" class="bg-white p-3 rounded-full shadow-sm hover:bg-gray-100 transition border border-gray-100">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
        </a>
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Semua Jadwal</h1>
            <p class="text-gray-500">Daftar lengkap kuliahmu seminggu ini</p>
        </div>
    </header>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php 
        $daftar_hari = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
        
        // Penentuan warna tiap hari agar berbeda
        $warna_hari = [
            'Senin'  => 'bg-[#FDF2B3] border-yellow-200', // Kuning
            'Selasa' => 'bg-[#D0E7FF] border-blue-200',   // Biru
            'Rabu'   => 'bg-[#F9D7E8] border-pink-200',   // Pink
            'Kamis'  => 'bg-[#E2F0D9] border-green-200',  // Hijau
            'Jumat'  => 'bg-[#FFD8BE] border-orange-200', // Oranye
            'Sabtu'  => 'bg-[#E5E7EB] border-gray-300',   // Abu-abu
            'Minggu' => 'bg-[#FECACA] border-red-200'     // Merah
        ];

        foreach ($daftar_hari as $hari): 
            if (isset($jadwal_per_hari[$hari])): ?>
                <div class="<?= $warna_hari[$hari]; ?> p-6 rounded-[2.5rem] shadow-sm border">
                    <h2 class="text-2xl font-bold mb-4 flex justify-between items-center text-gray-800">
                        <?= $hari; ?>
                        <span class="text-xs bg-white/60 px-3 py-1 rounded-full font-bold uppercase tracking-wider">
                            <?= count($jadwal_per_hari[$hari]); ?> Matkul
                        </span>
                    </h2>
                    
                    <div class="space-y-3">
                        <?php foreach ($jadwal_per_hari[$hari] as $item): ?>
                            <div class="bg-white/90 p-4 rounded-2xl shadow-sm border border-black/5 hover:scale-[1.02] transition-transform cursor-default">
                                <p class="font-bold text-gray-800 text-lg leading-tight"><?= $item['name']; ?></p>
                                <div class="flex justify-between items-center mt-3 text-xs font-bold text-gray-500">
                                    <span class="flex items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <?= substr($item['start_time'], 0, 5); ?>
                                    </span>
                                    <span class="flex items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        <?= $item['room']; ?>
                                    </span>
                                </div>
                                <div class="mt-2 pt-2 border-t border-gray-100">
                                    <p class="text-[10px] text-gray-400 font-medium uppercase tracking-tighter">Dosen Pengampu</p>
                                    <p class="text-xs text-gray-600 font-semibold italic"><?= $item['lecturer']; ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; 
        endforeach; ?>

        <?php if (empty($jadwal_per_hari)): ?>
            <div class="col-span-full text-center py-20 bg-white rounded-[3rem] border-2 border-dashed border-gray-200">
                <div class="text-6xl mb-4 text-gray-200">📭</div>
                <h3 class="text-xl font-bold text-gray-400">Belum ada jadwal kuliah</h3>
                <p class="text-gray-400 text-sm mt-1">Tambahkan jadwal baru melalui dashboard utama.</p>
                <a href="index.php" class="inline-block mt-6 bg-yellow-400 text-yellow-900 px-8 py-3 rounded-full font-bold shadow-lg shadow-yellow-100 hover:scale-105 transition">Kembali ke Dashboard</a>
            </div>
        <?php endif; ?>
    </div>

    <script>
        // PWA Service Worker Registration
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('sw.js')
                    .then(registration => {
                        console.log('ServiceWorker registration successful with scope: ', registration.scope);
                    })
                    .catch(error => {
                        console.log('ServiceWorker registration failed: ', error);
                    });
            });
        }
    </script>
</body>
</html>