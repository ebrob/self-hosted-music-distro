// Service Worker for [ARTIST NAME] Music Library
// Enables background audio playback and offline functionality

const CACHE_NAME = 'drum-row-music-v1';
const urlsToCache = [
  '/',
  '/index.html',
  '/logo.jpg',
  '/music/default-album.jpg'
];

// Install event - cache essential resources
self.addEventListener('install', (event) => {
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then((cache) => {
        console.log('Opened cache');
        return cache.addAll(urlsToCache);
      })
  );
});

// Fetch event - serve from cache when possible
self.addEventListener('fetch', (event) => {
  event.respondWith(
    caches.match(event.request)
      .then((response) => {
        // Return cached version or fetch from network
        return response || fetch(event.request);
      })
  );
});

// Background sync for audio playback
self.addEventListener('sync', (event) => {
  if (event.tag === 'background-audio') {
    event.waitUntil(
      // Keep audio playing in background
      console.log('Background audio sync triggered')
    );
  }
});

// Push notification support for music controls
self.addEventListener('push', (event) => {
  if (event.data) {
    const data = event.data.json();
    const options = {
      body: data.body || 'Music playback',
      icon: data.icon || '/logo.jpg',
      badge: '/logo.jpg',
      tag: 'music-player',
      requireInteraction: true,
      actions: [
        {
          action: 'play',
          title: 'Play',
          icon: '/logo.jpg'
        },
        {
          action: 'pause',
          title: 'Pause',
          icon: '/logo.jpg'
        },
        {
          action: 'next',
          title: 'Next',
          icon: '/logo.jpg'
        },
        {
          action: 'previous',
          title: 'Previous',
          icon: '/logo.jpg'
        }
      ]
    };

    event.waitUntil(
      self.registration.showNotification(data.title || '[ARTIST NAME]', options)
    );
  }
});

// Handle notification clicks
self.addEventListener('notificationclick', (event) => {
  event.notification.close();
  
  if (event.action) {
    // Send message to main thread to handle music controls
    self.clients.matchAll().then((clients) => {
      clients.forEach((client) => {
        client.postMessage({
          type: 'music-control',
          action: event.action
        });
      });
    });
  }
});

// Handle messages from main thread
self.addEventListener('message', (event) => {
  if (event.data && event.data.type === 'update-metadata') {
    // Update media session metadata
    if (self.registration.active) {
      self.registration.active.postMessage({
        type: 'metadata-update',
        data: event.data.data
      });
    }
  }
});

