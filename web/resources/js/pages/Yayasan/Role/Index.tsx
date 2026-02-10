import { Head, Link } from '@inertiajs/react';
import { Shield, Plus, MoreHorizontal, Pencil, Trash2, Users } from 'lucide-react';
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
    { title: 'Role', href: '/admin/roles' },
];

type Role = {
    id: number;
    name: string;
    display_name: string;
    description?: string;
    institution_id?: number;
    institution_name?: string;
    users_count: number;
    permissions_count: number;
};

type Props = {
    roles: Role[];
};

export default function RoleIndex({ roles = [] }: Props) {
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Manajemen Role" />

            <div className="flex flex-col gap-6 p-6">
                {/* Header */}
                <div className="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                    <div className="flex items-center gap-3">
                        <div className="flex size-12 items-center justify-center rounded-xl bg-indigo-100 dark:bg-indigo-900/30">
                            <Shield className="size-6 text-indigo-600" />
                        </div>
                        <div>
                            <h1 className="text-2xl font-bold">Manajemen Role</h1>
                            <p className="text-muted-foreground">Kelola role global dan per-lembaga</p>
                        </div>
                    </div>
                    <Button asChild>
                        <Link href="/admin/roles/create">
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
                                    <TableHead>Scope</TableHead>
                                    <TableHead>Users</TableHead>
                                    <TableHead>Permissions</TableHead>
                                    <TableHead className="w-[80px]"></TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                {roles.length === 0 ? (
                                    <TableRow>
                                        <TableCell colSpan={5} className="h-32 text-center text-muted-foreground">
                                            Belum ada data role
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
                                            <TableCell>
                                                {role.institution_id ? (
                                                    <Badge variant="outline">{role.institution_name}</Badge>
                                                ) : (
                                                    <Badge className="bg-amber-100 text-amber-800">Global</Badge>
                                                )}
                                            </TableCell>
                                            <TableCell>
                                                <div className="flex items-center gap-1">
                                                    <Users className="size-4 text-muted-foreground" />
                                                    {role.users_count}
                                                </div>
                                            </TableCell>
                                            <TableCell>{role.permissions_count}</TableCell>
                                            <TableCell>
                                                <DropdownMenu>
                                                    <DropdownMenuTrigger asChild>
                                                        <Button variant="ghost" size="icon">
                                                            <MoreHorizontal className="size-4" />
                                                        </Button>
                                                    </DropdownMenuTrigger>
                                                    <DropdownMenuContent align="end">
                                                        <DropdownMenuItem asChild>
                                                            <Link href={`/admin/roles/${role.id}/edit`}>
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
