import { Head, Link } from '@inertiajs/react';
import { Building2, Pencil, MapPin, Phone, Mail, Calendar, Users } from 'lucide-react';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/shared/ui/card';
import { Button } from '@/shared/ui/button';
import { Badge } from '@/shared/ui/badge';
import AppLayout from '@/APP/layouts/app-layout';
import type { BreadcrumbItem } from '@/types';

type Institution = {
    id: number;
    code: string;
    name: string;
    nickname?: string;
    type: string;
    category: string;
    address?: string;
    phone?: string;
    email?: string;
    is_active: boolean;
    created_at: string;
};

type Props = {
    institution: Institution;
};

export default function InstitutionShow({ institution }: Props) {
    const breadcrumbs: BreadcrumbItem[] = [
        { title: 'Admin Yayasan', href: '/admin/dashboard' },
        { title: 'Lembaga', href: '/admin/institutions' },
        { title: institution.code, href: `/admin/institutions/${institution.id}` },
    ];

    const getTypeBadge = (type: string) => {
        const colors: Record<string, string> = {
            'PONDOK': 'bg-emerald-100 text-emerald-800',
            'FORMAL': 'bg-blue-100 text-blue-800',
            'NON_FORMAL': 'bg-purple-100 text-purple-800',
            'SOSIAL': 'bg-amber-100 text-amber-800',
        };
        return colors[type] || 'bg-gray-100 text-gray-800';
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={institution.name} />

            <div className="flex flex-col gap-6 p-6">
                {/* Header */}
                <div className="flex flex-col gap-4 md:flex-row md:items-start md:justify-between">
                    <div className="flex items-start gap-4">
                        <div className="flex size-16 items-center justify-center rounded-xl bg-emerald-100 dark:bg-emerald-900/30 text-2xl font-bold text-emerald-700">
                            {institution.code.substring(0, 2)}
                        </div>
                        <div>
                            <div className="flex items-center gap-2">
                                <h1 className="text-2xl font-bold">{institution.name}</h1>
                                <Badge variant={institution.is_active ? 'default' : 'secondary'}>
                                    {institution.is_active ? 'Aktif' : 'Nonaktif'}
                                </Badge>
                            </div>
                            <p className="text-muted-foreground">Kode: {institution.code}</p>
                            <div className="mt-2 flex gap-2">
                                <Badge variant="outline" className={getTypeBadge(institution.type)}>
                                    {institution.type}
                                </Badge>
                                <Badge variant="outline">
                                    {institution.category === 'INTERNAL' ? 'Internal' : 'Eksternal'}
                                </Badge>
                            </div>
                        </div>
                    </div>
                    <Button asChild>
                        <Link href={`/admin/institutions/${institution.id}/edit`}>
                            <Pencil className="mr-2 size-4" />
                            Edit Lembaga
                        </Link>
                    </Button>
                </div>

                <div className="grid gap-6 lg:grid-cols-3">
                    {/* Info */}
                    <div className="lg:col-span-2 space-y-6">
                        <Card>
                            <CardHeader>
                                <CardTitle>Informasi Kontak</CardTitle>
                            </CardHeader>
                            <CardContent className="space-y-4">
                                {institution.address && (
                                    <div className="flex items-start gap-3">
                                        <MapPin className="size-5 text-muted-foreground mt-0.5" />
                                        <div>
                                            <p className="text-sm text-muted-foreground">Alamat</p>
                                            <p>{institution.address}</p>
                                        </div>
                                    </div>
                                )}
                                {institution.phone && (
                                    <div className="flex items-start gap-3">
                                        <Phone className="size-5 text-muted-foreground mt-0.5" />
                                        <div>
                                            <p className="text-sm text-muted-foreground">Telepon</p>
                                            <p>{institution.phone}</p>
                                        </div>
                                    </div>
                                )}
                                {institution.email && (
                                    <div className="flex items-start gap-3">
                                        <Mail className="size-5 text-muted-foreground mt-0.5" />
                                        <div>
                                            <p className="text-sm text-muted-foreground">Email</p>
                                            <p>{institution.email}</p>
                                        </div>
                                    </div>
                                )}
                                {!institution.address && !institution.phone && !institution.email && (
                                    <p className="text-muted-foreground">Belum ada informasi kontak</p>
                                )}
                            </CardContent>
                        </Card>

                        <Card>
                            <CardHeader>
                                <CardTitle>Akses Portal</CardTitle>
                                <CardDescription>URL akses dashboard lembaga</CardDescription>
                            </CardHeader>
                            <CardContent>
                                <div className="rounded-lg bg-muted p-4 font-mono text-sm">
                                    app.daraltauhid.com/{institution.code.toLowerCase()}/dashboard
                                </div>
                            </CardContent>
                        </Card>
                    </div>

                    {/* Sidebar Stats */}
                    <div className="space-y-6">
                        <Card>
                            <CardHeader>
                                <CardTitle className="text-base">Statistik</CardTitle>
                            </CardHeader>
                            <CardContent className="space-y-4">
                                <div className="flex items-center justify-between">
                                    <div className="flex items-center gap-2">
                                        <Users className="size-4 text-muted-foreground" />
                                        <span className="text-sm">Total Pengguna</span>
                                    </div>
                                    <span className="font-semibold">—</span>
                                </div>
                                <div className="flex items-center justify-between">
                                    <div className="flex items-center gap-2">
                                        <Calendar className="size-4 text-muted-foreground" />
                                        <span className="text-sm">Terdaftar</span>
                                    </div>
                                    <span className="text-sm text-muted-foreground">
                                        {new Date(institution.created_at).toLocaleDateString('id-ID')}
                                    </span>
                                </div>
                            </CardContent>
                        </Card>
                    </div>
                </div>
            </div>
        </AppLayout>
    );
}
