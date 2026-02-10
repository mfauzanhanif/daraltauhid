import { Head, router, usePage } from '@inertiajs/react';
import { Building2, ArrowRight, Shield, ArrowLeft } from 'lucide-react';
import { Button } from '@/components/ui/button';
import AppSidebarLayout from '@/layouts/app/app-sidebar-layout';
import type { SharedData, BreadcrumbItem } from '@/types';

interface Institution {
    id: number;
    code: string;
    name: string;
    type: string;
    url: string;
}

interface Props {
    institutions: Institution[];
    hasAdminAccess: boolean;
    adminDashboardUrl: string;
    currentInstitution?: string;
}

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: 'dashboard' },
    { title: 'Ganti Lembaga', href: '#' },
];

export default function InstitutionSwitch({ institutions, hasAdminAccess, adminDashboardUrl, currentInstitution }: Props) {
    const { auth } = usePage<SharedData>().props;

    const handleSelectInstitution = (institution: Institution) => {
        router.visit(`/switch-institution/${institution.code}`);
    };

    const handleSelectAdmin = () => {
        router.visit(adminDashboardUrl);
    };

    const handleBack = () => {
        window.history.back();
    };

    return (
        <AppSidebarLayout breadcrumbs={breadcrumbs}>
            <Head title="Ganti Lembaga" />

            <div className="flex flex-1 flex-col gap-6 p-6">
                {/* Header */}
                <div className="flex items-center justify-between">
                    <div className="flex items-center gap-4">
                        <Button variant="ghost" size="icon" onClick={handleBack}>
                            <ArrowLeft className="size-4" />
                        </Button>
                        <div>
                            <h1 className="text-2xl font-semibold tracking-tight">Ganti Lembaga</h1>
                            <p className="text-sm text-muted-foreground">
                                Halo, <span className="font-medium">{auth.user?.name}</span> — Pilih lembaga yang ingin Anda akses
                            </p>
                        </div>
                    </div>
                </div>

                {/* Content */}
                <div className="max-w-2xl space-y-4">
                    {/* Admin Access Card */}
                    {hasAdminAccess && (
                        <div className="p-4 rounded-xl bg-gradient-to-br from-amber-50 to-orange-50 dark:from-amber-950/30 dark:to-orange-950/30 border border-amber-200 dark:border-amber-800">
                            <div className="flex items-center justify-between">
                                <div className="flex items-center gap-3">
                                    <div className="w-10 h-10 bg-amber-100 dark:bg-amber-900 rounded-lg flex items-center justify-center">
                                        <Shield className="w-5 h-5 text-amber-600 dark:text-amber-400" />
                                    </div>
                                    <div>
                                        <p className="font-medium text-amber-800 dark:text-amber-200">Admin Yayasan</p>
                                        <p className="text-xs text-amber-600 dark:text-amber-400">Akses penuh ke seluruh lembaga</p>
                                    </div>
                                </div>
                                <Button
                                    onClick={handleSelectAdmin}
                                    className="bg-amber-600 hover:bg-amber-700 text-white"
                                >
                                    Masuk
                                    <ArrowRight className="w-4 h-4 ml-1" />
                                </Button>
                            </div>
                        </div>
                    )}

                    {/* Institutions List */}
                    <div className="space-y-3">
                        <p className="text-sm font-medium text-muted-foreground">
                            Lembaga yang dapat Anda akses:
                        </p>
                        {institutions.length > 0 ? (
                            <div className="grid gap-2">
                                {institutions.map((institution) => (
                                    <button
                                        key={institution.id}
                                        onClick={() => handleSelectInstitution(institution)}
                                        disabled={institution.code === currentInstitution}
                                        className={`w-full flex items-center justify-between p-4 rounded-xl border transition-all group
                                            ${institution.code === currentInstitution 
                                                ? 'border-emerald-500 bg-emerald-50 dark:bg-emerald-950/30 cursor-default' 
                                                : 'border-border bg-card hover:border-emerald-300 dark:hover:border-emerald-700 hover:bg-emerald-50 dark:hover:bg-emerald-950/30'
                                            }`}
                                    >
                                        <div className="flex items-center gap-3">
                                            <div className={`w-10 h-10 rounded-lg flex items-center justify-center font-bold text-sm
                                                ${institution.code === currentInstitution 
                                                    ? 'bg-emerald-500 text-white' 
                                                    : 'bg-emerald-100 dark:bg-emerald-900 text-emerald-700 dark:text-emerald-300'
                                                }`}>
                                                {institution.code.substring(0, 2).toUpperCase()}
                                            </div>
                                            <div className="text-left">
                                                <p className="font-medium">{institution.name}</p>
                                                <p className="text-xs text-muted-foreground">{institution.type}</p>
                                            </div>
                                        </div>
                                        {institution.code === currentInstitution ? (
                                            <span className="text-xs font-medium text-emerald-600 dark:text-emerald-400 px-2 py-1 bg-emerald-100 dark:bg-emerald-900 rounded-full">
                                                Aktif
                                            </span>
                                        ) : (
                                            <ArrowRight className="w-4 h-4 text-muted-foreground group-hover:text-emerald-600 group-hover:translate-x-1 transition-all" />
                                        )}
                                    </button>
                                ))}
                            </div>
                        ) : (
                            <div className="p-8 text-center rounded-xl border border-dashed">
                                <Building2 className="w-10 h-10 mx-auto text-muted-foreground mb-3" />
                                <p className="text-muted-foreground">
                                    Anda belum memiliki akses ke lembaga manapun.
                                </p>
                                <p className="text-xs text-muted-foreground mt-1">
                                    Hubungi admin untuk mendapatkan akses.
                                </p>
                            </div>
                        )}
                    </div>
                </div>
            </div>
        </AppSidebarLayout>
    );
}
