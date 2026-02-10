import { Head, Link } from '@inertiajs/react';
import { Building2, Plus, Search, MoreHorizontal, Pencil, Trash2, Eye } from 'lucide-react';
import { Card, CardContent, CardHeader, CardTitle } from '@/shared/ui/card';
import { Button } from '@/shared/ui/button';
import { Input } from '@/shared/ui/input';
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
import type { BreadcrumbItem } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Admin Yayasan', href: '/admin/dashboard' },
    { title: 'Lembaga', href: '/admin/institutions' },
];

type Institution = {
    id: number;
    code: string;
    name: string;
    nickname?: string;
    type: string;
    category: string;
    is_active: boolean;
};

type Props = {
    institutions: Institution[];
};

export default function InstitutionIndex({ institutions = [] }: Props) {
    const getTypeBadge = (type: string) => {
        const colors: Record<string, string> = {
            'PONDOK': 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400',
            'FORMAL': 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400',
            'NON_FORMAL': 'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400',
            'SOSIAL': 'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400',
        };
        return colors[type] || 'bg-gray-100 text-gray-800';
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Manajemen Lembaga" />

            <div className="flex flex-col gap-6 p-6">
                {/* Header */}
                <div className="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                    <div className="flex items-center gap-3">
                        <div className="flex size-12 items-center justify-center rounded-xl bg-emerald-100 dark:bg-emerald-900/30">
                            <Building2 className="size-6 text-emerald-600" />
                        </div>
                        <div>
                            <h1 className="text-2xl font-bold">Manajemen Lembaga</h1>
                            <p className="text-muted-foreground">Kelola data lembaga di bawah naungan yayasan</p>
                        </div>
                    </div>
                    <Button asChild>
                        <Link href="/admin/institutions/create">
                            <Plus className="mr-2 size-4" />
                            Tambah Lembaga
                        </Link>
                    </Button>
                </div>

                {/* Search & Filter */}
                <Card>
                    <CardContent className="pt-6">
                        <div className="flex gap-4">
                            <div className="relative flex-1">
                                <Search className="absolute left-3 top-1/2 size-4 -translate-y-1/2 text-muted-foreground" />
                                <Input placeholder="Cari lembaga..." className="pl-9" />
                            </div>
                        </div>
                    </CardContent>
                </Card>

                {/* Table */}
                <Card>
                    <CardHeader>
                        <CardTitle>Daftar Lembaga ({institutions.length})</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <Table>
                            <TableHeader>
                                <TableRow>
                                    <TableHead>Kode</TableHead>
                                    <TableHead>Nama Lembaga</TableHead>
                                    <TableHead>Tipe</TableHead>
                                    <TableHead>Kategori</TableHead>
                                    <TableHead>Status</TableHead>
                                    <TableHead className="w-[80px]"></TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                {institutions.length === 0 ? (
                                    <TableRow>
                                        <TableCell colSpan={6} className="h-32 text-center text-muted-foreground">
                                            Belum ada data lembaga
                                        </TableCell>
                                    </TableRow>
                                ) : (
                                    institutions.map((institution) => (
                                        <TableRow key={institution.id}>
                                            <TableCell className="font-mono font-medium">
                                                {institution.code}
                                            </TableCell>
                                            <TableCell>
                                                <div>
                                                    <div className="font-medium">{institution.name}</div>
                                                    {institution.nickname && (
                                                        <div className="text-sm text-muted-foreground">
                                                            {institution.nickname}
                                                        </div>
                                                    )}
                                                </div>
                                            </TableCell>
                                            <TableCell>
                                                <Badge variant="outline" className={getTypeBadge(institution.type)}>
                                                    {institution.type}
                                                </Badge>
                                            </TableCell>
                                            <TableCell>{institution.category}</TableCell>
                                            <TableCell>
                                                <Badge variant={institution.is_active ? 'default' : 'secondary'}>
                                                    {institution.is_active ? 'Aktif' : 'Nonaktif'}
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
                                                            <Link href={`/admin/institutions/${institution.id}`}>
                                                                <Eye className="mr-2 size-4" />
                                                                Lihat Detail
                                                            </Link>
                                                        </DropdownMenuItem>
                                                        <DropdownMenuItem asChild>
                                                            <Link href={`/admin/institutions/${institution.id}/edit`}>
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
