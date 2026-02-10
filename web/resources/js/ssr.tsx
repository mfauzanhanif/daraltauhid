import { createInertiaApp } from '@inertiajs/react';
import createServer from '@inertiajs/react/server';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import ReactDOMServer from 'react-dom/server';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

// Eagerly glob both APP and PPDT pages for SSR resolution
const appPages = import.meta.glob('./APP/pages/**/*.tsx');
const ppdtPages = import.meta.glob('./PPDT/pages/**/*.tsx');

createServer((page) =>
    createInertiaApp({
        page,
        render: ReactDOMServer.renderToString,
        title: (title) => (title ? `${title} - ${appName}` : appName),
        resolve: (name) => {
            // Try APP pages first, then PPDT pages
            if (appPages[`./APP/pages/${name}.tsx`]) {
                return resolvePageComponent(`./APP/pages/${name}.tsx`, appPages);
            }
            return resolvePageComponent(`./PPDT/pages/${name}.tsx`, ppdtPages);
        },
        setup: ({ App, props }) => {
            return <App {...props} />;
        },
    }),
);
