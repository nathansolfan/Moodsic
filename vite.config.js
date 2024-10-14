import { defineConfig } from 'vite';
import reactRefresh from '@vitejs/plugin-react-refresh';

import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        reactRefresh(),  // Add the reactRefresh plugin here
    ],
});
