import { Head, Link } from '@inertiajs/react';
import { BookOpen, Plus, MoreHorizontal, Pencil, Trash2 } from 'lucide-react';
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
    { title: 'Semester', href: '/admin/semesters' },
];

type Semester = {
    id: number;
    name: string;
    academic_year_name: string;
    type: string;
    start_date: string;
    end_date: string;
    is_active: boolean;
};

type Props = {
    semesters: Semester[];
};

export default function SemesterIndex({ semesters = [] }: Props) {
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Semester" />

            <div className="flex flex-col gap-6 p-6">
                {/* Header */}
                <div className="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                    <div className="flex items-center gap-3">
                        <div className="flex size-12 items-center justify-center rounded-xl bg-purple-100 dark:bg-purple-900/30">
                            <BookOpen className="size-6 text-purple-600" />
                        </div>
                        <div>
                            <h1 className="text-2xl font-bold">Semester</h1>
                            <p className="text-muted-foreground">Kelola periode semester (Ganjil/Genap)</p>
                        </div>
                    </div>
                    <Button asChild>
                        <Link href="/admin/semesters/create">
                            <Plus className="mr-2 size-4" />
                            Tambah Semester
                        </Link>
                    </Button>
                </div>

                {/* Table */}
                <Card>
                    <CardHeader>
                        <CardTitle>Daftar Semester</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <Table>
                            <TableHeader>
                                <TableRow>
                                    <TableHead>Semester</TableHead>
                                    <TableHead>Tahun Ajaran</TableHead>
                                    <TableHead>Tipe</TableHead>
                                    <TableHead>Periode</TableHead>
                                    <TableHead>Status</TableHead>
                                    <TableHead className="w-[80px]"></TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                {semesters.length === 0 ? (
                                    <TableRow>
                                        <TableCell colSpan={6} className="h-32 text-center text-muted-foreground">
                                            Belum ada data semester
                                        </TableCell>
                                    </TableRow>
                                ) : (
                                    semesters.map((semester) => (
                                        <TableRow key={semester.id}>
                                            <TableCell className="font-medium">{semester.name}</TableCell>
                                            <TableCell>{semester.academic_year_name}</TableCell>
                                            <TableCell>
                                                <Badge variant="outline">
                                                    {semester.type === 'ODD' ? 'Ganjil' : 'Genap'}
                                                </Badge>
                                            </TableCell>
                                            <TableCell className="text-sm text-muted-foreground">
                                                {new Date(semester.start_date).toLocaleDateString('id-ID')} - {new Date(semester.end_date).toLocaleDateString('id-ID')}
                                            </TableCell>
                                            <TableCell>
                                                <Badge variant={semester.is_active ? 'default' : 'secondary'}>
                                                    {semester.is_active ? 'Aktif' : 'Tidak Aktif'}
                                                </Badge>
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
                                                            <Link href={`/admin/semesters/${semester.id}/edit`}>
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
