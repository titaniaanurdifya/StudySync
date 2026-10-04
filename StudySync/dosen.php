<?php include 'koneksi.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kontak Dosen - StudySync</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #F8F9FD; }
        .bento-card { transition: transform 0.2s ease; }
        .bento-card:hover { transform: scale(1.02); }
    </style>

    <!-- PWA Meta Tags -->
    <link rel="manifest" href="manifest.json">
    <meta name="theme-color" content="#F472B6">
    <link rel="apple-touch-icon" href="icon-192x192.png">
</head>
<body class="p-6">

    <div class="max-w-md mx-auto">
        <!-- Header -->
        <header class="mb-8 flex items-center justify-between">
            <a href="index.php" class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center shadow-sm border border-gray-100 text-xl">←</a>
            <h1 class="text-xl font-bold text-gray-800">Kontak Dosen</h1>
            <div class="w-12"></div> <!-- Spacer -->
        </header>

        <!-- Grid Kontak -->
        <div class="grid grid-cols-1 gap-4">
            
            <!-- DOSEN 1 - Warna Biru Pastel -->
            <div class="bento-card bg-[#D0E7FF] p-6 rounded-[2.5rem] border border-blue-100 flex flex-col justify-between min-h-[160px] relative overflow-hidden">
                <div class="absolute -right-4 -top-4 w-24 h-24 bg-blue-200/50 rounded-full"></div>
                <div>
                    <span class="bg-white/50 text-blue-700 text-[10px] font-bold px-3 py-1 rounded-full uppercase">Dosen Pemrograman</span>
                    <h2 class="text-2xl font-bold mt-3 text-blue-900 leading-tight">Pak Budi, S.Kom</h2>
                    <p class="text-blue-700/70 text-sm font-medium">Algoritma & Struktur Data</p>
                </div>
                <div class="flex justify-end mt-4">
                    <a href="https://wa.me/6281234567890" target="_blank" class="bg-white text-blue-600 px-6 py-3 rounded-[1.5rem] font-bold shadow-sm hover:bg-blue-50 transition flex items-center gap-2">
                        Chat WA <span>➔</span>
                    </a>
                </div>
            </div>

            <!-- DOSEN 2 - Warna Pink/Peach Pastel -->
            <div class="bento-card bg-[#F9D7E8] p-6 rounded-[2.5rem] border border-pink-100 flex flex-col justify-between min-h-[160px] relative overflow-hidden">
                <div class="absolute -right-4 -top-4 w-24 h-24 bg-pink-200/50 rounded-full shadow-inner"></div>
                <div>
                    <span class="bg-white/50 text-pink-700 text-[10px] font-bold px-3 py-1 rounded-full uppercase">Dosen Desain</span>
                    <h2 class="text-2xl font-bold mt-3 text-pink-900 leading-tight">Ibu Sarah, M.Ds</h2>
                    <p class="text-pink-700/70 text-sm font-medium">UI/UX Design</p>
                </div>
                <div class="flex justify-end mt-4">
                    <a href="https://wa.me/628987654321" target="_blank" class="bg-white text-pink-600 px-6 py-3 rounded-[1.5rem] font-bold shadow-sm hover:bg-pink-50 transition flex items-center gap-2">
                        Chat WA <span>➔</span>
                    </a>
                </div>
            </div>

            <!-- DOSEN 3 - Warna Kuning/Krem Pastel -->
            <div class="bento-card bg-[#FDF2B3] p-6 rounded-[2.5rem] border border-yellow-100 flex flex-col justify-between min-h-[160px] relative overflow-hidden">
                <div class="absolute -right-4 -top-4 w-24 h-24 bg-yellow-200/50 rounded-full"></div>
                <div>
                    <span class="bg-white/50 text-yellow-700 text-[10px] font-bold px-3 py-1 rounded-full uppercase">Dosen Database</span>
                    <h2 class="text-2xl font-bold mt-3 text-yellow-900 leading-tight">Pak Andi, M.T</h2>
                    <p class="text-yellow-700/70 text-sm font-medium">Sistem Basis Data</p>
                </div>
                <div class="flex justify-end mt-4">
                    <a href="https://wa.me/6281122334455" target="_blank" class="bg-black text-white px-6 py-3 rounded-[1.5rem] font-bold shadow-sm hover:opacity-80 transition flex items-center gap-2">
                        Chat WA <span>➔</span>
                    </a>
                </div>
            </div>

             <!-- DOSEN 4 - Warna Hijau Pastel -->
             <div class="bento-card bg-[#E2F0D9] p-6 rounded-[2.5rem] border border-green-100 flex flex-col justify-between min-h-[160px] relative overflow-hidden">
                <div>
                    <span class="bg-white/50 text-green-700 text-[10px] font-bold px-3 py-1 rounded-full uppercase">Dosen Umum</span>
                    <h2 class="text-2xl font-bold mt-3 text-green-900 leading-tight">Ibu Ani, M.Pd</h2>
                    <p class="text-green-700/70 text-sm font-medium">Bahasa Inggris</p>
                </div>
                <div class="flex justify-end mt-4">
                    <a href="https://wa.me/6281212121212" target="_blank" class="bg-white text-green-600 px-6 py-3 rounded-[1.5rem] font-bold shadow-sm hover:bg-green-50 transition flex items-center gap-2">
                        Chat WA <span>➔</span>
                    </a>
                </div>
            </div>

        </div>

        <!-- Footer Info -->
        <p class="text-center text-gray-400 text-xs mt-12 font-medium tracking-wide">STUDYSYNC • KONTAK DOSEN</p>
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