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

window.Echo = new Echo({
    broadcaster: 'reverb',
    key: 'local',
    wsHost: window.location.hostname,
    wsPort: 8081,
    wssPort: 8081,
    forceTLS: false,
    enabledTransports: ['ws'],
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
