<?php
include 'koneksi.php';

// 1. Logika untuk menandai tugas selesai dari Dashboard
if (isset($_GET['finish_task'])) {
    $id_tugas = mysqli_real_escape_string($conn, $_GET['finish_task']);
    mysqli_query($conn, "UPDATE tasks SET is_completed = 1 WHERE id = $id_tugas");
    header("Location: index.php"); // Refresh halaman agar progress bar update
    exit();
}

// Set zona waktu
date_default_timezone_set('Asia/Makassar'); 

// Ambil hari ini dalam bahasa Indonesia
$hari_ini_eng = date('l');
$daftar_hari = [
    'Monday' => 'Senin', 'Tuesday' => 'Selasa', 'Wednesday' => 'Rabu',
    'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu', 'Sunday' => 'Minggu'
];
$hari_indo = $daftar_hari[$hari_ini_eng];

// --- LOGIKA REMINDER OTOMATIS ---
$query_reminder = "SELECT task_title, deadline FROM tasks WHERE is_completed = 0 ORDER BY deadline ASC LIMIT 1";
$result_reminder = mysqli_query($conn, $query_reminder);
$data_reminder = mysqli_fetch_assoc($result_reminder);

$pesan_reminder = "";
$is_urgent = false;

if ($data_reminder) {
    $deadline_time = strtotime($data_reminder['deadline']);
    $sekarang = time();
    $selisih = $deadline_time - $sekarang;

    if ($selisih > 0 && $selisih < 86400) {
        $jam_sisa = floor($selisih / 3600);
        $pesan_reminder = "🚨 Deadline Mepet! Tugas <b>" . $data_reminder['task_title'] . "</b> harus dikumpul dalam " . $jam_sisa . " jam lagi!";
        $is_urgent = true;
    }
}

// 2. Ambil Jadwal Kuliah hari ini
$query_kuliah = "SELECT * FROM courses WHERE day = '$hari_indo' AND start_time >= '" . date('H:i:s') . "' ORDER BY start_time ASC LIMIT 1";
$result_kuliah = mysqli_query($conn, $query_kuliah);
$data_kuliah = mysqli_fetch_assoc($result_kuliah);

// 3. Ambil Tugas Terdekat (Belum Selesai)
$query_tugas = "SELECT tasks.*, courses.name as matkul 
                FROM tasks 
                JOIN courses ON tasks.course_id = courses.id 
                WHERE is_completed = 0 
                ORDER BY deadline ASC LIMIT 2";
$result_tugas = mysqli_query($conn, $query_tugas);

// 4. Hitung Progress
$total_tugas_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM tasks");
$total_tugas = mysqli_fetch_assoc($total_tugas_query)['total'];
$selesai_tugas_query = mysqli_query($conn, "SELECT COUNT(*) as selesai FROM tasks WHERE is_completed = 1");
$selesai_tugas = mysqli_fetch_assoc($selesai_tugas_query)['selesai'];
$persen = ($total_tugas > 0) ? round(($selesai_tugas / $total_tugas) * 100) : 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StudySync - Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #F8F9FD; }
        .bento-card { transition: all 0.3s ease; }
        .bento-card:hover { transform: translateY(-5px); }
        @keyframes pulse-soft {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.8; }
        }
        .animate-pulse-soft { animation: pulse-soft 2s infinite; }
        /* Scrollbar tipis untuk list tugas */
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(244, 114, 182, 0.2); border-radius: 10px; }
    </style>
    
    <!-- PWA Meta Tags -->
    <link rel="manifest" href="manifest.json">
    <meta name="theme-color" content="#F472B6">
    <link rel="apple-touch-icon" href="icon-192x192.png">
</head>
<body class="p-6">

    <header class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-800 tracking-tight">StudySync</h1>
            <p class="text-gray-500 font-medium text-sm">Semangat kuliahnya hari ini! ✨</p>
        </div>
        <div class="flex items-center gap-3">
            <button onclick="toggleModal('modalJadwal')" class="bg-yellow-400 text-yellow-900 px-5 py-2.5 rounded-2xl text-sm font-bold shadow-lg shadow-yellow-100 hover:bg-yellow-300 transition">
                + Jadwal
            </button>
            <div class="hidden md:block bg-white p-2.5 px-4 rounded-2xl border border-gray-100 shadow-sm">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest"><?= $hari_indo; ?></p>
                <p class="text-sm font-bold text-gray-800"><?= date('d M Y'); ?></p>
            </div>
        </div>
    </header>

    <?php if ($is_urgent): ?>
    <div class="bg-red-500 text-white p-5 rounded-[2rem] mb-6 flex items-center gap-4 animate-pulse-soft shadow-xl shadow-red-100 border-b-4 border-red-700">
        <div class="bg-white/20 p-2 rounded-full text-xl">⚠️</div>
        <div class="text-sm font-medium leading-relaxed"><?= $pesan_reminder; ?></div>
    </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        <!-- Card Kuliah Utama -->
        <div class="bento-card md:col-span-2 bg-[#FDF2B3] p-8 rounded-[2.5rem] shadow-sm flex flex-col justify-between h-72 border border-yellow-200 relative overflow-hidden">
            <div class="absolute -right-10 -top-10 w-40 h-40 bg-yellow-300/30 rounded-full"></div>
            <?php if ($data_kuliah): ?>
                <div class="relative">
                    <span class="bg-white/60 text-yellow-800 text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-wider">Next Class</span>
                    <h2 class="text-4xl font-extrabold mt-4 text-yellow-900 leading-tight"><?= $data_kuliah['name']; ?></h2>
                    <p class="text-lg font-medium text-yellow-800/70">Dosen: <?= $data_kuliah['lecturer']; ?></p>
                </div>
                <div class="flex justify-between items-end relative">
                    <div class="flex gap-4">
                        <p class="font-bold text-yellow-900 bg-white/40 px-3 py-1 rounded-lg text-sm">📍 <?= $data_kuliah['room']; ?></p>
                        <p class="font-bold text-yellow-900 bg-white/40 px-3 py-1 rounded-lg text-sm">⏰ <?= substr($data_kuliah['start_time'], 0, 5); ?></p>
                    </div>
                    <a href="jadwal.php" class="bg-black text-white px-6 py-2.5 rounded-2xl text-sm font-bold hover:bg-gray-800 transition shadow-lg">Detail</a>
                </div>
            <?php else: ?>
                <div class="flex flex-col justify-center h-full items-center text-center">
                    <p class="text-5xl mb-4">🥳</p>
                    <p class="font-bold text-yellow-900 text-xl">Kelas Beres Semua!</p>
                    <a href="jadwal.php" class="text-sm text-yellow-800 underline font-semibold mt-2">Lihat jadwal lainnya</a>
                </div>
            <?php endif; ?>
        </div>

        <!-- Card Deadline (Updated with Finish Button & Empty State) -->
        <div class="bento-card bg-[#F9D7E8] p-7 rounded-[2.5rem] shadow-sm flex flex-col min-h-72 border border-pink-200">
            <h3 class="text-xl font-bold text-pink-900 mb-5">Deadline ⏳</h3>
            <div class="space-y-4 overflow-y-auto mb-4 flex-1 custom-scrollbar">
                <?php 
                if (mysqli_num_rows($result_tugas) > 0): 
                    while($t = mysqli_fetch_assoc($result_tugas)): 
                        $tgl_deadline = new DateTime($t['deadline']);
                        $skrg = new DateTime();
                        $diff = $skrg->diff($tgl_deadline);
                        $is_expired = ($tgl_deadline < $skrg);
                ?>
                        <div class="bg-white/60 p-4 rounded-3xl border border-pink-300/30 flex justify-between items-center transition-all hover:bg-white/80 group">
                            <div class="flex-1 pr-2">
                                <p class="font-bold text-sm text-pink-900 leading-tight line-clamp-1"><?= $t['task_title']; ?></p>
                                <p class="text-[10px] <?= $is_expired ? 'text-red-600' : 'text-pink-700' ?> font-extrabold uppercase mt-1">
                                    <?= $is_expired ? 'Expired' : $diff->days . "d " . $diff->h . "h left"; ?>
                                </p>
                            </div>
                            <a href="index.php?finish_task=<?= $t['id']; ?>" class="w-9 h-9 bg-pink-500 text-white rounded-2xl flex items-center justify-center shadow-lg shadow-pink-200 hover:bg-green-500 transition-all duration-300 transform hover:scale-110" title="Selesai">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                            </a>
                        </div>
                <?php 
                    endwhile; 
                else: 
                ?>
                    <div class="flex flex-col items-center justify-center h-full text-center py-6">
                        <span class="text-4xl mb-2">☕</span>
                        <p class="text-sm font-bold text-pink-900/60 leading-tight">Hooray! Tugas aman.<br>Waktunya santai.</p>
                    </div>
                <?php endif; ?>
            </div>
            <button onclick="toggleModal('modalTugas')" class="w-full bg-white text-pink-600 py-3 rounded-2xl text-sm font-extrabold shadow-sm hover:bg-pink-50 transition border border-pink-100 uppercase tracking-wider">+ TUGAS BARU</button>
        </div>

        <!-- Progress Tugas -->
        <div class="bento-card bg-[#D0E7FF] p-7 rounded-[2.5rem] shadow-sm flex items-center justify-between border border-blue-200 relative overflow-hidden">
            <div class="absolute -left-4 -bottom-4 w-20 h-20 bg-blue-300/20 rounded-full"></div>
            <div class="relative">
                <h3 class="text-lg font-bold text-blue-900 leading-tight">Progress<br>Tugas</h3>
                <p class="text-4xl font-extrabold text-blue-800 mt-2"><?= $persen; ?>%</p>
            </div>
            <div class="relative flex items-center justify-center">
                <svg class="w-24 h-24 transform -rotate-90">
                    <circle cx="48" cy="48" r="38" stroke="currentColor" stroke-width="10" fill="transparent" class="text-blue-200" />
                    <circle cx="48" cy="48" r="38" stroke="currentColor" stroke-width="10" fill="transparent" 
                        stroke-dasharray="238.7" 
                        stroke-dashoffset="<?= 238.7 - (238.7 * $persen) / 100; ?>" 
                        class="text-blue-600 transition-all duration-1000 ease-out" 
                        stroke-linecap="round" />
                </svg>
                <span class="absolute text-xs font-black text-blue-700"><?= $selesai_tugas; ?>/<?= $total_tugas; ?></span>
            </div>
        </div>

        <!-- Card Kontak Dosen -->
        <a href="dosen.php" class="bento-card bg-white p-7 rounded-[2.5rem] shadow-sm border border-gray-100 flex items-center justify-between group">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 bg-blue-100 rounded-[1.5rem] flex items-center justify-center text-blue-600 font-bold text-2xl group-hover:bg-blue-600 group-hover:text-white transition-colors duration-300">D</div>
                <div>
                    <p class="font-extrabold text-gray-800 text-lg leading-none">Dosen</p>
                    <p class="text-xs font-semibold text-gray-400 mt-1 uppercase tracking-wider">Contact List</p>
                </div>
            </div>
            <div class="text-gray-300 text-2xl group-hover:text-blue-600 transition-transform group-hover:translate-x-1">➔</div>
        </a>

        <!-- Quick Notes -->
        <div class="bento-card bg-[#E2F0D9] p-7 rounded-[2.5rem] shadow-sm border border-green-200">
            <h3 class="font-bold text-green-900 mb-3 flex items-center gap-2">
                <span>📝</span> Catatan Cepat
            </h3>
            <textarea id="quickNote" class="w-full bg-transparent text-sm text-green-800/80 font-medium border-none outline-none resize-none h-20 placeholder-green-700/40" placeholder="Ada ide apa hari ini?"></textarea>
        </div>
    </div>

    <!-- Modals -->
    <!-- Modal Tugas -->
    <div id="modalTugas" class="fixed inset-0 bg-black/60 hidden items-center justify-center p-4 z-[100] backdrop-blur-sm">
        <div class="bg-white w-full max-w-md rounded-[2.5rem] p-8 shadow-2xl scale-in">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Tugas Baru</h2>
                <button onclick="toggleModal('modalTugas')" class="text-gray-400 hover:text-black text-3xl">&times;</button>
            </div>
            <form action="tambah_tugas.php" method="POST" class="space-y-4">
                <div>
                    <label class="block text-xs font-bold uppercase text-gray-400 mb-2 ml-1">Pilih Mata Kuliah</label>
                    <select name="course_id" class="w-full bg-gray-50 p-4 rounded-2xl border-none ring-1 ring-gray-100 outline-none" required>
                        <?php
                        $res_matkul = mysqli_query($conn, "SELECT id, name FROM courses");
                        while($m = mysqli_fetch_assoc($res_matkul)) {
                            echo "<option value='".$m['id']."'>".$m['name']."</option>";
                        }
                        ?>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase text-gray-400 mb-2 ml-1">Judul Tugas</label>
                    <input type="text" name="task_title" class="w-full bg-gray-50 p-4 rounded-2xl border-none ring-1 ring-gray-100 focus:ring-2 focus:ring-pink-300 outline-none" required>
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase text-gray-400 mb-2 ml-1">Deadline</label>
                    <input type="datetime-local" name="deadline" class="w-full bg-gray-50 p-4 rounded-2xl border-none ring-1 ring-gray-100 focus:ring-2 focus:ring-pink-300 outline-none" required>
                </div>
                <button type="submit" class="w-full bg-black text-white font-bold py-4 rounded-2xl hover:opacity-80 transition shadow-lg mt-2 uppercase">Simpan Tugas</button>
            </form>
        </div>
    </div>

    <!-- Modal Jadwal -->
    <div id="modalJadwal" class="fixed inset-0 bg-black/60 hidden items-center justify-center p-4 z-[100] backdrop-blur-sm">
        <div class="bg-white w-full max-w-md rounded-[2.5rem] p-8 shadow-2xl border-t-8 border-yellow-400">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Tambah Jadwal</h2>
                <button onclick="toggleModal('modalJadwal')" class="text-gray-400 hover:text-black text-3xl">&times;</button>
            </div>
            <form action="tambah_jadwal.php" method="POST" class="space-y-4">
                <input type="text" name="name" placeholder="Nama Mata Kuliah" class="w-full bg-gray-50 p-4 rounded-2xl border-none ring-1 ring-gray-100 outline-none focus:ring-2 focus:ring-yellow-300" required>
                <input type="text" name="lecturer" placeholder="Nama Dosen" class="w-full bg-gray-50 p-4 rounded-2xl border-none ring-1 ring-gray-100 outline-none focus:ring-2 focus:ring-yellow-300" required>
                <div class="grid grid-cols-2 gap-4">
                    <select name="day" class="w-full bg-gray-50 p-4 rounded-2xl border-none ring-1 ring-gray-100 outline-none" required>
                        <option value="Senin">Senin</option>
                        <option value="Selasa">Selasa</option>
                        <option value="Rabu">Rabu</option>
                        <option value="Kamis">Kamis</option>
                        <option value="Jumat">Jumat</option>
                    </select>
                    <input type="time" name="start_time" class="w-full bg-gray-50 p-4 rounded-2xl border-none ring-1 ring-gray-100 outline-none" required>
                </div>
                <input type="text" name="room" placeholder="Ruangan/Zoom" class="w-full bg-gray-50 p-4 rounded-2xl border-none ring-1 ring-gray-100 outline-none focus:ring-2 focus:ring-yellow-300" required>
                <button type="submit" class="w-full bg-yellow-400 text-yellow-900 font-bold py-4 rounded-2xl hover:opacity-80 transition mt-4 shadow-lg shadow-yellow-100 uppercase">Simpan Jadwal</button>
            </form>
        </div>
    </div>

    <script>
        function toggleModal(id) {
            const modal = document.getElementById(id);
            if (modal) {
                modal.classList.toggle('hidden');
                modal.classList.toggle('flex');
            }
        }

        const noteArea = document.getElementById('quickNote');
        noteArea.value = localStorage.getItem('studysync_quick_note') || "";
        noteArea.addEventListener('input', () => {
            localStorage.setItem('studysync_quick_note', noteArea.value);
        });

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