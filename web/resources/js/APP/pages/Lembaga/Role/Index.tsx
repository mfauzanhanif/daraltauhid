import { Head, Link, usePage } from '@inertiajs/react';
import { Shield, Plus, MoreHorizontal, Pencil, Trash2, Users } from 'lucide-react';
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

type Role = {
    id: number;
    name: string;
    display_name: string;
    description?: string;
    users_count: number;
};

type Props = {
    roles: Role[];
    institutionCode: string;
};

export default function LembagaRoleIndex({ roles = [], institutionCode }: Props) {
    const { currentPortal } = usePage<SharedData>().props;

    const breadcrumbs: BreadcrumbItem[] = [
        { title: currentPortal?.name || 'Lembaga', href: currentPortal?.dashboardUrl || '/' },
        { title: 'Role', href: `/${institutionCode}/roles` },
    ];

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Role Lembaga" />

            <div className="flex flex-col gap-6 p-6">
                {/* Header */}
                <div className="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                    <div className="flex items-center gap-3">
                        <div className="flex size-12 items-center justify-center rounded-xl bg-indigo-100 dark:bg-indigo-900/30">
                            <Shield className="size-6 text-indigo-600" />
                        </div>
                        <div>
                            <h1 className="text-2xl font-bold">Role Lembaga</h1>
                            <p className="text-muted-foreground">Kelola role khusus untuk lembaga ini</p>
                        </div>
                    </div>
                    <Button asChild>
                        <Link href={`/${institutionCode}/roles/create`}>
                            <Plus className="mr-2 size-4" />
                            Tambah Role
                        </Link>
                    </Button>
                </div>

                {/* Table */}
                <Card>
                    <CardHeader>
                        <CardTitle>Daftar Role ({roles.length})</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <Table>
                            <TableHeader>
                                <TableRow>
                                    <TableHead>Role</TableHead>
                                    <TableHead>Deskripsi</TableHead>
                                    <TableHead>Pengguna</TableHead>
                                    <TableHead className="w-[80px]"></TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                {roles.length === 0 ? (
                                    <TableRow>
                                        <TableCell colSpan={4} className="h-32 text-center text-muted-foreground">
                                            Belum ada role khusus lembaga
                                        </TableCell>
                                    </TableRow>
                                ) : (
                                    roles.map((role) => (
                                        <TableRow key={role.id}>
                                            <TableCell>
                                                <div>
                                                    <div className="font-medium">{role.display_name}</div>
                                                    <div className="text-sm text-muted-foreground font-mono">{role.name}</div>
                                                </div>
                                            </TableCell>
                                            <TableCell className="text-muted-foreground">
                                                {role.description || '-'}
                                            </TableCell>
                                            <TableCell>
                                                <Badge variant="secondary">
                                                    <Users className="mr-1 size-3" />
                                                    {role.users_count}
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
                                                            <Link href={`/${institutionCode}/roles/${role.id}/edit`}>
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
