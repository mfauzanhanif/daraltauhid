export type * from './auth';
export type * from './navigation';
export type * from './ui';

import type { Auth } from './auth';

export type CurrentPortal = {
    type: 'institution' | 'admin' | 'wali';
    code: string;
    name: string;
    dashboardUrl: string;
    is_root?: boolean;
} | null;

export type SharedData = {
    name: string;
    auth: Auth;
    sidebarOpen: boolean;
    currentPortal: CurrentPortal;
    [key: string]: unknown;
};
