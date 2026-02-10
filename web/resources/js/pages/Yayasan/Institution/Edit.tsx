import { Head, Link, useForm } from '@inertiajs/react';
import { Building2 } from 'lucide-react';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { Switch } from '@/components/ui/switch';
import AppLayout from '@/layouts/app-layout';
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
};

type Props = {
    institution: Institution;
};

export default function InstitutionEdit({ institution }: Props) {
    const breadcrumbs: BreadcrumbItem[] = [
        { title: 'Admin Yayasan', href: '/admin/dashboard' },
        { title: 'Lembaga', href: '/admin/institutions' },
        { title: institution.code, href: `/admin/institutions/${institution.id}` },
        { title: 'Edit', href: `/admin/institutions/${institution.id}/edit` },
    ];

    const { data, setData, put, processing, errors } = useForm({
        code: institution.code,
        name: institution.name,
        nickname: institution.nickname || '',
        type: institution.type,
        category: institution.category,
        address: institution.address || '',
        phone: institution.phone || '',
        email: institution.email || '',
        is_active: institution.is_active,
    });

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        put(`/admin/institutions/${institution.id}`);
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={`Edit - ${institution.name}`} />

            <div className="flex flex-col gap-6 p-6">
                {/* Header */}
                <div className="flex items-center gap-3">
                    <div className="flex size-12 items-center justify-center rounded-xl bg-emerald-100 dark:bg-emerald-900/30">
                        <Building2 className="size-6 text-emerald-600" />
                    </div>
                    <div>
                        <h1 className="text-2xl font-bold">Edit Lembaga</h1>
                        <p className="text-muted-foreground">{institution.name}</p>
                    </div>
                </div>

                <form onSubmit={handleSubmit}>
                    <div className="grid gap-6 lg:grid-cols-3">
                        {/* Main Form */}
                        <div className="lg:col-span-2 space-y-6">
                            <Card>
                                <CardHeader>
                                    <CardTitle>Informasi Dasar</CardTitle>
                                    <CardDescription>Data identitas lembaga</CardDescription>
                                </CardHeader>
                                <CardContent className="space-y-4">
                                    <div className="grid gap-4 md:grid-cols-2">
                                        <div className="space-y-2">
                                            <Label htmlFor="code">Kode Lembaga *</Label>
                                            <Input
                                                id="code"
                                                value={data.code}
                                                onChange={(e) => setData('code', e.target.value.toUpperCase())}
                                                className="font-mono"
                                            />
                                            {errors.code && <p className="text-sm text-destructive">{errors.code}</p>}
                                        </div>
                                        <div className="space-y-2">
                                            <Label htmlFor="nickname">Nama Singkat</Label>
                                            <Input
                                                id="nickname"
                                                value={data.nickname}
                                                onChange={(e) => setData('nickname', e.target.value)}
                                            />
                                        </div>
                                    </div>
                                    <div className="space-y-2">
                                        <Label htmlFor="name">Nama Lengkap *</Label>
                                        <Input
                                            id="name"
                                            value={data.name}
                                            onChange={(e) => setData('name', e.target.value)}
                                        />
                                        {errors.name && <p className="text-sm text-destructive">{errors.name}</p>}
                                    </div>
                                    <div className="grid gap-4 md:grid-cols-2">
                                        <div className="space-y-2">
                                            <Label htmlFor="type">Tipe Lembaga *</Label>
                                            <Select value={data.type} onValueChange={(v) => setData('type', v)}>
                                                <SelectTrigger>
                                                    <SelectValue placeholder="Pilih tipe" />
                                                </SelectTrigger>
                                                <SelectContent>
                                                    <SelectItem value="PONDOK">Pondok Pesantren</SelectItem>
                                                    <SelectItem value="FORMAL">Pendidikan Formal</SelectItem>
                                                    <SelectItem value="NON_FORMAL">Pendidikan Non-Formal</SelectItem>
                                                    <SelectItem value="SOSIAL">Lembaga Sosial</SelectItem>
                                                </SelectContent>
                                            </Select>
                                        </div>
                                        <div className="space-y-2">
                                            <Label htmlFor="category">Kategori *</Label>
                                            <Select value={data.category} onValueChange={(v) => setData('category', v)}>
                                                <SelectTrigger>
                                                    <SelectValue placeholder="Pilih kategori" />
                                                </SelectTrigger>
                                                <SelectContent>
                                                    <SelectItem value="INTERNAL">Internal (Milik Sendiri)</SelectItem>
                                                    <SelectItem value="EXTERNAL">Eksternal (Mitra/Binaan)</SelectItem>
                                                </SelectContent>
                                            </Select>
                                        </div>
                                    </div>
                                </CardContent>
                            </Card>

                            <Card>
                                <CardHeader>
                                    <CardTitle>Kontak</CardTitle>
                                    <CardDescription>Informasi kontak lembaga</CardDescription>
                                </CardHeader>
                                <CardContent className="space-y-4">
                                    <div className="space-y-2">
                                        <Label htmlFor="address">Alamat</Label>
                                        <Textarea
                                            id="address"
                                            value={data.address}
                                            onChange={(e) => setData('address', e.target.value)}
                                        />
                                    </div>
                                    <div className="grid gap-4 md:grid-cols-2">
                                        <div className="space-y-2">
                                            <Label htmlFor="phone">Telepon</Label>
                                            <Input
                                                id="phone"
                                                value={data.phone}
                                                onChange={(e) => setData('phone', e.target.value)}
                                            />
                                        </div>
                                        <div className="space-y-2">
                                            <Label htmlFor="email">Email</Label>
                                            <Input
                                                id="email"
                                                type="email"
                                                value={data.email}
                                                onChange={(e) => setData('email', e.target.value)}
                                            />
                                        </div>
                                    </div>
                                </CardContent>
                            </Card>
                        </div>

                        {/* Sidebar */}
                        <div className="space-y-6">
                            <Card>
                                <CardHeader>
                                    <CardTitle>Status</CardTitle>
                                </CardHeader>
                                <CardContent>
                                    <div className="flex items-center justify-between">
                                        <Label htmlFor="is_active">Aktifkan Lembaga</Label>
                                        <Switch
                                            id="is_active"
                                            checked={data.is_active}
                                            onCheckedChange={(checked) => setData('is_active', checked)}
                                        />
                                    </div>
                                </CardContent>
                            </Card>

                            <Card>
                                <CardContent className="pt-6">
                                    <div className="flex flex-col gap-2">
                                        <Button type="submit" disabled={processing}>
                                            {processing ? 'Menyimpan...' : 'Simpan Perubahan'}
                                        </Button>
                                        <Button variant="outline" asChild>
                                            <Link href="/admin/institutions">Batal</Link>
                                        </Button>
                                    </div>
                                </CardContent>
                            </Card>
                        </div>
                    </div>
                </form>
            </div>
        </AppLayout>
    );
}
