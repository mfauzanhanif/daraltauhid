import { Head, usePage } from '@inertiajs/react';
import { Home, User, BookOpen, DollarSign, Calendar, GraduationCap, FileText, Phone } from 'lucide-react';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { Badge } from '@/components/ui/badge';
import AppLayout from '@/layouts/app-layout';
import type { BreadcrumbItem, SharedData } from '@/types';

type Student = {
    id: number;
    public_id: string;
    name: string;
    nis?: string;
    nisn?: string;
    class_name?: string;
    institution_name?: string;
    photo_url?: string;
    gender?: string;
    birth_date?: string;
};

type Props = {
    student: Student;
};

export default function WaliDashboard({ student }: Props) {
    const { currentPortal } = usePage<SharedData>().props;

    const breadcrumbs: BreadcrumbItem[] = [
        { title: student.name, href: `/wali/${student.public_id}/dashboard` },
    ];

    const getInitials = (name: string) => {
        return name.split(' ').map(n => n[0]).join('').substring(0, 2).toUpperCase();
    };

    const menuItems = [
        { title: 'Data Akademik', href: `/wali/${student.public_id}/academic`, icon: BookOpen, desc: 'Nilai, rapor, dan prestasi' },
        { title: 'Data Keuangan', href: `/wali/${student.public_id}/finance`, icon: DollarSign, desc: 'Tagihan dan pembayaran' },
    ];

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={`Dashboard - ${student.name}`} />

            <div className="flex flex-col gap-6 p-6">
                {/* Student Profile Card */}
                <Card className="bg-gradient-to-r from-emerald-500/10 via-teal-500/5 to-transparent border-emerald-500/20">
                    <CardContent className="pt-6">
                        <div className="flex flex-col md:flex-row items-center md:items-start gap-6">
                            <Avatar className="size-24 border-4 border-white shadow-lg">
                                <AvatarImage src={student.photo_url} />
                                <AvatarFallback className="text-2xl bg-emerald-100 text-emerald-700">
                                    {getInitials(student.name)}
                                </AvatarFallback>
                            </Avatar>
                            <div className="flex-1 text-center md:text-left">
                                <h1 className="text-2xl font-bold">{student.name}</h1>
                                <div className="mt-2 flex flex-wrap justify-center md:justify-start gap-2">
                                    {student.class_name && (
                                        <Badge variant="outline" className="bg-white">
                                            <GraduationCap className="mr-1 size-3" />
                                            {student.class_name}
                                        </Badge>
                                    )}
                                    {student.institution_name && (
                                        <Badge variant="outline" className="bg-white">
                                            {student.institution_name}
                                        </Badge>
                                    )}
                                </div>
                                <div className="mt-4 grid gap-2 text-sm text-muted-foreground md:grid-cols-2">
                                    {student.nis && (
                                        <div className="flex items-center gap-2">
                                            <FileText className="size-4" />
                                            <span>NIS: {student.nis}</span>
                                        </div>
                                    )}
                                    {student.nisn && (
                                        <div className="flex items-center gap-2">
                                            <FileText className="size-4" />
                                            <span>NISN: {student.nisn}</span>
                                        </div>
                                    )}
                                </div>
                            </div>
                        </div>
                    </CardContent>
                </Card>

                {/* Quick Menu */}
                <div className="grid gap-4 md:grid-cols-2">
                    {menuItems.map((item) => (
                        <a
                            key={item.title}
                            href={item.href}
                            className="block"
                        >
                            <Card className="h-full transition-all hover:shadow-md hover:border-emerald-300">
                                <CardHeader>
                                    <div className="flex items-start gap-4">
                                        <div className="rounded-lg bg-emerald-100 p-3 dark:bg-emerald-900/30">
                                            <item.icon className="size-6 text-emerald-600" />
                                        </div>
                                        <div>
                                            <CardTitle>{item.title}</CardTitle>
                                            <CardDescription>{item.desc}</CardDescription>
                                        </div>
                                    </div>
                                </CardHeader>
                            </Card>
                        </a>
                    ))}
                </div>

                {/* Info Cards */}
                <div className="grid gap-4 md:grid-cols-3">
                    <Card>
                        <CardHeader className="pb-2">
                            <CardTitle className="text-sm text-muted-foreground">Semester Aktif</CardTitle>
                        </CardHeader>
                        <CardContent>
                            <div className="flex items-center gap-2">
                                <Calendar className="size-5 text-blue-600" />
                                <span className="text-lg font-semibold">—</span>
                            </div>
                        </CardContent>
                    </Card>
                    <Card>
                        <CardHeader className="pb-2">
                            <CardTitle className="text-sm text-muted-foreground">Total Tagihan</CardTitle>
                        </CardHeader>
                        <CardContent>
                            <div className="flex items-center gap-2">
                                <DollarSign className="size-5 text-amber-600" />
                                <span className="text-lg font-semibold">—</span>
                            </div>
                        </CardContent>
                    </Card>
                    <Card>
                        <CardHeader className="pb-2">
                            <CardTitle className="text-sm text-muted-foreground">Status Pembayaran</CardTitle>
                        </CardHeader>
                        <CardContent>
                            <Badge className="bg-emerald-100 text-emerald-800">
                                Lunas
                            </Badge>
                        </CardContent>
                    </Card>
                </div>
            </div>
        </AppLayout>
    );
}
