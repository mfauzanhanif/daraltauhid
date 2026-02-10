import { Head } from '@inertiajs/react';
import { Building2, Users, BookOpen, DollarSign, Package, Settings, Shield, TrendingUp } from 'lucide-react';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/shared/ui/card';
import AppLayout from '@/APP/layouts/app-layout';
import type { BreadcrumbItem } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Admin Yayasan',
        href: '/admin/dashboard',
    },
];

export default function AdminDashboard() {
    const globalStats = [
        { title: 'Total Lembaga', value: '—', icon: Building2, color: 'text-blue-600', bg: 'bg-blue-100 dark:bg-blue-900/30' },
        { title: 'Total Pegawai', value: '—', icon: Users, color: 'text-emerald-600', bg: 'bg-emerald-100 dark:bg-emerald-900/30' },
        { title: 'Total Siswa', value: '—', icon: BookOpen, color: 'text-purple-600', bg: 'bg-purple-100 dark:bg-purple-900/30' },
        { title: 'Total Aset', value: '—', icon: Package, color: 'text-amber-600', bg: 'bg-amber-100 dark:bg-amber-900/30' },
    ];

    const adminLinks = [
        { title: 'Manajemen Lembaga', href: '/admin/institutions', icon: Building2, desc: 'Kelola data lembaga' },
        { title: 'Tahun Ajaran', href: '/admin/academic-years', icon: BookOpen, desc: 'Atur tahun ajaran aktif' },
        { title: 'Periode Fiskal', href: '/admin/fiscal-periods', icon: DollarSign, desc: 'Kelola tahun buku keuangan' },
        { title: 'Role & Permission', href: '/admin/roles', icon: Shield, desc: 'Atur hak akses global' },
    ];

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Dashboard Admin Yayasan" />

            <div className="flex flex-col gap-6 p-6">
                {/* Welcome Banner */}
                <Card className="bg-gradient-to-r from-amber-500/10 via-orange-500/5 to-transparent border-amber-500/20">
                    <CardHeader>
                        <div className="flex items-center gap-4">
                            <div className="flex size-16 items-center justify-center rounded-xl bg-gradient-to-br from-amber-500 to-orange-600 text-white">
                                <Shield className="size-8" />
                            </div>
                            <div>
                                <CardTitle className="text-2xl">Dashboard Admin Yayasan</CardTitle>
                                <CardDescription className="text-base">
                                    Kelola seluruh lembaga di bawah naungan Yayasan Dar Al Tauhid
                                </CardDescription>
                            </div>
                        </div>
                    </CardHeader>
                </Card>

                {/* Global Stats */}
                <div className="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
                    {globalStats.map((stat) => (
                        <Card key={stat.title}>
                            <CardHeader className="flex flex-row items-center justify-between pb-2">
                                <CardTitle className="text-sm font-medium text-muted-foreground">
                                    {stat.title}
                                </CardTitle>
                                <div className={`rounded-lg p-2 ${stat.bg}`}>
                                    <stat.icon className={`size-5 ${stat.color}`} />
                                </div>
                            </CardHeader>
                            <CardContent>
                                <div className="text-3xl font-bold">{stat.value}</div>
                                <p className="flex items-center gap-1 text-xs text-muted-foreground">
                                    <TrendingUp className="size-3" />
                                    Data belum tersedia
                                </p>
                            </CardContent>
                        </Card>
                    ))}
                </div>

                {/* Admin Quick Links */}
                <Card>
                    <CardHeader>
                        <CardTitle>Menu Administrasi</CardTitle>
                        <CardDescription>Kelola pengaturan global yayasan</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <div className="grid gap-4 md:grid-cols-2">
                            {adminLinks.map((link) => (
                                <a
                                    key={link.title}
                                    href={link.href}
                                    className="flex items-start gap-4 rounded-xl border p-4 transition-all hover:bg-muted/50 hover:shadow-sm"
                                >
                                    <div className="rounded-lg bg-primary/10 p-3">
                                        <link.icon className="size-6 text-primary" />
                                    </div>
                                    <div>
                                        <div className="font-semibold">{link.title}</div>
                                        <div className="text-sm text-muted-foreground">{link.desc}</div>
                                    </div>
                                </a>
                            ))}
                        </div>
                    </CardContent>
                </Card>

                {/* Summary Cards */}
                <div className="grid gap-4 md:grid-cols-3">
                    <Card>
                        <CardHeader>
                            <CardTitle className="text-base">Lembaga Aktif</CardTitle>
                        </CardHeader>
                        <CardContent>
                            <div className="flex h-24 items-center justify-center text-muted-foreground">
                                Memuat data...
                            </div>
                        </CardContent>
                    </Card>
                    <Card>
                        <CardHeader>
                            <CardTitle className="text-base">Tahun Ajaran Aktif</CardTitle>
                        </CardHeader>
                        <CardContent>
                            <div className="flex h-24 items-center justify-center text-muted-foreground">
                                Belum diatur
                            </div>
                        </CardContent>
                    </Card>
                    <Card>
                        <CardHeader>
                            <CardTitle className="text-base">Periode Fiskal Aktif</CardTitle>
                        </CardHeader>
                        <CardContent>
                            <div className="flex h-24 items-center justify-center text-muted-foreground">
                                Belum diatur
                            </div>
                        </CardContent>
                    </Card>
                </div>
            </div>
        </AppLayout>
    );
}
