import { Head, Link, useForm } from '@inertiajs/react';
import { Calendar } from 'lucide-react';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/shared/ui/card';
import { Button } from '@/shared/ui/button';
import { Input } from '@/shared/ui/input';
import { Label } from '@/shared/ui/label';
import { Switch } from '@/shared/ui/switch';
import AppLayout from '@/APP/layouts/app-layout';
import type { BreadcrumbItem } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Admin Yayasan', href: '/admin/dashboard' },
    { title: 'Tahun Ajaran', href: '/admin/academic-years' },
    { title: 'Tambah', href: '/admin/academic-years/create' },
];

export default function AcademicYearCreate() {
    const { data, setData, post, processing, errors } = useForm({
        name: '',
        start_date: '',
        end_date: '',
        is_active: false,
    });

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        post('/admin/academic-years');
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Tambah Tahun Ajaran" />

            <div className="flex flex-col gap-6 p-6">
                {/* Header */}
                <div className="flex items-center gap-3">
                    <div className="flex size-12 items-center justify-center rounded-xl bg-blue-100 dark:bg-blue-900/30">
                        <Calendar className="size-6 text-blue-600" />
                    </div>
                    <div>
                        <h1 className="text-2xl font-bold">Tambah Tahun Ajaran</h1>
                        <p className="text-muted-foreground">Buat tahun ajaran baru</p>
                    </div>
                </div>

                <form onSubmit={handleSubmit}>
                    <div className="grid gap-6 lg:grid-cols-3">
                        <div className="lg:col-span-2">
                            <Card>
                                <CardHeader>
                                    <CardTitle>Informasi Tahun Ajaran</CardTitle>
                                    <CardDescription>Periode tahun ajaran mengikuti kalender akademik (Juli - Juni)</CardDescription>
                                </CardHeader>
                                <CardContent className="space-y-4">
                                    <div className="space-y-2">
                                        <Label htmlFor="name">Nama Tahun Ajaran *</Label>
                                        <Input
                                            id="name"
                                            placeholder="2024/2025"
                                            value={data.name}
                                            onChange={(e) => setData('name', e.target.value)}
                                        />
                                        {errors.name && <p className="text-sm text-destructive">{errors.name}</p>}
                                    </div>
                                    <div className="grid gap-4 md:grid-cols-2">
                                        <div className="space-y-2">
                                            <Label htmlFor="start_date">Tanggal Mulai *</Label>
                                            <Input
                                                id="start_date"
                                                type="date"
                                                value={data.start_date}
                                                onChange={(e) => setData('start_date', e.target.value)}
                                            />
                                            {errors.start_date && <p className="text-sm text-destructive">{errors.start_date}</p>}
                                        </div>
                                        <div className="space-y-2">
                                            <Label htmlFor="end_date">Tanggal Selesai *</Label>
                                            <Input
                                                id="end_date"
                                                type="date"
                                                value={data.end_date}
                                                onChange={(e) => setData('end_date', e.target.value)}
                                            />
                                            {errors.end_date && <p className="text-sm text-destructive">{errors.end_date}</p>}
                                        </div>
                                    </div>
                                    <div className="flex items-center justify-between rounded-lg border p-4">
                                        <div>
                                            <Label htmlFor="is_active">Aktifkan Tahun Ajaran</Label>
                                            <p className="text-sm text-muted-foreground">
                                                Hanya boleh ada satu tahun ajaran aktif
                                            </p>
                                        </div>
                                        <Switch
                                            id="is_active"
                                            checked={data.is_active}
                                            onCheckedChange={(checked) => setData('is_active', checked)}
                                        />
                                    </div>
                                </CardContent>
                            </Card>
                        </div>

                        <div>
                            <Card>
                                <CardContent className="pt-6">
                                    <div className="flex flex-col gap-2">
                                        <Button type="submit" disabled={processing}>
                                            {processing ? 'Menyimpan...' : 'Simpan'}
                                        </Button>
                                        <Button variant="outline" asChild>
                                            <Link href="/admin/academic-years">Batal</Link>
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
