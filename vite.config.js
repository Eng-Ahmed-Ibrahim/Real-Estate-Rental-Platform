import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: 'resources/js/app.js',
            refresh: true,
        }),
    ],
    server: {
        host: 'ren2go.dev2.tqnia.me',  // Use localhost for development; production will use the domain automatically
        port: 5173,         // Ensure this does not conflict with the production port
        https: true, // Forces HTTPS
        
    },
});
