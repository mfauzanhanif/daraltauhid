import { Head, Link, usePage } from '@inertiajs/react';
import { Shield, Pencil, Key, Building2 } from 'lucide-react';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/shared/ui/card';
import { Button } from '@/shared/ui/button';
import { Badge } from '@/shared/ui/badge';
import AppLayout from '@/APP/layouts/app-layout';
import type { BreadcrumbItem, SharedData } from '@/types';
import { index as rolesIndex, edit as rolesEdit } from '@/routes/institution/roles';
import { dashboard } from '@/routes/portal';

type Permission = {
    id: number;
    name: string;
    display_name: string;
    group: string;
};

type Institution = {
    id: number;
    code: string;
    name: string;
};

type Role = {
    id: number;
    name: string;
    display_name: string;
    description?: string;
    institution_id?: number;
    institution?: Institution | null;
    permissions: Permission[];
};

type Props = {
    role: Role;
};

export default function RoleShow({ role }: Props) {
    const { currentPortal } = usePage<SharedData>().props;
    const code = currentPortal?.code ?? '';

    const breadcrumbs: BreadcrumbItem[] = [
        { title: 'Admin Yayasan', href: dashboard.url(code) },
        { title: 'Role', href: rolesIndex.url(code) },
        { title: role.display_name, href: '#' },
    ];

    // Group permissions by their group name
    const groupedPermissions = role.permissions.reduce((acc, p) => {
        const group = p.group || 'Umum';
        if (!acc[group]) acc[group] = [];
        acc[group].push(p);
        return acc;
    }, {} as Record<string, Permission[]>);

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={role.display_name} />

            <div className="flex flex-col gap-6 p-6">
                {/* Header */}
                <div className="flex flex-col gap-4 md:flex-row md:items-start md:justify-between">
                    <div className="flex items-start gap-4">
                        <div className="flex size-16 items-center justify-center rounded-xl bg-indigo-100 dark:bg-indigo-900/30">
                            <Shield className="size-8 text-indigo-600" />
                        </div>
                        <div>
                            <h1 className="text-2xl font-bold">{role.display_name}</h1>
                            <p className="text-sm text-muted-foreground font-mono">{role.name}</p>
                            {role.description && (
                                <p className="mt-1 text-muted-foreground">{role.description}</p>
                            )}
                            <div className="mt-2">
                                {role.institution ? (
                                    <Badge variant="outline">
                                        <Building2 className="mr-1 size-3" />
                                        {role.institution.name}
                                    </Badge>
                                ) : (
                                    <Badge className="bg-amber-100 text-amber-800">Global</Badge>
                                )}
                            </div>
                        </div>
                    </div>
                    <Button asChild>
                        <Link href={rolesEdit.url({ institution: code, role: role.id })}>
                            <Pencil className="mr-2 size-4" />
                            Edit Role
                        </Link>
                    </Button>
                </div>

                {/* Permissions */}
                <Card>
                    <CardHeader>
                        <CardTitle className="flex items-center gap-2">
                            <Key className="size-5" />
                            Permissions ({role.permissions.length})
                        </CardTitle>
                        <CardDescription>Hak akses yang diberikan ke role ini</CardDescription>
                    </CardHeader>
                    <CardContent>
                        {Object.keys(groupedPermissions).length === 0 ? (
                            <p className="text-muted-foreground">Belum ada permission</p>
                        ) : (
                            <div className="space-y-6">
                                {Object.entries(groupedPermissions).map(([group, perms]) => (
                                    <div key={group}>
                                        <h4 className="font-medium mb-2 capitalize">{group}</h4>
                                        <div className="flex flex-wrap gap-2">
                                            {perms.map((perm) => (
                                                <Badge key={perm.id} variant="secondary">
                                                    {perm.display_name}
                                                </Badge>
                                            ))}
                                        </div>
                                    </div>
                                ))}
                            </div>
                        )}
                    </CardContent>
                </Card>
            </div>
        </AppLayout>
    );
}
