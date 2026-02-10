import { Link, usePage } from '@inertiajs/react';
import AppLogoIcon from '@/APP/components/app-logo-icon';
import { home } from '@/routes';
import type { SharedData } from '@/types';

interface AuthLayoutProps {
    children: React.ReactNode;
    title: string;
    description: string;
}

export default function AuthLayout({ children, title, description }: AuthLayoutProps) {
    const { name } = usePage<SharedData>().props;

    return (
        <div className="relative min-h-screen flex flex-col items-center justify-center px-6 py-12 bg-slate-50 dark:bg-slate-950">
            <div className="w-full max-w-sm space-y-6">
                <Link href={home()} className="flex items-center justify-center gap-2 mb-8">
                    <AppLogoIcon className="h-10 text-emerald-600 dark:text-emerald-400" />
                    <span className="text-xl font-bold text-slate-900 dark:text-slate-100">{name}</span>
                </Link>
                
                <div className="text-center space-y-2">
                    <h1 className="text-2xl font-semibold tracking-tight text-slate-900 dark:text-slate-100">{title}</h1>
                    <p className="text-sm text-slate-500 dark:text-slate-400">{description}</p>
                </div>
                
                {children}
            </div>
        </div>
    );
}
