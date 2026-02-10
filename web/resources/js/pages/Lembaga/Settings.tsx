import { Head, usePage } from '@inertiajs/react';
import { Settings, Building2, Save, MapPin, Phone, Mail, Palette } from 'lucide-react';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import AppLayout from '@/layouts/app-layout';
import type { BreadcrumbItem, SharedData } from '@/types';

type Institution = {
    id: number;
    code: string;
    name: string;
    nickname?: string;
    address?: string;
    phone?: string;
    email?: string;
    logo_path?: string;
    theme_color?: string;
};

type Props = {
    institution: Institution;
};

export default function LembagaSettings({ institution }: Props) {
    const { currentPortal } = usePage<SharedData>().props;

    const breadcrumbs: BreadcrumbItem[] = [
        { title: currentPortal?.name || 'Lembaga', href: currentPortal?.dashboardUrl || '/' },
        { title: 'Pengaturan', href: `/${institution.code}/settings` },
    ];

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={`Pengaturan - ${institution.name}`} />

            <div className="flex flex-col gap-6 p-6">
                {/* Header */}
                <div className="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                    <div className="flex items-center gap-3">
                        <div className="flex size-12 items-center justify-center rounded-xl bg-slate-100 dark:bg-slate-900/30">
                            <Settings className="size-6 text-slate-600" />
                        </div>
                        <div>
                            <h1 className="text-2xl font-bold">Pengaturan Lembaga</h1>
                            <p className="text-muted-foreground">{institution.name}</p>
                        </div>
                    </div>
                </div>

                <div className="grid gap-6 lg:grid-cols-3">
                    <div className="lg:col-span-2 space-y-6">
                        {/* Basic Info */}
                        <Card>
                            <CardHeader>
                                <CardTitle className="flex items-center gap-2">
                                    <Building2 className="size-5" />
                                    Informasi Lembaga
                                </CardTitle>
                                <CardDescription>Informasi dasar yang ditampilkan di portal</CardDescription>
                            </CardHeader>
                            <CardContent className="space-y-4">
                                <div className="grid gap-4 md:grid-cols-2">
                                    <div className="space-y-2">
                                        <Label>Kode Lembaga</Label>
                                        <Input value={institution.code} disabled className="font-mono" />
                                    </div>
                                    <div className="space-y-2">
                                        <Label>Nama Singkat</Label>
                                        <Input defaultValue={institution.nickname || ''} />
                                    </div>
                                </div>
                                <div className="space-y-2">
                                    <Label>Nama Lengkap</Label>
                                    <Input defaultValue={institution.name} />
                                </div>
                            </CardContent>
                        </Card>

                        {/* Contact */}
                        <Card>
                            <CardHeader>
                                <CardTitle className="flex items-center gap-2">
                                    <Phone className="size-5" />
                                    Kontak
                                </CardTitle>
                            </CardHeader>
                            <CardContent className="space-y-4">
                                <div className="space-y-2">
                                    <Label className="flex items-center gap-2">
                                        <MapPin className="size-4" />
                                        Alamat
                                    </Label>
                                    <Textarea defaultValue={institution.address || ''} />
                                </div>
                                <div className="grid gap-4 md:grid-cols-2">
                                    <div className="space-y-2">
                                        <Label className="flex items-center gap-2">
                                            <Phone className="size-4" />
                                            Telepon
                                        </Label>
                                        <Input defaultValue={institution.phone || ''} />
                                    </div>
                                    <div className="space-y-2">
                                        <Label className="flex items-center gap-2">
                                            <Mail className="size-4" />
                                            Email
                                        </Label>
                                        <Input type="email" defaultValue={institution.email || ''} />
                                    </div>
                                </div>
                            </CardContent>
                        </Card>

                        {/* Appearance */}
                        <Card>
                            <CardHeader>
                                <CardTitle className="flex items-center gap-2">
                                    <Palette className="size-5" />
                                    Tampilan
                                </CardTitle>
                                <CardDescription>Kustomisasi tampilan portal lembaga</CardDescription>
                            </CardHeader>
                            <CardContent className="space-y-4">
                                <div className="space-y-2">
                                    <Label>Warna Tema</Label>
                                    <div className="flex items-center gap-3">
                                        <Input
                                            type="color"
                                            defaultValue={institution.theme_color || '#10b981'}
                                            className="h-10 w-20 cursor-pointer"
                                        />
                                        <span className="text-sm text-muted-foreground">
                                            Warna utama untuk portal lembaga
                                        </span>
                                    </div>
                                </div>
                                <div className="space-y-2">
                                    <Label>Logo Lembaga</Label>
                                    <div className="flex items-center gap-4">
                                        <div className="flex size-20 items-center justify-center rounded-lg border-2 border-dashed">
                                            {institution.logo_path ? (
                                                <img src={institution.logo_path} alt="Logo" className="size-16 object-contain" />
                                            ) : (
                                                <Building2 className="size-8 text-muted-foreground" />
                                            )}
                                        </div>
                                        <Button variant="outline" type="button">Upload Logo</Button>
                                    </div>
                                </div>
                            </CardContent>
                        </Card>
                    </div>

                    {/* Sidebar */}
                    <div>
                        <Card className="sticky top-6">
                            <CardContent className="pt-6">
                                <Button className="w-full">
                                    <Save className="mr-2 size-4" />
                                    Simpan Pengaturan
                                </Button>
                            </CardContent>
                        </Card>
                    </div>
                </div>
            </div>
        </AppLayout>
    );
}
