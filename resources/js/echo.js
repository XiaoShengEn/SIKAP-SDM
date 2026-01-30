import Echo from 'laravel-echo'
import Pusher from 'pusher-js'



window.Echo = new Echo({
    broadcaster: 'reverb',
    client: new Pusher(import.meta.env.VITE_REVERB_APP_KEY, {
        wsHost: import.meta.env.VITE_REVERB_HOST,
        wsPort: import.meta.env.VITE_REVERB_PORT,
        wssPort: import.meta.env.VITE_REVERB_PORT,
        forceTLS: false,
        enabledTransports: ['ws'],
    }),
});
