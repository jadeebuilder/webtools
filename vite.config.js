import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";

export default defineConfig({
    plugins: [
        laravel({
            input: ["resources/css/app.css", "resources/js/app.js"],
            refresh: true,
            // Copier les ressources vers le dossier public
            publicDirectory: "public",
            // Inclure les images dans les ressources accessibles
            includeAssets: ["resources/images/**"],
        }),
    ],
    server: {
        host: "0.0.0.0",
        hmr: {
            host: "localhost",
        },
        cors: true,
    },
    // Définir les alias pour faciliter l'importation
    resolve: {
        alias: {
            '@': '/resources',
            '~': '/public',
        },
    },
    // Configurer comment les ressources sont construites
    build: {
        assetsInlineLimit: 0, // Empêche l'incorporation de fichiers en base64
    },
});
