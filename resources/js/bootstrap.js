import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allow your team to quickly build robust real-time web applications.
 */

import './echo';


window.Echo.channel('agenda-channel')
    .listen('.agenda.updated', (e) => {
        console.log('REALTIME MASUK:', e);
        window.dispatchEvent(new Event('agenda-updated'));
    });