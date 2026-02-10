import { Head, Link, router, usePage } from '@inertiajs/react';
import { GraduationCap, ArrowRight, LogOut, CheckCircle2, Users } from 'lucide-react';
import { Button } from '@/shared/ui/button';
import AppLogoIcon from '@/APP/components/app-logo-icon';
import { home } from '@/routes';
import type { SharedData } from '@/types';

interface Student {
    id: number;
    public_id: string;
    name: string;
    nis?: string;
    class_name?: string;
    institution_name?: string;
}

interface Props {
    students: Student[];
}

export default function StudentSelect({ students }: Props) {
    const { name, auth } = usePage<SharedData>().props;

    const handleSelectStudent = (student: Student) => {
        router.visit(`/switch-student/${student.public_id}`);
    };

    const handleLogout = () => {
        router.post('/logout');
    };

    return (
        <>
            <Head title="Pilih Anak" />

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
                            Pantau <br />
                            <span className="text-emerald-300">Perkembangan Anak.</span>
                        </h2>
                        <p className="text-emerald-100/80 leading-relaxed">
                            Pilih anak yang ingin Anda pantau. Lihat perkembangan akademik, keuangan, dan informasi penting lainnya.
                        </p>

                        <div className="space-y-4 pt-4">
                            {['Laporan Akademik', 'Info Keuangan & Tagihan', 'Jadwal & Kegiatan Pondok'].map(
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

                {/* RIGHT SIDE - STUDENT SELECTION */}
                <div className="w-full lg:w-1/2 flex items-center justify-center p-6 lg:p-12 relative">
                    <div className="w-full max-w-[480px] space-y-6">
                        {/* Mobile Header */}
                        <div className="lg:hidden text-center mb-8">
                            <div className="inline-flex items-center justify-center w-12 h-12 bg-emerald-100 dark:bg-emerald-900 rounded-lg mb-4">
                                <AppLogoIcon className="w-6 h-6 text-emerald-600 dark:text-emerald-400" />
                            </div>
                            <h1 className="text-2xl font-bold text-slate-900 dark:text-slate-100">Dar Al Tauhid</h1>
                            <p className="text-slate-500 dark:text-slate-400">Portal Wali</p>
                        </div>

                        {/* Header with User Info */}
                        <div className="flex items-center justify-between">
                            <div>
                                <h1 className="text-2xl font-semibold tracking-tight">Pilih Anak</h1>
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

                        {/* Students List */}
                        <div className="space-y-3">
                            <p className="text-sm font-medium text-slate-600 dark:text-slate-400">
                                Anak yang terdaftar:
                            </p>
                            {students.length > 0 ? (
                                <div className="space-y-2">
                                    {students.map((student) => (
                                        <button
                                            key={student.id}
                                            onClick={() => handleSelectStudent(student)}
                                            className="w-full flex items-center justify-between p-4 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 hover:border-emerald-300 dark:hover:border-emerald-700 hover:bg-emerald-50 dark:hover:bg-emerald-950/30 transition-all group"
                                        >
                                            <div className="flex items-center gap-3">
                                                <div className="w-12 h-12 bg-emerald-100 dark:bg-emerald-900 rounded-full flex items-center justify-center text-emerald-700 dark:text-emerald-300 font-bold text-lg">
                                                    {student.name?.charAt(0).toUpperCase() || 'S'}
                                                </div>
                                                <div className="text-left">
                                                    <p className="font-medium text-slate-900 dark:text-slate-100">{student.name}</p>
                                                    <div className="flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400">
                                                        {student.nis && <span>NIS: {student.nis}</span>}
                                                        {student.class_name && (
                                                            <>
                                                                <span className="text-slate-300 dark:text-slate-600">•</span>
                                                                <span>{student.class_name}</span>
                                                            </>
                                                        )}
                                                        {student.institution_name && (
                                                            <>
                                                                <span className="text-slate-300 dark:text-slate-600">•</span>
                                                                <span>{student.institution_name}</span>
                                                            </>
                                                        )}
                                                    </div>
                                                </div>
                                            </div>
                                            <ArrowRight className="w-4 h-4 text-slate-400 group-hover:text-emerald-600 group-hover:translate-x-1 transition-all" />
                                        </button>
                                    ))}
                                </div>
                            ) : (
                                <div className="p-8 text-center rounded-xl border border-dashed border-slate-300 dark:border-slate-700">
                                    <Users className="w-10 h-10 mx-auto text-slate-400 mb-3" />
                                    <p className="text-slate-500 dark:text-slate-400">
                                        Tidak ada data anak yang terdaftar.
                                    </p>
                                    <p className="text-xs text-slate-400 mt-1">
                                        Hubungi admin sekolah untuk menghubungkan data anak Anda.
                                    </p>
                                </div>
                            )}
                        </div>

                        {/* Help Text */}
                        <div className="p-4 rounded-xl bg-blue-50 dark:bg-blue-950/30 border border-blue-200 dark:border-blue-800">
                            <div className="flex items-start gap-3">
                                <GraduationCap className="w-5 h-5 text-blue-600 dark:text-blue-400 mt-0.5" />
                                <div className="text-sm">
                                    <p className="font-medium text-blue-800 dark:text-blue-200">
                                        Belum melihat anak Anda?
                                    </p>
                                    <p className="text-blue-600 dark:text-blue-400 mt-1">
                                        Hubungi bagian administrasi untuk menghubungkan data anak dengan akun Anda.
                                    </p>
                                </div>
                            </div>
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
