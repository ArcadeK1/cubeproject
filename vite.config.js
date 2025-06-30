import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js', 'resources/js/teamTrashButton.js','resources/js/jsqr.js', 'resources/js/trashButton.js', 'resources/js/qrSearch.js', 'resources/js/viewerRefresh.js'],
            refresh: true,
        }),
    ],
});
