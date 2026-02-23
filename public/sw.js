const CACHE_NAME = 'activos-v2';
const STATIC_ASSETS = [
    '/offline.html',
];

// Install — cache offline page
self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME).then((cache) => cache.addAll(STATIC_ASSETS))
    );
    self.skipWaiting();
});

// Activate — clean old caches
self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then((keys) =>
            Promise.all(keys.filter((k) => k !== CACHE_NAME).map((k) => caches.delete(k)))
        )
    );
    self.clients.claim();
});

// Fetch — network first, fallback to offline page for navigations
self.addEventListener('fetch', (event) => {
    if (event.request.mode === 'navigate') {
        event.respondWith(
            fetch(event.request).catch(() => caches.match('/offline.html'))
        );
        return;
    }

    // For non-navigation requests: stale-while-revalidate for assets
    if (event.request.destination === 'style' || event.request.destination === 'script' || event.request.destination === 'font') {
        event.respondWith(
            caches.open(CACHE_NAME).then((cache) =>
                cache.match(event.request).then((cached) => {
                    const fetched = fetch(event.request).then((response) => {
                        cache.put(event.request, response.clone());
                        return response;
                    });
                    return cached || fetched;
                })
            )
        );
        return;
    }
});
