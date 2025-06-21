// resuorces/js/app.js
// import './bootstrap';
// Importing required packages
import Echo from 'laravel-echo';
import Pusher from 'pusher-js'; // or Reverb if that's what you're using

window.Pusher = Pusher;
window.Pusher.logToConsole = true;

// Initialize Echo
window.Echo = new Echo({
    broadcaster: 'reverb', // or 'reverb' if that's your setup
    key: import.meta.env.VITE_REVERB_APP_KEY,
    cluster: 'eu', // Add appropriate cluster if using Pusher
    wsHost: import.meta.env.VITE_REVERB_HOST,
    wsPort: import.meta.env.VITE_REVERB_PORT,
    forceTLS: true, // Set to true if you are using HTTPS
    enabledTransports: ['ws', 'wss'], // Enable WebSocket transport
});

// Confirm Echo initialization
console.log('Echo initialized:', window.Echo);
