import { Head, usePage } from '@inertiajs/react';
import { Building2, Users, BookOpen, DollarSign, Package, Settings } from 'lucide-react';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/shared/ui/card';
import AppLayout from '@/APP/layouts/app-layout';
import type { BreadcrumbItem } from '@/types';

interface Institution {
    id: number;
    code: string;
    name: string;
    type: string;
    category: string;
    logo_path?: string;
}

interface Props {
    institution: Institution | null;
}

export default function PortalDashboard({ institution }: Props) {
    const breadcrumbs: BreadcrumbItem[] = [
        {
            title: institution?.name || 'Dashboard',
            href: `/${institution?.code}/dashboard`,
        },
    ];

    const quickStats = [
        { title: 'Total Siswa', value: '—', icon: Users, color: 'text-blue-600' },
        { title: 'Total Guru', value: '—', icon: BookOpen, color: 'text-emerald-600' },
        { title: 'Keuangan', value: '—', icon: DollarSign, color: 'text-amber-600' },
        { title: 'Aset', value: '—', icon: Package, color: 'text-purple-600' },
    ];

    const quickLinks = [
        { title: 'Manajemen Siswa', href: `/${institution?.code}/academic/students`, icon: Users },
        { title: 'Manajemen Guru', href: `/${institution?.code}/employees`, icon: BookOpen },
        { title: 'Keuangan', href: `/${institution?.code}/finance`, icon: DollarSign },
        { title: 'Pengaturan', href: `/${institution?.code}/settings`, icon: Settings },
    ];

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={`Dashboard - ${institution?.name || 'Portal'}`} />

            <div className="flex flex-col gap-6 p-6">
                {/* Welcome Banner */}
                <Card className="bg-gradient-to-r from-primary/10 via-primary/5 to-transparent border-primary/20">
                    <CardHeader>
                        <div className="flex items-center gap-4">
                            <div className="flex size-16 items-center justify-center rounded-xl bg-primary text-primary-foreground font-bold text-2xl">
                                {institution?.code?.substring(0, 2).toUpperCase() || 'SA'}
                            </div>
                            <div>
                                <CardTitle className="text-2xl">{institution?.name || 'Dashboard'}</CardTitle>
                                <CardDescription className="text-base">
                                    {institution?.type} • {institution?.category}
                                </CardDescription>
                            </div>
                        </div>
                    </CardHeader>
                </Card>

                {/* Quick Stats */}
                <div className="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
                    {quickStats.map((stat) => (
                        <Card key={stat.title}>
                            <CardHeader className="flex flex-row items-center justify-between pb-2">
                                <CardTitle className="text-sm font-medium text-muted-foreground">
                                    {stat.title}
                                </CardTitle>
                                <stat.icon className={`size-5 ${stat.color}`} />
                            </CardHeader>
                            <CardContent>
                                <div className="text-2xl font-bold">{stat.value}</div>
                                <p className="text-xs text-muted-foreground">Data belum tersedia</p>
                            </CardContent>
                        </Card>
                    ))}
                </div>

                {/* Quick Links */}
                <Card>
                    <CardHeader>
                        <CardTitle>Menu Cepat</CardTitle>
                        <CardDescription>Akses cepat ke fitur yang sering digunakan</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <div className="grid gap-3 md:grid-cols-2 lg:grid-cols-4">
                            {quickLinks.map((link) => (
                                <a
                                    key={link.title}
                                    href={link.href}
                                    className="flex items-center gap-3 rounded-lg border p-4 transition-colors hover:bg-muted/50"
                                >
                                    <link.icon className="size-5 text-muted-foreground" />
                                    <span className="font-medium">{link.title}</span>
                                </a>
                            ))}
                        </div>
                    </CardContent>
                </Card>

                {/* Placeholder for future widgets */}
                <div className="grid gap-4 md:grid-cols-2">
                    <Card>
                        <CardHeader>
                            <CardTitle>Aktivitas Terbaru</CardTitle>
                        </CardHeader>
                        <CardContent>
                            <div className="flex h-32 items-center justify-center text-muted-foreground">
                                Belum ada aktivitas
                            </div>
                        </CardContent>
                    </Card>
                    <Card>
                        <CardHeader>
                            <CardTitle>Pengumuman</CardTitle>
                        </CardHeader>
                        <CardContent>
                            <div className="flex h-32 items-center justify-center text-muted-foreground">
                                Belum ada pengumuman
                            </div>
                        </CardContent>
                    </Card>
                </div>
            </div>
        </AppLayout>
    );
}
