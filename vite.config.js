import {defineConfig} from 'vite';
import laravel from 'laravel-vite-plugin';
import purge from '@erbelion/vite-plugin-laravel-purgecss';

export default defineConfig({
    build: {
        sourcemap: 'hidden'
    },
    plugins: [
        laravel([
            'resources/sass/app.scss',
            'resources/js/app.js',
        ]),
        purge({
            templates: ['blade'],
            safelist: {
                greedy: [
                    /orange/,
                    /purple/,
                    /unranked/,
                    /eagle/,
                    /cobra/,
                    /hades/,
                    /valkyrie/,
                    /rampart/,
                    /cutlass/,
                    /valor/,
                    /hazard/,
                ],
                deep: [
                    /has-tooltip-info/,
                    /has-tooltip-success/,
                    /has-tooltip-warning/,
                    /has-tooltip-danger/,
                    /has-text-success/,
                    /has-text-info/,
                    /has-text-warning/,
                    /has-text-danger/,
                    /has-text-primary/,
                    /has-background-success-light/,
                    /has-background-info-light/,
                    /has-background-warning-light/,
                    /has-background-danger-light/,
                ]
            },
        }),
    ],
});
