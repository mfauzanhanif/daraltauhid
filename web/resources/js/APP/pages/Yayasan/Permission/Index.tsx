import { Head } from '@inertiajs/react';
import { Key } from 'lucide-react';
import { Card, CardContent, CardHeader, CardTitle } from '@/shared/ui/card';
import { Badge } from '@/shared/ui/badge';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/shared/ui/table';
import AppLayout from '@/APP/layouts/app-layout';
import type { BreadcrumbItem } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Admin Yayasan', href: '/admin/dashboard' },
    { title: 'Permissions', href: '/admin/permissions' },
];

type Permission = {
    id: number;
    name: string;
    display_name: string;
    description?: string;
    group: string;
};

type Props = {
    permissions: Permission[];
};

export default function PermissionIndex({ permissions = [] }: Props) {
    // Group permissions by their group name
    const groupedPermissions = permissions.reduce((acc, p) => {
        if (!acc[p.group]) acc[p.group] = [];
        acc[p.group].push(p);
        return acc;
    }, {} as Record<string, Permission[]>);

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Daftar Permissions" />

            <div className="flex flex-col gap-6 p-6">
                {/* Header */}
                <div className="flex items-center gap-3">
                    <div className="flex size-12 items-center justify-center rounded-xl bg-rose-100 dark:bg-rose-900/30">
                        <Key className="size-6 text-rose-600" />
                    </div>
                    <div>
                        <h1 className="text-2xl font-bold">Permissions</h1>
                        <p className="text-muted-foreground">Daftar semua permission yang tersedia dalam sistem</p>
                    </div>
                </div>

                {/* Grouped Tables */}
                {Object.keys(groupedPermissions).length === 0 ? (
                    <Card>
                        <CardContent className="flex h-32 items-center justify-center text-muted-foreground">
                            Belum ada permission terdaftar
                        </CardContent>
                    </Card>
                ) : (
                    Object.entries(groupedPermissions).map(([group, perms]) => (
                        <Card key={group}>
                            <CardHeader>
                                <CardTitle className="flex items-center gap-2 capitalize">
                                    {group}
                                    <Badge variant="secondary">{perms.length}</Badge>
                                </CardTitle>
                            </CardHeader>
                            <CardContent>
                                <Table>
                                    <TableHeader>
                                        <TableRow>
                                            <TableHead className="w-[300px]">Permission</TableHead>
                                            <TableHead>Name (Slug)</TableHead>
                                            <TableHead>Deskripsi</TableHead>
                                        </TableRow>
                                    </TableHeader>
                                    <TableBody>
                                        {perms.map((perm) => (
                                            <TableRow key={perm.id}>
                                                <TableCell className="font-medium">{perm.display_name}</TableCell>
                                                <TableCell className="font-mono text-sm text-muted-foreground">{perm.name}</TableCell>
                                                <TableCell className="text-muted-foreground">{perm.description || '-'}</TableCell>
                                            </TableRow>
                                        ))}
                                    </TableBody>
                                </Table>
                            </CardContent>
                        </Card>
                    ))
                )}
            </div>
        </AppLayout>
    );
}
