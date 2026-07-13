const CACHE_NAME = 'literia-pwa-v1';

// Aset statis yang di-cache di awal (install)
const STATIC_ASSETS = [
    '/manifest.json',
    'https://unpkg.com/@phosphor-icons/web',
    'https://cdn.tailwindcss.com'
];

self.addEventListener('install', (e) => {
    e.waitUntil(
        caches.open(CACHE_NAME).then(cache => {
            return cache.addAll(STATIC_ASSETS);
        })
    );
    self.skipWaiting();
});

self.addEventListener('activate', (e) => {
    e.waitUntil(
        caches.keys().then(keys => {
            return Promise.all(
                keys.map(key => {
                    if (key !== CACHE_NAME) {
                        return caches.delete(key);
                    }
                })
            );
        })
    );
    self.clients.claim();
});

// Strategi: Network First, Fallback to Cache
self.addEventListener('fetch', (e) => {
    // Abaikan request POST
    if (e.request.method !== 'GET') return;

    e.respondWith(
        fetch(e.request)
            .then(response => {
                // Jangan cache error atau URL eksternal yang bukan punya kita jika tidak perlu, 
                // tapi untuk simplifikasi kita cache semua GET yang success
                if (response && response.status === 200 && response.type === 'basic') {
                    const responseClone = response.clone();
                    caches.open(CACHE_NAME).then(cache => {
                        cache.put(e.request, responseClone);
                    });
                }
                return response;
            })
            .catch(() => {
                // Jika offline / gagal fetch, ambil dari cache
                return caches.match(e.request);
            })
    );
});
