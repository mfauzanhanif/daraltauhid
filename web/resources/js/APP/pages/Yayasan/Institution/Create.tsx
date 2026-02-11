import { Head, Link, useForm, usePage } from '@inertiajs/react';
import { Building2 } from 'lucide-react';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/shared/ui/card';
import { Button } from '@/shared/ui/button';
import { Input } from '@/shared/ui/input';
import { Label } from '@/shared/ui/label';
import { Textarea } from '@/shared/ui/textarea';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/shared/ui/select';
import { Switch } from '@/shared/ui/switch';
import AppLayout from '@/APP/layouts/app-layout';
import type { BreadcrumbItem, SharedData } from '@/types';
import { index as institutionsIndex, create as institutionsCreate, store as institutionsStore } from '@/routes/institutions';
import { dashboard } from '@/routes/portal';

export default function InstitutionCreate() {
    const { currentPortal } = usePage<SharedData>().props;
    const code = currentPortal?.code ?? '';

    const breadcrumbs: BreadcrumbItem[] = [
        { title: 'Admin Yayasan', href: dashboard.url(code) },
        { title: 'Lembaga', href: institutionsIndex.url(code) },
        { title: 'Tambah', href: institutionsCreate.url(code) },
    ];

    const { data, setData, post, processing, errors } = useForm({
        code: '',
        name: '',
        nickname: '',
        type: '',
        category: '',
        address: '',
        phone: '',
        email: '',
        is_internal: true,
        is_active: true,
    });

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        post(institutionsStore.url(code));
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Tambah Lembaga" />

            <div className="flex flex-col gap-6 p-6">
                {/* Header */}
                <div className="flex items-center gap-3">
                    <div className="flex size-12 items-center justify-center rounded-xl bg-emerald-100 dark:bg-emerald-900/30">
                        <Building2 className="size-6 text-emerald-600" />
                    </div>
                    <div>
                        <h1 className="text-2xl font-bold">Tambah Lembaga Baru</h1>
                        <p className="text-muted-foreground">Daftarkan lembaga baru ke dalam sistem</p>
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
                                                placeholder="PPDT"
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
                                                placeholder="Pondok Pesantren"
                                                value={data.nickname}
                                                onChange={(e) => setData('nickname', e.target.value)}
                                            />
                                        </div>
                                    </div>
                                    <div className="space-y-2">
                                        <Label htmlFor="name">Nama Lengkap *</Label>
                                        <Input
                                            id="name"
                                            placeholder="Pondok Pesantren Dar Al Tauhid"
                                            value={data.name}
                                            onChange={(e) => setData('name', e.target.value)}
                                        />
                                        {errors.name && <p className="text-sm text-destructive">{errors.name}</p>}
                                    </div>
                                    <div className="grid gap-4 md:grid-cols-2">
                                        <div className="space-y-2">
                                            <Label htmlFor="is_internal">Status Kepemilikan *</Label>
                                            <Select
                                                value={data.is_internal ? 'true' : 'false'}
                                                onValueChange={(v) => setData('is_internal', v === 'true')}
                                            >
                                                <SelectTrigger>
                                                    <SelectValue placeholder="Pilih status" />
                                                </SelectTrigger>
                                                <SelectContent>
                                                    <SelectItem value="true">Internal (Milik Sendiri)</SelectItem>
                                                    <SelectItem value="false">Eksternal (Mitra/Binaan)</SelectItem>
                                                </SelectContent>
                                            </Select>
                                            {errors.is_internal && <p className="text-sm text-destructive">{errors.is_internal}</p>}
                                        </div>

                                        <div className="space-y-2">
                                            <Label htmlFor="category">Kategori Lembaga *</Label>
                                            <Select value={data.category} onValueChange={(v) => setData('category', v)}>
                                                <SelectTrigger>
                                                    <SelectValue placeholder="Pilih kategori" />
                                                </SelectTrigger>
                                                <SelectContent>
                                                    <SelectItem value="YAYASAN">Yayasan</SelectItem>
                                                    <SelectItem value="PONDOK">Pondok Pesantren</SelectItem>
                                                    <SelectItem value="FORMAL">Pendidikan Formal</SelectItem>
                                                    <SelectItem value="NON_FORMAL">Pendidikan Non-Formal</SelectItem>
                                                    <SelectItem value="SOSIAL">Lembaga Sosial</SelectItem>
                                                </SelectContent>
                                            </Select>
                                            {errors.category && <p className="text-sm text-destructive">{errors.category}</p>}
                                        </div>

                                        <div className="space-y-2 md:col-span-2">
                                            <Label htmlFor="type">Tipe / Jenjang *</Label>
                                            <Select value={data.type} onValueChange={(v) => setData('type', v)}>
                                                <SelectTrigger>
                                                    <SelectValue placeholder="Pilih tipe/jenjang" />
                                                </SelectTrigger>
                                                <SelectContent>
                                                    <SelectItem value="YAYASAN">Yayasan</SelectItem>
                                                    <SelectItem value="PONDOK">Pondok Pesantren</SelectItem>
                                                    <SelectItem value="TK">TK (Taman Kanak-Kanak)</SelectItem>
                                                    <SelectItem value="SD">SD (Sekolah Dasar)</SelectItem>
                                                    <SelectItem value="MI">MI (Madrasah Ibtidaiyah)</SelectItem>
                                                    <SelectItem value="SMP">SMP (Sekolah Menengah Pertama)</SelectItem>
                                                    <SelectItem value="MTS">MTs (Madrasah Tsanawiyah)</SelectItem>
                                                    <SelectItem value="SMA">SMA (Sekolah Menengah Atas)</SelectItem>
                                                    <SelectItem value="MA">MA (Madrasah Aliyah)</SelectItem>
                                                    <SelectItem value="SMK">SMK (Sekolah Menengah Kejuruan)</SelectItem>
                                                    <SelectItem value="SLB">SLB (Sekolah Luar Biasa)</SelectItem>
                                                    <SelectItem value="MDTA">MDTA (Madrasah Diniyah Takmiliyah Awaliyah)</SelectItem>
                                                    <SelectItem value="TPQ">TPQ (Taman Pendidikan Al-Qur'an)</SelectItem>
                                                    <SelectItem value="Madrasah">Madrasah (Umum)</SelectItem>
                                                    <SelectItem value="LKSA">LKSA (Lembaga Kesejahteraan Sosial Anak)</SelectItem>
                                                </SelectContent>
                                            </Select>
                                            {errors.type && <p className="text-sm text-destructive">{errors.type}</p>}
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
                                            placeholder="Alamat lengkap lembaga"
                                            value={data.address}
                                            onChange={(e) => setData('address', e.target.value)}
                                        />
                                    </div>
                                    <div className="grid gap-4 md:grid-cols-2">
                                        <div className="space-y-2">
                                            <Label htmlFor="phone">Telepon</Label>
                                            <Input
                                                id="phone"
                                                placeholder="0231-123456"
                                                value={data.phone}
                                                onChange={(e) => setData('phone', e.target.value)}
                                            />
                                        </div>
                                        <div className="space-y-2">
                                            <Label htmlFor="email">Email</Label>
                                            <Input
                                                id="email"
                                                type="email"
                                                placeholder="lembaga@daraltauhid.com"
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
                                    <p className="mt-2 text-sm text-muted-foreground">
                                        Lembaga aktif akan muncul di portal dan dapat diakses oleh pengguna.
                                    </p>
                                </CardContent>
                            </Card>

                            <Card>
                                <CardContent className="pt-6">
                                    <div className="flex flex-col gap-2">
                                        <Button type="submit" disabled={processing}>
                                            {processing ? 'Menyimpan...' : 'Simpan Lembaga'}
                                        </Button>
                                        <Button variant="outline" asChild>
                                            <Link href={institutionsIndex.url(code)}>Batal</Link>
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
