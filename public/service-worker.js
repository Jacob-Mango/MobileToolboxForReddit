var dataCacheName = 'SRTR-v1';
var cacheName = 'SRTR-final-1';

var filesToCache = [
	'/',
	'/index.php',
	'https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js',
	'https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js',
	'https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js',
	'https://use.fontawesome.com/releases/v5.0.13/js/all.js'
];

self.addEventListener('install', function (e) {
	console.log('[ServiceWorker] Install');
	e.waitUntil(
		caches.open(cacheName).then(function (cache) {
			console.log('[ServiceWorker] Caching app shell');
			return cache.addAll(filesToCache);
		})
	);
});

self.addEventListener('activate', function (e) {
	console.log('[ServiceWorker] Activate');
	e.waitUntil(
		caches.keys().then(function (keyList) {
			return Promise.all(keyList.map(function (key) {
				if (key !== cacheName && key !== dataCacheName) {
					console.log('[ServiceWorker] Removing old cache', key);
					return caches.delete(key);
				}
			}));
		})
	);
	return self.clients.claim();
});

self.addEventListener('fetch', function (e) {
	console.log('[Service Worker] Fetch', e.request.url);

	//if (e.request.url.indexOf(dataUrl) > -1) {
		e.respondWith(
			caches.open(dataCacheName).then(function (cache) {
				return fetch(e.request).then(function (response) {
					cache.put(e.request.url, response.clone());
					return response;
				});
			})
		);
	//} else {
	//	e.respondWith(
	//		caches.match(e.request).then(function (response) {
	//			return response || fetch(e.request);
	//		})
	//	);
	//}
});