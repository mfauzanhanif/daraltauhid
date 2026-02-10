import { Head, usePage } from '@inertiajs/react';
import { BookOpen, Award, FileText, TrendingUp, Calendar } from 'lucide-react';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import AppLayout from '@/layouts/app-layout';
import type { BreadcrumbItem, SharedData } from '@/types';

type Student = {
    public_id: string;
    name: string;
};

type Grade = {
    subject: string;
    midterm: number | null;
    final: number | null;
    average: number | null;
};

type Props = {
    student: Student;
    grades: Grade[];
    semester: string;
};

export default function WaliAcademic({ student, grades = [], semester = '—' }: Props) {
    const { currentPortal } = usePage<SharedData>().props;

    const breadcrumbs: BreadcrumbItem[] = [
        { title: student.name, href: `/wali/${student.public_id}/dashboard` },
        { title: 'Akademik', href: `/wali/${student.public_id}/academic` },
    ];

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={`Akademik - ${student.name}`} />

            <div className="flex flex-col gap-6 p-6">
                {/* Header */}
                <div className="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                    <div className="flex items-center gap-3">
                        <div className="flex size-12 items-center justify-center rounded-xl bg-blue-100 dark:bg-blue-900/30">
                            <BookOpen className="size-6 text-blue-600" />
                        </div>
                        <div>
                            <h1 className="text-2xl font-bold">Data Akademik</h1>
                            <p className="text-muted-foreground">{student.name}</p>
                        </div>
                    </div>
                    <Badge variant="outline" className="w-fit">
                        <Calendar className="mr-1 size-3" />
                        {semester}
                    </Badge>
                </div>

                {/* Summary Cards */}
                <div className="grid gap-4 md:grid-cols-3">
                    <Card>
                        <CardHeader className="pb-2">
                            <CardTitle className="flex items-center gap-2 text-sm text-muted-foreground">
                                <TrendingUp className="size-4" />
                                Rata-rata Nilai
                            </CardTitle>
                        </CardHeader>
                        <CardContent>
                            <span className="text-3xl font-bold">—</span>
                        </CardContent>
                    </Card>
                    <Card>
                        <CardHeader className="pb-2">
                            <CardTitle className="flex items-center gap-2 text-sm text-muted-foreground">
                                <Award className="size-4" />
                                Ranking
                            </CardTitle>
                        </CardHeader>
                        <CardContent>
                            <span className="text-3xl font-bold">—</span>
                        </CardContent>
                    </Card>
                    <Card>
                        <CardHeader className="pb-2">
                            <CardTitle className="flex items-center gap-2 text-sm text-muted-foreground">
                                <FileText className="size-4" />
                                Kehadiran
                            </CardTitle>
                        </CardHeader>
                        <CardContent>
                            <span className="text-3xl font-bold">—%</span>
                        </CardContent>
                    </Card>
                </div>

                {/* Grades Table */}
                <Card>
                    <CardHeader>
                        <CardTitle>Nilai Per Mata Pelajaran</CardTitle>
                        <CardDescription>Nilai UTS dan UAS semester berjalan</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <Table>
                            <TableHeader>
                                <TableRow>
                                    <TableHead>Mata Pelajaran</TableHead>
                                    <TableHead className="text-center">UTS</TableHead>
                                    <TableHead className="text-center">UAS</TableHead>
                                    <TableHead className="text-center">Rata-rata</TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                {grades.length === 0 ? (
                                    <TableRow>
                                        <TableCell colSpan={4} className="h-32 text-center text-muted-foreground">
                                            Belum ada data nilai
                                        </TableCell>
                                    </TableRow>
                                ) : (
                                    grades.map((grade, index) => (
                                        <TableRow key={index}>
                                            <TableCell className="font-medium">{grade.subject}</TableCell>
                                            <TableCell className="text-center">{grade.midterm ?? '—'}</TableCell>
                                            <TableCell className="text-center">{grade.final ?? '—'}</TableCell>
                                            <TableCell className="text-center font-semibold">
                                                {grade.average ?? '—'}
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
