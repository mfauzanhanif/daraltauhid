import { Head, Link, useForm, usePage } from '@inertiajs/react';
import { DollarSign } from 'lucide-react';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/shared/ui/card';
import { Button } from '@/shared/ui/button';
import { Input } from '@/shared/ui/input';
import { Label } from '@/shared/ui/label';
import { Switch } from '@/shared/ui/switch';
import AppLayout from '@/APP/layouts/app-layout';
import type { BreadcrumbItem, SharedData } from '@/types';
import { index as fiscalPeriodsIndex, edit as fiscalPeriodsEdit, update as fiscalPeriodsUpdate } from '@/routes/fiscal-periods';
import { dashboard } from '@/routes/portal';

type FiscalPeriod = {
    id: number;
    name: string;
    start_date: string;
    end_date: string;
    is_active: boolean;
};

type Props = {
    fiscalPeriod: FiscalPeriod;
};

export default function FiscalPeriodEdit({ fiscalPeriod }: Props) {
    const { currentPortal } = usePage<SharedData>().props;
    const code = currentPortal?.code ?? '';

    const breadcrumbs: BreadcrumbItem[] = [
        { title: 'Admin Yayasan', href: dashboard.url(code) },
        { title: 'Periode Fiskal', href: fiscalPeriodsIndex.url(code) },
        { title: 'Edit', href: fiscalPeriodsEdit.url({ institution: code, fiscal_period: fiscalPeriod.id }) },
    ];

    const { data, setData, put, processing, errors } = useForm({
        name: fiscalPeriod.name,
        start_date: fiscalPeriod.start_date,
        end_date: fiscalPeriod.end_date,
        is_active: fiscalPeriod.is_active,
    });

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        put(fiscalPeriodsUpdate.url({ institution: code, fiscal_period: fiscalPeriod.id }));
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={`Edit - ${fiscalPeriod.name}`} />

            <div className="flex flex-col gap-6 p-6">
                {/* Header */}
                <div className="flex items-center gap-3">
                    <div className="flex size-12 items-center justify-center rounded-xl bg-amber-100 dark:bg-amber-900/30">
                        <DollarSign className="size-6 text-amber-600" />
                    </div>
                    <div>
                        <h1 className="text-2xl font-bold">Edit Periode Fiskal</h1>
                        <p className="text-muted-foreground">{fiscalPeriod.name}</p>
                    </div>
                </div>

                <form onSubmit={handleSubmit}>
                    <div className="grid gap-6 lg:grid-cols-3">
                        <div className="lg:col-span-2">
                            <Card>
                                <CardHeader>
                                    <CardTitle>Informasi Periode Fiskal</CardTitle>
                                    <CardDescription>Periode tahun buku mengikuti kalender keuangan (Januari - Desember)</CardDescription>
                                </CardHeader>
                                <CardContent className="space-y-4">
                                    <div className="space-y-2">
                                        <Label htmlFor="name">Nama Periode *</Label>
                                        <Input
                                            id="name"
                                            placeholder="2024"
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
                                            <Label htmlFor="is_active">Aktifkan Periode</Label>
                                            <p className="text-sm text-muted-foreground">
                                                Hanya boleh ada satu periode fiskal aktif
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
                                            {processing ? 'Menyimpan...' : 'Simpan Perubahan'}
                                        </Button>
                                        <Button variant="outline" asChild>
                                            <Link href={fiscalPeriodsIndex.url(code)}>Batal</Link>
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
