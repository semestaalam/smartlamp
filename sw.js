/* SMARTLAMP - Service Worker PWA
   Biar bisa di-Install di HP Android & iPhone, bisa dibuka offline untuk tampilan dasar.
   Data lampu tetap diambil dari server PHP + MySQL kalau online.
   Kalau offline, tampilan terakhir yang sudah dibuka tetap bisa dilihat. */
const CACHE_NAME = 'smartlamp-v1';
const CORE = ['/', '/index.html', '/manifest.json'];

self.addEventListener('install', (e) => {
  e.waitUntil(caches.open(CACHE_NAME).then((c) => c.addAll(CORE)).then(() => self.skipWaiting()));
});
self.addEventListener('activate', (e) => {
  e.waitUntil(
    caches.keys().then((keys) => Promise.all(keys.filter((k) => k !== CACHE_NAME).map((k) => caches.delete(k)))).then(() => self.clients.claim())
  );
});
self.addEventListener('fetch', (e) => {
  const url = new URL(e.request.url);
  // API (PHP + MySQL) jangan di-cache, harus selalu data terbaru dari server
  if (url.pathname.startsWith('/api/')) return;
  e.respondWith(
    caches.match(e.request).then((hit) => {
      const net = fetch(e.request).then((res) => {
        if (res.ok && e.request.method === 'GET') {
          const copy = res.clone();
          caches.open(CACHE_NAME).then((c) => c.put(e.request, copy));
        }
        return res;
      }).catch(() => hit);
      return hit || net;
    })
  );
});
