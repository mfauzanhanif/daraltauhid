import { Head, Link } from '@inertiajs/react';
import { Calendar, Plus, MoreHorizontal, Pencil, Trash2, Check } from 'lucide-react';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import AppLayout from '@/layouts/app-layout';
import type { BreadcrumbItem } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Admin Yayasan', href: '/admin/dashboard' },
    { title: 'Tahun Ajaran', href: '/admin/academic-years' },
];

type AcademicYear = {
    id: number;
    name: string;
    start_date: string;
    end_date: string;
    is_active: boolean;
};

type Props = {
    academicYears: AcademicYear[];
};

export default function AcademicYearIndex({ academicYears = [] }: Props) {
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Tahun Ajaran" />

            <div className="flex flex-col gap-6 p-6">
                {/* Header */}
                <div className="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                    <div className="flex items-center gap-3">
                        <div className="flex size-12 items-center justify-center rounded-xl bg-blue-100 dark:bg-blue-900/30">
                            <Calendar className="size-6 text-blue-600" />
                        </div>
                        <div>
                            <h1 className="text-2xl font-bold">Tahun Ajaran</h1>
                            <p className="text-muted-foreground">Kelola tahun ajaran akademik (Juli - Juni)</p>
                        </div>
                    </div>
                    <Button asChild>
                        <Link href="/admin/academic-years/create">
                            <Plus className="mr-2 size-4" />
                            Tambah Tahun Ajaran
                        </Link>
                    </Button>
                </div>

                {/* Table */}
                <Card>
                    <CardHeader>
                        <CardTitle>Daftar Tahun Ajaran</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <Table>
                            <TableHeader>
                                <TableRow>
                                    <TableHead>Tahun Ajaran</TableHead>
                                    <TableHead>Periode</TableHead>
                                    <TableHead>Status</TableHead>
                                    <TableHead className="w-[80px]"></TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                {academicYears.length === 0 ? (
                                    <TableRow>
                                        <TableCell colSpan={4} className="h-32 text-center text-muted-foreground">
                                            Belum ada data tahun ajaran
                                        </TableCell>
                                    </TableRow>
                                ) : (
                                    academicYears.map((year) => (
                                        <TableRow key={year.id}>
                                            <TableCell className="font-medium">{year.name}</TableCell>
                                            <TableCell>
                                                {new Date(year.start_date).toLocaleDateString('id-ID')} - {new Date(year.end_date).toLocaleDateString('id-ID')}
                                            </TableCell>
                                            <TableCell>
                                                {year.is_active ? (
                                                    <Badge className="bg-emerald-100 text-emerald-800">
                                                        <Check className="mr-1 size-3" />
                                                        Aktif
                                                    </Badge>
                                                ) : (
                                                    <Badge variant="secondary">Tidak Aktif</Badge>
                                                )}
                                            </TableCell>
                                            <TableCell>
                                                <DropdownMenu>
                                                    <DropdownMenuTrigger asChild>
                                                        <Button variant="ghost" size="icon">
                                                            <MoreHorizontal className="size-4" />
                                                        </Button>
                                                    </DropdownMenuTrigger>
                                                    <DropdownMenuContent align="end">
                                                        <DropdownMenuItem asChild>
                                                            <Link href={`/admin/academic-years/${year.id}/edit`}>
                                                                <Pencil className="mr-2 size-4" />
                                                                Edit
                                                            </Link>
                                                        </DropdownMenuItem>
                                                        <DropdownMenuItem className="text-destructive">
                                                            <Trash2 className="mr-2 size-4" />
                                                            Hapus
                                                        </DropdownMenuItem>
                                                    </DropdownMenuContent>
                                                </DropdownMenu>
                                            </TableCell>
                                        </TableRow>
                                    ))
                                )}
                            </TableBody>
                        </Table>
                    </CardContent>
                </Card>
            </div>
        </AppLayout>
    );
}
