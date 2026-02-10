import { useState } from 'react';
import { Form, Head, Link, usePage } from '@inertiajs/react';
import {
    Eye,
    EyeOff,
    Lock,
    User,
    Building2,
    GraduationCap,
    BookOpen,
    LayoutGrid,
    ArrowRight,
    CheckCircle2,
} from 'lucide-react';
import { Button } from '@/shared/ui/button';
import { Checkbox } from '@/shared/ui/checkbox';
import { Input } from '@/shared/ui/input';
import { Label } from '@/shared/ui/label';
import InputError from '@/APP/components/input-error';
import TextLink from '@/APP/components/text-link';
import AppLogoIcon from '@/APP/components/app-logo-icon';
import { store } from '@/routes/login';
import { request } from '@/routes/password';
import { home } from '@/routes';
import type { SharedData } from '@/types';

type Props = {
    status?: string;
    canResetPassword: boolean;
    canRegister: boolean;
};

export default function Login({ status, canResetPassword }: Props) {
    const { name } = usePage<SharedData>().props;
    const [activeTab, setActiveTab] = useState<'staff' | 'santri'>('staff');
    const [showPassword, setShowPassword] = useState(false);

    return (
        <>
            <Head title="Masuk" />

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
                            Satu Akun untuk <br />
                            <span className="text-emerald-300">Semua Layanan.</span>
                        </h2>
                        <p className="text-emerald-100/80 leading-relaxed">
                            Kelola data Yayasan, Akademik Pondok, Keuangan, hingga Administrasi Sekolah (MI, SMP, MA)
                            dalam satu genggaman.
                        </p>

                        <div className="space-y-4 pt-4">
                            {['Manajemen Data Santri & Wali', 'Sistem Keuangan Terpusat', 'Laporan Akademik Real-time'].map(
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

                {/* RIGHT SIDE - LOGIN FORM */}
                <div className="w-full lg:w-1/2 flex items-center justify-center p-6 lg:p-12 relative">
                    <div className="w-full max-w-[420px] space-y-6">
                        {/* Mobile Header (Visible only on mobile) */}
                        <div className="lg:hidden text-center mb-8">
                            <div className="inline-flex items-center justify-center w-12 h-12 bg-emerald-100 dark:bg-emerald-900 rounded-lg mb-4">
                                <AppLogoIcon className="w-6 h-6 text-emerald-600 dark:text-emerald-400" />
                            </div>
                            <h1 className="text-2xl font-bold text-slate-900 dark:text-slate-100">Dar Al Tauhid</h1>
                            <p className="text-slate-500 dark:text-slate-400">Super App Login Portal</p>
                        </div>

                        <div className="flex flex-col space-y-2 text-center lg:text-left">
                            <h1 className="text-2xl font-semibold tracking-tight">Selamat Datang</h1>
                            <p className="text-sm text-slate-500 dark:text-slate-400">
                                Silakan masuk menggunakan akun Anda.
                            </p>
                        </div>

                        {/* Custom Tabs */}
                        <div className="grid w-full grid-cols-2 p-1 bg-slate-100 dark:bg-slate-800 rounded-lg mb-6">
                            <button
                                type="button"
                                onClick={() => setActiveTab('staff')}
                                className={`text-sm font-medium py-2 rounded-md transition-all duration-200 ${
                                    activeTab === 'staff'
                                        ? 'bg-white dark:bg-slate-700 text-emerald-700 dark:text-emerald-400 shadow-sm'
                                        : 'text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-200'
                                }`}
                            >
                                <span className="flex items-center justify-center gap-2">
                                    <Building2 className="w-4 h-4" />
                                    PTK
                                </span>
                            </button>
                            <button
                                type="button"
                                onClick={() => setActiveTab('santri')}
                                className={`text-sm font-medium py-2 rounded-md transition-all duration-200 ${
                                    activeTab === 'santri'
                                        ? 'bg-white dark:bg-slate-700 text-emerald-700 dark:text-emerald-400 shadow-sm'
                                        : 'text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-200'
                                }`}
                            >
                                <span className="flex items-center justify-center gap-2">
                                    <GraduationCap className="w-4 h-4" />
                                    Orang Tua / Wali
                                </span>
                            </button>
                        </div>

                        <Form {...store.form()} resetOnSuccess={['password']}>
                            {({ processing, errors }) => (
                                <div className="space-y-4">
                                    {/* Hidden input to send login type to backend */}
                                    <input type="hidden" name="login_type" value={activeTab === 'staff' ? 'staff' : 'wali'} />
                                    {/* Identity Input */}
                                    <div className="space-y-2">
                                        <Label htmlFor="email">
                                            {activeTab === 'staff' ? 'Email / NIP' : 'NIS / Nomor HP'}
                                        </Label>
                                        <div className="relative">
                                            <div className="absolute left-3 top-2.5 text-slate-400">
                                                <User className="h-4 w-4" />
                                            </div>
                                            <Input
                                                id="email"
                                                type="email"
                                                name="email"
                                                placeholder={
                                                    activeTab === 'staff' ? 'admin@daraltauhid.id' : '12345678'
                                                }
                                                className="pl-9"
                                                autoFocus
                                                required
                                            />
                                        </div>
                                        <InputError message={errors.email} />
                                    </div>

                                    {/* Password Input */}
                                    <div className="space-y-2">
                                        <div className="flex items-center justify-between">
                                            <Label htmlFor="password">Kata Sandi</Label>
                                            {canResetPassword && (
                                                <TextLink
                                                    href={request()}
                                                    className="text-xs font-medium text-emerald-600 hover:underline"
                                                >
                                                    Lupa sandi?
                                                </TextLink>
                                            )}
                                        </div>
                                        <div className="relative">
                                            <div className="absolute left-3 top-2.5 text-slate-400">
                                                <Lock className="h-4 w-4" />
                                            </div>
                                            <Input
                                                id="password"
                                                type={showPassword ? 'text' : 'password'}
                                                name="password"
                                                placeholder="••••••••"
                                                className="pl-9 pr-9"
                                                required
                                            />
                                            <button
                                                type="button"
                                                onClick={() => setShowPassword(!showPassword)}
                                                className="absolute right-3 top-2.5 text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 focus:outline-none"
                                            >
                                                {showPassword ? (
                                                    <EyeOff className="h-4 w-4" />
                                                ) : (
                                                    <Eye className="h-4 w-4" />
                                                )}
                                            </button>
                                        </div>
                                        <InputError message={errors.password} />
                                    </div>

                                    {/* Remember Me */}
                                    <div className="flex items-center space-x-2">
                                        <Checkbox id="remember" name="remember" />
                                        <Label
                                            htmlFor="remember"
                                            className="text-sm font-medium text-slate-500 dark:text-slate-400"
                                        >
                                            Ingat saya di perangkat ini
                                        </Label>
                                    </div>

                                    {/* Submit Button */}
                                    <Button
                                        type="submit"
                                        className="w-full h-11 text-base group"
                                        disabled={processing}
                                    >
                                        {processing ? (
                                            <span className="flex items-center gap-2">
                                                <div className="h-4 w-4 animate-spin rounded-full border-2 border-white border-t-transparent"></div>
                                                Memproses...
                                            </span>
                                        ) : (
                                            <span className="flex items-center gap-2">
                                                Masuk ke Dashboard
                                                <ArrowRight className="w-4 h-4 group-hover:translate-x-1 transition-transform" />
                                            </span>
                                        )}
                                    </Button>

                                    {status && (
                                        <div className="text-center text-sm font-medium text-green-600">{status}</div>
                                    )}
                                </div>
                            )}
                        </Form>

                        {/* Additional Links/Info */}
                        <div className="mt-6 text-center text-xs text-slate-400">
                            <p className="mb-4">
                                Mengalami kendala login? Hubungi{' '}
                                <a href="https://wa.me/6285624568440" className="underline hover:text-emerald-600">
                                    Tim IT & Operator
                                </a>
                            </p>
                            <div className="flex items-center justify-center gap-4 border-t border-slate-200 dark:border-slate-700 pt-6">
                                <span
                                    className="flex items-center gap-1 opacity-70 hover:opacity-100 transition-opacity cursor-default"
                                    title="Madrasah Ibtidaiyah"
                                >
                                    <BookOpen className="w-3 h-3" /> MI
                                </span>
                                <span
                                    className="flex items-center gap-1 opacity-70 hover:opacity-100 transition-opacity cursor-default"
                                    title="SMP Plus"
                                >
                                    <Building2 className="w-3 h-3" /> SMP
                                </span>
                                <span
                                    className="flex items-center gap-1 opacity-70 hover:opacity-100 transition-opacity cursor-default"
                                    title="MAS Nusantara"
                                >
                                    <GraduationCap className="w-3 h-3" /> MA
                                </span>
                                <span
                                    className="flex items-center gap-1 opacity-70 hover:opacity-100 transition-opacity cursor-default"
                                    title="Pondok Pesantren"
                                >
                                    <Building2 className="w-3 h-3" /> PPDT
                                </span>
                            </div>
                            <p className="mt-4">&copy; {new Date().getFullYear()} Yayasan Dar Al Tauhid Pusat. All rights reserved.</p>
                        </div>
                    </div>
                </div>
            </div>
        </>
    );
}
