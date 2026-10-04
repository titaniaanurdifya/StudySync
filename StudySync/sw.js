const CACHE_NAME = 'studysync-v1';
const urlsToCache = [
  './index.php',
  './jadwal.php',
  './dosen.php',
  './manifest.json',
  './icon-192x192.png',
  './icon-512x512.png'
];

self.addEventListener('install', event => {
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then(cache => {
        return cache.addAll(urlsToCache);
      })
  );
});

self.addEventListener('fetch', event => {
  event.respondWith(
    caches.match(event.request)
      .then(response => {
        // Balikin cache kalau ada, jika tidak fetch dari network
        return response || fetch(event.request);
      })
  );
});
