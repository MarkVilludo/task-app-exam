import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

//additional
import vue from "@vitejs/plugin-vue";
import path from "path";

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        //additionals
        vue(),
    ],
    //additionals
    resolve: {
        alias: {
        
            "@resources": path.resolve(__dirname, "resources"),
            "@assets": path.resolve(__dirname, "resources/assets"),
            "@public": path.resolve(__dirname, "public/assets"),
            "@mixins": path.resolve(__dirname, "resources/js/Pages/Mixins"),
        },
    },
    build: {
        rollupOptions: {
            output: {
                assetFileNames: 'assets/[name]-[hash].[ext]',
            },
        },
    },
});