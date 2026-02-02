/**
 * SETUP LARAVEL ECHO & REVERB
 * File: resources/js/app.js
 * 
 * Install dependencies terlebih dahulu:
 * npm install --save laravel-echo pusher-js
 */

import './bootstrap';
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

// Make Pusher available globally
window.Pusher = Pusher;

// Initialize Laravel Echo with Reverb
window.Echo = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST,
    wsPort: import.meta.env.VITE_REVERB_PORT ?? 80,
    wssPort: import.meta.env.VITE_REVERB_PORT ?? 443,
    forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? 'https') === 'https',
    enabledTransports: ['ws', 'wss'],
    disableStats: true,
});

// Listen to agenda updates channel
window.Echo.channel('agenda-updates')
    .listen('.AgendaUpdated', (e) => {
        console.log('📡 Agenda Updated Event Received:', e);
        
        // Dispatch custom browser event untuk didengar oleh JS lain
        window.dispatchEvent(new CustomEvent('agenda-updated', {
            detail: e
        }));
    });

// Debug connection
window.Echo.connector.pusher.connection.bind('connected', () => {
    console.log('✅ Connected to Reverb');
});

window.Echo.connector.pusher.connection.bind('error', (err) => {
    console.error('❌ Reverb Connection Error:', err);
});

window.Echo.connector.pusher.connection.bind('disconnected', () => {
    console.warn('⚠️ Disconnected from Reverb');
});