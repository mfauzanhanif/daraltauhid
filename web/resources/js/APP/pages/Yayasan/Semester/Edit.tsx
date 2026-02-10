import { Head, Link, useForm, usePage } from '@inertiajs/react';
import { BookOpen } from 'lucide-react';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/shared/ui/card';
import { Button } from '@/shared/ui/button';
import { Input } from '@/shared/ui/input';
import { Label } from '@/shared/ui/label';
import { Switch } from '@/shared/ui/switch';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/shared/ui/select';
import AppLayout from '@/APP/layouts/app-layout';
import type { BreadcrumbItem, SharedData } from '@/types';
import { index as semestersIndex, edit as semestersEdit, update as semestersUpdate } from '@/routes/semesters';
import { dashboard } from '@/routes/portal';

type AcademicYear = {
    id: number;
    name: string;
};

type AcademicPeriod = {
    id: number;
    name: string;
    academic_year_id: number;
    type: string;
    start_date: string;
    end_date: string;
    is_active: boolean;
};

type Props = {
    academicPeriod: AcademicPeriod;
    academicYears: AcademicYear[];
};

export default function SemesterEdit({ academicPeriod, academicYears = [] }: Props) {
    const { currentPortal } = usePage<SharedData>().props;
    const code = currentPortal?.code ?? '';

    const breadcrumbs: BreadcrumbItem[] = [
        { title: 'Admin Yayasan', href: dashboard.url(code) },
        { title: 'Semester', href: semestersIndex.url(code) },
        { title: 'Edit', href: semestersEdit.url({ institution: code, semester: academicPeriod.id }) },
    ];

    const { data, setData, put, processing, errors } = useForm({
        name: academicPeriod.name,
        academic_year_id: String(academicPeriod.academic_year_id),
        type: academicPeriod.type,
        start_date: academicPeriod.start_date,
        end_date: academicPeriod.end_date,
        is_active: academicPeriod.is_active,
    });

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        put(semestersUpdate.url({ institution: code, semester: academicPeriod.id }));
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={`Edit - ${academicPeriod.name}`} />

            <div className="flex flex-col gap-6 p-6">
                {/* Header */}
                <div className="flex items-center gap-3">
                    <div className="flex size-12 items-center justify-center rounded-xl bg-purple-100 dark:bg-purple-900/30">
                        <BookOpen className="size-6 text-purple-600" />
                    </div>
                    <div>
                        <h1 className="text-2xl font-bold">Edit Semester</h1>
                        <p className="text-muted-foreground">{academicPeriod.name}</p>
                    </div>
                </div>

                <form onSubmit={handleSubmit}>
                    <div className="grid gap-6 lg:grid-cols-3">
                        <div className="lg:col-span-2">
                            <Card>
                                <CardHeader>
                                    <CardTitle>Informasi Semester</CardTitle>
                                    <CardDescription>Semester terkait dengan tahun ajaran</CardDescription>
                                </CardHeader>
                                <CardContent className="space-y-4">
                                    <div className="space-y-2">
                                        <Label htmlFor="name">Nama Semester *</Label>
                                        <Input
                                            id="name"
                                            placeholder="Semester Ganjil 2024/2025"
                                            value={data.name}
                                            onChange={(e) => setData('name', e.target.value)}
                                        />
                                        {errors.name && <p className="text-sm text-destructive">{errors.name}</p>}
                                    </div>
                                    <div className="grid gap-4 md:grid-cols-2">
                                        <div className="space-y-2">
                                            <Label htmlFor="academic_year_id">Tahun Ajaran *</Label>
                                            <Select value={data.academic_year_id} onValueChange={(v) => setData('academic_year_id', v)}>
                                                <SelectTrigger>
                                                    <SelectValue placeholder="Pilih Tahun Ajaran" />
                                                </SelectTrigger>
                                                <SelectContent>
                                                    {academicYears.map((year) => (
                                                        <SelectItem key={year.id} value={String(year.id)}>
                                                            {year.name}
                                                        </SelectItem>
                                                    ))}
                                                </SelectContent>
                                            </Select>
                                            {errors.academic_year_id && <p className="text-sm text-destructive">{errors.academic_year_id}</p>}
                                        </div>
                                        <div className="space-y-2">
                                            <Label htmlFor="type">Tipe *</Label>
                                            <Select value={data.type} onValueChange={(v) => setData('type', v)}>
                                                <SelectTrigger>
                                                    <SelectValue placeholder="Pilih Tipe" />
                                                </SelectTrigger>
                                                <SelectContent>
                                                    <SelectItem value="ODD">Ganjil</SelectItem>
                                                    <SelectItem value="EVEN">Genap</SelectItem>
                                                </SelectContent>
                                            </Select>
                                            {errors.type && <p className="text-sm text-destructive">{errors.type}</p>}
                                        </div>
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
                                            <Label htmlFor="is_active">Aktifkan Semester</Label>
                                            <p className="text-sm text-muted-foreground">
                                                Hanya boleh ada satu semester aktif
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
                                            <Link href={semestersIndex.url(code)}>Batal</Link>
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
