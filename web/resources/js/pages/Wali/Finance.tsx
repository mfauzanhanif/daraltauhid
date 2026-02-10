import { Head, usePage } from '@inertiajs/react';
import { DollarSign, CreditCard, CheckCircle, Clock, AlertCircle, Download } from 'lucide-react';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
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

type Invoice = {
    id: number;
    invoice_no: string;
    description: string;
    amount: number;
    due_date: string;
    status: 'PAID' | 'UNPAID' | 'OVERDUE';
    paid_at?: string;
};

type Props = {
    student: Student;
    invoices: Invoice[];
    summary: {
        total_paid: number;
        total_unpaid: number;
        total_overdue: number;
    };
};

export default function WaliFinance({ student, invoices = [], summary }: Props) {
    const { currentPortal } = usePage<SharedData>().props;

    const breadcrumbs: BreadcrumbItem[] = [
        { title: student.name, href: `/wali/${student.public_id}/dashboard` },
        { title: 'Keuangan', href: `/wali/${student.public_id}/finance` },
    ];

    const formatCurrency = (amount: number) => {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0,
        }).format(amount);
    };

    const getStatusBadge = (status: Invoice['status']) => {
        switch (status) {
            case 'PAID':
                return (
                    <Badge className="bg-emerald-100 text-emerald-800">
                        <CheckCircle className="mr-1 size-3" />
                        Lunas
                    </Badge>
                );
            case 'UNPAID':
                return (
                    <Badge className="bg-amber-100 text-amber-800">
                        <Clock className="mr-1 size-3" />
                        Belum Bayar
                    </Badge>
                );
            case 'OVERDUE':
                return (
                    <Badge className="bg-red-100 text-red-800">
                        <AlertCircle className="mr-1 size-3" />
                        Jatuh Tempo
                    </Badge>
                );
        }
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={`Keuangan - ${student.name}`} />

            <div className="flex flex-col gap-6 p-6">
                {/* Header */}
                <div className="flex items-center gap-3">
                    <div className="flex size-12 items-center justify-center rounded-xl bg-amber-100 dark:bg-amber-900/30">
                        <DollarSign className="size-6 text-amber-600" />
                    </div>
                    <div>
                        <h1 className="text-2xl font-bold">Data Keuangan</h1>
                        <p className="text-muted-foreground">{student.name}</p>
                    </div>
                </div>

                {/* Summary Cards */}
                <div className="grid gap-4 md:grid-cols-3">
                    <Card className="border-emerald-200 bg-emerald-50/50 dark:bg-emerald-900/10">
                        <CardHeader className="pb-2">
                            <CardTitle className="flex items-center gap-2 text-sm text-emerald-700">
                                <CheckCircle className="size-4" />
                                Total Terbayar
                            </CardTitle>
                        </CardHeader>
                        <CardContent>
                            <span className="text-2xl font-bold text-emerald-700">
                                {summary ? formatCurrency(summary.total_paid) : '—'}
                            </span>
                        </CardContent>
                    </Card>
                    <Card className="border-amber-200 bg-amber-50/50 dark:bg-amber-900/10">
                        <CardHeader className="pb-2">
                            <CardTitle className="flex items-center gap-2 text-sm text-amber-700">
                                <Clock className="size-4" />
                                Belum Bayar
                            </CardTitle>
                        </CardHeader>
                        <CardContent>
                            <span className="text-2xl font-bold text-amber-700">
                                {summary ? formatCurrency(summary.total_unpaid) : '—'}
                            </span>
                        </CardContent>
                    </Card>
                    <Card className="border-red-200 bg-red-50/50 dark:bg-red-900/10">
                        <CardHeader className="pb-2">
                            <CardTitle className="flex items-center gap-2 text-sm text-red-700">
                                <AlertCircle className="size-4" />
                                Jatuh Tempo
                            </CardTitle>
                        </CardHeader>
                        <CardContent>
                            <span className="text-2xl font-bold text-red-700">
                                {summary ? formatCurrency(summary.total_overdue) : '—'}
                            </span>
                        </CardContent>
                    </Card>
                </div>

                {/* Invoices Table */}
                <Card>
                    <CardHeader>
                        <CardTitle>Riwayat Tagihan</CardTitle>
                        <CardDescription>Daftar tagihan dan status pembayaran</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <Table>
                            <TableHeader>
                                <TableRow>
                                    <TableHead>No. Invoice</TableHead>
                                    <TableHead>Deskripsi</TableHead>
                                    <TableHead>Jatuh Tempo</TableHead>
                                    <TableHead className="text-right">Jumlah</TableHead>
                                    <TableHead>Status</TableHead>
                                    <TableHead className="w-[80px]"></TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                {invoices.length === 0 ? (
                                    <TableRow>
                                        <TableCell colSpan={6} className="h-32 text-center text-muted-foreground">
                                            Belum ada data tagihan
                                        </TableCell>
                                    </TableRow>
                                ) : (
                                    invoices.map((invoice) => (
                                        <TableRow key={invoice.id}>
                                            <TableCell className="font-mono text-sm">
                                                {invoice.invoice_no}
                                            </TableCell>
                                            <TableCell>{invoice.description}</TableCell>
                                            <TableCell className="text-muted-foreground">
                                                {new Date(invoice.due_date).toLocaleDateString('id-ID')}
                                            </TableCell>
                                            <TableCell className="text-right font-semibold">
                                                {formatCurrency(invoice.amount)}
                                            </TableCell>
                                            <TableCell>{getStatusBadge(invoice.status)}</TableCell>
                                            <TableCell>
                                                {invoice.status === 'PAID' && (
                                                    <Button variant="ghost" size="icon">
                                                        <Download className="size-4" />
                                                    </Button>
                                                )}
                                                {invoice.status !== 'PAID' && (
                                                    <Button size="sm">Bayar</Button>
                                                )}
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
