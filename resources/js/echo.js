import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;
const reverb = window.reverbConfig || {};

if (reverb.key && reverb.host) {
    window.Echo = new Echo({
        broadcaster: 'reverb',
        key: reverb.key,
        wsHost: reverb.host,
        wsPort: Number(reverb.port || 80),
        wssPort: Number(reverb.port || 443),
        forceTLS: reverb.scheme === 'https',
        enabledTransports: ['ws', 'wss'],
        authEndpoint: '/broadcasting/auth',
    });
}
