/**
 * Reverb + Echo bootstrap for realtime updates.
 *
 * This file runs on both TV and admin pages via `@vite(['resources/js/app.js'])`.
 */

import './bootstrap';
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

function dispatchBrowserEvent(name, detail) {
    window.dispatchEvent(new CustomEvent(name, { detail }));
}

const browserHost = window.location.hostname;
const reverbHost = import.meta.env.VITE_REVERB_HOST || browserHost;
const reverbPort = import.meta.env.VITE_REVERB_PORT ?? 8081;
const reverbScheme = import.meta.env.VITE_REVERB_SCHEME || window.location.protocol.replace(':', '');

// window.Echo = new Echo({
//     broadcaster: 'reverb',
//     key: import.meta.env.VITE_REVERB_APP_KEY,
//     wsHost: import.meta.env.VITE_REVERB_HOST,
//     wsPort: import.meta.env.VITE_REVERB_PORT ?? 80,
//     wssPort: import.meta.env.VITE_REVERB_PORT ?? 443,
//     forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? 'https') === 'https',
//     enabledTransports: ['ws', 'wss'],
//     disableStats: true,
// });

window.Echo = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: reverbHost,
    wsPort: reverbPort,
    wssPort: reverbPort,
    forceTLS: reverbScheme === 'https',
    enabledTransports: ['ws', 'wss'],
    disableStats: true,
});

// Agenda updates (existing realtime)
window.Echo.channel('agenda-updates').listen('.AgendaUpdated', (e) => {
    dispatchBrowserEvent('agenda-updated', e);
    dispatchBrowserEvent('tv-refresh', { section: 'agenda', action: 'changed', payload: e });
});

// Generic "refresh TV" signal (profil/video/runningtext/admin/etc)
window.Echo.channel('tv-updates').listen('.TvRefreshRequested', (e) => {
    dispatchBrowserEvent('tv-refresh', e);
});

// Optional debug logs
const connection = window.Echo?.connector?.pusher?.connection;
if (connection?.bind) {
    connection.bind('connected', () => console.log('[reverb] connected'));
    connection.bind('error', (err) => console.error('[reverb] error', err));
    connection.bind('disconnected', () => console.warn('[reverb] disconnected'));
}
