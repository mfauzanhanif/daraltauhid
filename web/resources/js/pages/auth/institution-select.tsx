import { Head, Link, router, usePage } from '@inertiajs/react';
import { Building2, ArrowRight, LogOut, Shield, CheckCircle2 } from 'lucide-react';
import { Button } from '@/components/ui/button';
import AppLogoIcon from '@/components/app-logo-icon';
import { home } from '@/routes';
import type { SharedData } from '@/types';

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
}

export default function InstitutionSelect({ institutions, hasAdminAccess, adminDashboardUrl }: Props) {
    const { name, auth } = usePage<SharedData>().props;

    const handleSelectInstitution = (institution: Institution) => {
        router.visit(`/switch-institution/${institution.code}`);
    };

    const handleSelectAdmin = () => {
        router.visit(adminDashboardUrl);
    };

    const handleLogout = () => {
        router.post('/logout');
    };

    return (
        <>
            <Head title="Pilih Lembaga" />

            <div className="min-h-screen w-full flex bg-slate-50 font-sans text-slate-900 dark:bg-slate-950 dark:text-slate-100">
                {/* LEFT SIDE - BRANDING & INFO (Hidden on mobile) */}
                <div className="hidden lg:flex lg:w-1/2 relative bg-emerald-900 overflow-hidden flex-col justify-between p-12 text-white">
                    {/* Background Patterns */}
                    <div className="absolute inset-0 opacity-10 pointer-events-none">
                        <div
                            className="absolute inset-0"
                            style={{
                                backgroundImage:
                                    'radial-gradient(circle at 2px 2px, rgba(255,255,255,0.15) 1px, transparent 0)',
                                backgroundSize: '40px 40px',
                            }}
                        ></div>
                    </div>

                    {/* Decorative Circles */}
                    <div className="absolute top-[-10%] left-[-10%] w-96 h-96 bg-emerald-500/20 rounded-full blur-3xl"></div>
                    <div className="absolute bottom-[-10%] right-[-10%] w-96 h-96 bg-yellow-500/10 rounded-full blur-3xl"></div>

                    {/* Content Top */}
                    <div className="relative z-10">
                        <Link href={home()} className="flex items-center gap-3 mb-8">
                            <div className="w-12 h-12 bg-white/10 backdrop-blur-sm rounded-lg flex items-center justify-center border border-white/20">
                                <AppLogoIcon className="w-7 h-7 text-emerald-300" />
                            </div>
                            <div>
                                <h1 className="text-xl font-bold tracking-tight">{name}</h1>
                                <p className="text-emerald-200 text-sm">Sistem Informasi Terintegrasi</p>
                            </div>
                        </Link>
                    </div>

                    {/* Content Middle - Features */}
                    <div className="relative z-10 space-y-6 max-w-md">
                        <h2 className="text-4xl font-bold leading-tight">
                            Pilih <br />
                            <span className="text-emerald-300">Lembaga Anda.</span>
                        </h2>
                        <p className="text-emerald-100/80 leading-relaxed">
                            Anda memiliki akses ke beberapa lembaga. Silakan pilih lembaga yang ingin Anda kelola saat ini.
                        </p>

                        <div className="space-y-4 pt-4">
                            {['Akses Multi-Lembaga', 'Switch Lembaga Kapan Saja', 'Data Terpisah per Lembaga'].map(
                                (item, idx) => (
                                    <div key={idx} className="flex items-center gap-3">
                                        <CheckCircle2 className="w-5 h-5 text-emerald-400" />
                                        <span className="font-medium">{item}</span>
                                    </div>
                                )
                            )}
                        </div>
                    </div>

                    {/* Content Bottom - Footer Info */}
                    <div className="relative z-10 text-sm text-emerald-200/60 mt-12">
                        <p className="font-semibold text-white mb-1">Yayasan Dar Al Tauhid Pusat</p>
                        <p>Jl. KH. A. Syathori, Arjawinangun, Cirebon - 45162</p>
                    </div>
                </div>

                {/* RIGHT SIDE - INSTITUTION SELECTION */}
                <div className="w-full lg:w-1/2 flex items-center justify-center p-6 lg:p-12 relative">
                    <div className="w-full max-w-[480px] space-y-6">
                        {/* Mobile Header */}
                        <div className="lg:hidden text-center mb-8">
                            <div className="inline-flex items-center justify-center w-12 h-12 bg-emerald-100 dark:bg-emerald-900 rounded-lg mb-4">
                                <AppLogoIcon className="w-6 h-6 text-emerald-600 dark:text-emerald-400" />
                            </div>
                            <h1 className="text-2xl font-bold text-slate-900 dark:text-slate-100">Dar Al Tauhid</h1>
                            <p className="text-slate-500 dark:text-slate-400">Pilih Lembaga</p>
                        </div>

                        {/* Header with User Info */}
                        <div className="flex items-center justify-between">
                            <div>
                                <h1 className="text-2xl font-semibold tracking-tight">Pilih Lembaga</h1>
                                <p className="text-sm text-slate-500 dark:text-slate-400">
                                    Halo, <span className="font-medium text-slate-700 dark:text-slate-300">{auth.user?.name}</span>
                                </p>
                            </div>
                            <Button
                                variant="ghost"
                                size="sm"
                                onClick={handleLogout}
                                className="text-slate-500 hover:text-red-600"
                            >
                                <LogOut className="size-4 mr-1" />
                                Keluar
                            </Button>
                        </div>

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
                            <p className="text-sm font-medium text-slate-600 dark:text-slate-400">
                                Lembaga yang dapat Anda akses:
                            </p>
                            {institutions.length > 0 ? (
                                <div className="space-y-2">
                                    {institutions.map((institution) => (
                                        <button
                                            key={institution.id}
                                            onClick={() => handleSelectInstitution(institution)}
                                            className="w-full flex items-center justify-between p-4 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 hover:border-emerald-300 dark:hover:border-emerald-700 hover:bg-emerald-50 dark:hover:bg-emerald-950/30 transition-all group"
                                        >
                                            <div className="flex items-center gap-3">
                                                <div className="w-10 h-10 bg-emerald-100 dark:bg-emerald-900 rounded-lg flex items-center justify-center text-emerald-700 dark:text-emerald-300 font-bold text-sm">
                                                    {institution.code.substring(0, 2).toUpperCase()}
                                                </div>
                                                <div className="text-left">
                                                    <p className="font-medium text-slate-900 dark:text-slate-100">{institution.name}</p>
                                                    <p className="text-xs text-slate-500 dark:text-slate-400">{institution.type}</p>
                                                </div>
                                            </div>
                                            <ArrowRight className="w-4 h-4 text-slate-400 group-hover:text-emerald-600 group-hover:translate-x-1 transition-all" />
                                        </button>
                                    ))}
                                </div>
                            ) : (
                                <div className="p-8 text-center rounded-xl border border-dashed border-slate-300 dark:border-slate-700">
                                    <Building2 className="w-10 h-10 mx-auto text-slate-400 mb-3" />
                                    <p className="text-slate-500 dark:text-slate-400">
                                        Anda belum memiliki akses ke lembaga manapun.
                                    </p>
                                    <p className="text-xs text-slate-400 mt-1">
                                        Hubungi admin untuk mendapatkan akses.
                                    </p>
                                </div>
                            )}
                        </div>

                        {/* Footer */}
                        <div className="text-center text-xs text-slate-400 pt-4">
                            <p>&copy; {new Date().getFullYear()} Yayasan Dar Al Tauhid Pusat. All rights reserved.</p>
                        </div>
                    </div>
                </div>
            </div>
        </>
    );
}
