/// <reference types="vite/client" />

declare module 'vite/client' {
    interface ImportMetaEnv {
        readonly VITE_APP_NAME: string;
        readonly VITE_APP_ENV: string;
        readonly VITE_APP_DEBUG: string;
        [key: string]: string | boolean | undefined;
    }

    interface ImportMeta {
        readonly env: ImportMetaEnv;
        readonly glob: <T>(pattern: string) => Record<string, () => Promise<T>>;
    }
}

// Global type for Inertia
declare global {
    interface Window {
        Laravel?: {
            csrfToken: string;
        };
    }
}

export {};
