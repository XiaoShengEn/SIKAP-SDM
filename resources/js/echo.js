import Echo from 'laravel-echo'




window.Echo = new Echo({
    broadcaster: 'reverb',
    host: window.location.hostname + ':8081',
});
