import { Head, Link, usePage } from '@inertiajs/react';
import { DollarSign, Plus, MoreHorizontal, Pencil, Trash2, Check } from 'lucide-react';
import { Card, CardContent, CardHeader, CardTitle } from '@/shared/ui/card';
import { Button } from '@/shared/ui/button';
import { Badge } from '@/shared/ui/badge';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/shared/ui/table';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuTrigger,
} from '@/shared/ui/dropdown-menu';
import AppLayout from '@/APP/layouts/app-layout';
import type { BreadcrumbItem, SharedData } from '@/types';
import { index as fiscalPeriodsIndex, create as fiscalPeriodsCreate, edit as fiscalPeriodsEdit } from '@/routes/fiscal-periods';
import { dashboard } from '@/routes/portal';

type FiscalPeriod = {
    id: number;
    name: string;
    start_date: string;
    end_date: string;
    is_active: boolean;
};

type Props = {
    fiscalPeriods: FiscalPeriod[];
};

export default function FiscalPeriodIndex({ fiscalPeriods = [] }: Props) {
    const { currentPortal } = usePage<SharedData>().props;
    const code = currentPortal?.code ?? '';

    const breadcrumbs: BreadcrumbItem[] = [
        { title: 'Admin Yayasan', href: dashboard.url(code) },
        { title: 'Periode Fiskal', href: fiscalPeriodsIndex.url(code) },
    ];

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Periode Fiskal" />

            <div className="flex flex-col gap-6 p-6">
                {/* Header */}
                <div className="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                    <div className="flex items-center gap-3">
                        <div className="flex size-12 items-center justify-center rounded-xl bg-amber-100 dark:bg-amber-900/30">
                            <DollarSign className="size-6 text-amber-600" />
                        </div>
                        <div>
                            <h1 className="text-2xl font-bold">Periode Fiskal</h1>
                            <p className="text-muted-foreground">Kelola tahun buku keuangan (Januari - Desember)</p>
                        </div>
                    </div>
                    <Button asChild>
                        <Link href={fiscalPeriodsCreate.url(code)}>
                            <Plus className="mr-2 size-4" />
                            Tambah Periode
                        </Link>
                    </Button>
                </div>

                {/* Table */}
                <Card>
                    <CardHeader>
                        <CardTitle>Daftar Periode Fiskal</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <Table>
                            <TableHeader>
                                <TableRow>
                                    <TableHead>Periode</TableHead>
                                    <TableHead>Rentang Waktu</TableHead>
                                    <TableHead>Status</TableHead>
                                    <TableHead className="w-[80px]"></TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                {fiscalPeriods.length === 0 ? (
                                    <TableRow>
                                        <TableCell colSpan={4} className="h-32 text-center text-muted-foreground">
                                            Belum ada data periode fiskal
                                        </TableCell>
                                    </TableRow>
                                ) : (
                                    fiscalPeriods.map((period) => (
                                        <TableRow key={period.id}>
                                            <TableCell className="font-medium">{period.name}</TableCell>
                                            <TableCell className="text-muted-foreground">
                                                {new Date(period.start_date).toLocaleDateString('id-ID')} - {new Date(period.end_date).toLocaleDateString('id-ID')}
                                            </TableCell>
                                            <TableCell>
                                                {period.is_active ? (
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
                                                            <Link href={fiscalPeriodsEdit.url({ institution: code, fiscal_period: period.id })}>
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
