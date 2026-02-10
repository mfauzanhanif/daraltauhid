import { Head, Link, useForm, usePage } from '@inertiajs/react';
import { Shield } from 'lucide-react';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/shared/ui/card';
import { Button } from '@/shared/ui/button';
import { Input } from '@/shared/ui/input';
import { Label } from '@/shared/ui/label';
import { Textarea } from '@/shared/ui/textarea';
import { Checkbox } from '@/shared/ui/checkbox';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/shared/ui/select';
import AppLayout from '@/APP/layouts/app-layout';
import type { BreadcrumbItem, SharedData } from '@/types';
import { index as rolesIndex, edit as rolesEdit, update as rolesUpdate } from '@/routes/institution/roles';
import { dashboard } from '@/routes/portal';

type Institution = {
    id: number;
    name: string;
};

type Permission = {
    id: number;
    name: string;
    display_name: string;
    group: string;
};

type Role = {
    id: number;
    name: string;
    display_name: string;
    description?: string;
    institution_id?: number;
    permissions: Permission[];
};

type Props = {
    role: Role;
    institutions: Institution[];
    permissions: Permission[];
};

export default function RoleEdit({ role, institutions = [], permissions = [] }: Props) {
    const { currentPortal } = usePage<SharedData>().props;
    const code = currentPortal?.code ?? '';

    const breadcrumbs: BreadcrumbItem[] = [
        { title: 'Admin Yayasan', href: dashboard.url(code) },
        { title: 'Role', href: rolesIndex.url(code) },
        { title: 'Edit', href: rolesEdit.url({ institution: code, role: role.id }) },
    ];

    const { data, setData, put, processing, errors } = useForm({
        name: role.name,
        display_name: role.display_name,
        description: role.description ?? '',
        institution_id: role.institution_id ? String(role.institution_id) : '',
        permissions: role.permissions.map((p) => p.id),
    });

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        put(rolesUpdate.url({ institution: code, role: role.id }));
    };

    const togglePermission = (permissionId: number) => {
        if (data.permissions.includes(permissionId)) {
            setData('permissions', data.permissions.filter((id) => id !== permissionId));
        } else {
            setData('permissions', [...data.permissions, permissionId]);
        }
    };

    // Group permissions by their group name
    const groupedPermissions = permissions.reduce((acc, p) => {
        if (!acc[p.group]) acc[p.group] = [];
        acc[p.group].push(p);
        return acc;
    }, {} as Record<string, Permission[]>);

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={`Edit Role - ${role.display_name}`} />

            <div className="flex flex-col gap-6 p-6">
                {/* Header */}
                <div className="flex items-center gap-3">
                    <div className="flex size-12 items-center justify-center rounded-xl bg-indigo-100 dark:bg-indigo-900/30">
                        <Shield className="size-6 text-indigo-600" />
                    </div>
                    <div>
                        <h1 className="text-2xl font-bold">Edit Role</h1>
                        <p className="text-muted-foreground">{role.display_name}</p>
                    </div>
                </div>

                <form onSubmit={handleSubmit}>
                    <div className="grid gap-6 lg:grid-cols-3">
                        <div className="lg:col-span-2 space-y-6">
                            <Card>
                                <CardHeader>
                                    <CardTitle>Informasi Role</CardTitle>
                                    <CardDescription>Detail dasar role</CardDescription>
                                </CardHeader>
                                <CardContent className="space-y-4">
                                    <div className="grid gap-4 md:grid-cols-2">
                                        <div className="space-y-2">
                                            <Label htmlFor="name">Nama Role (Slug) *</Label>
                                            <Input
                                                id="name"
                                                placeholder="admin-yayasan"
                                                value={data.name}
                                                onChange={(e) => setData('name', e.target.value.toLowerCase().replace(/\s+/g, '-'))}
                                                className="font-mono"
                                            />
                                            {errors.name && <p className="text-sm text-destructive">{errors.name}</p>}
                                        </div>
                                        <div className="space-y-2">
                                            <Label htmlFor="display_name">Nama Tampilan *</Label>
                                            <Input
                                                id="display_name"
                                                placeholder="Admin Yayasan"
                                                value={data.display_name}
                                                onChange={(e) => setData('display_name', e.target.value)}
                                            />
                                            {errors.display_name && <p className="text-sm text-destructive">{errors.display_name}</p>}
                                        </div>
                                    </div>
                                    <div className="space-y-2">
                                        <Label htmlFor="description">Deskripsi</Label>
                                        <Textarea
                                            id="description"
                                            placeholder="Deskripsi singkat tentang role ini"
                                            value={data.description}
                                            onChange={(e) => setData('description', e.target.value)}
                                        />
                                    </div>
                                    <div className="space-y-2">
                                        <Label htmlFor="institution_id">Scope Lembaga</Label>
                                        <Select value={data.institution_id} onValueChange={(v) => setData('institution_id', v)}>
                                            <SelectTrigger>
                                                <SelectValue placeholder="Global (Semua Lembaga)" />
                                            </SelectTrigger>
                                            <SelectContent>
                                                <SelectItem value="">Global (Semua Lembaga)</SelectItem>
                                                {institutions.map((inst) => (
                                                    <SelectItem key={inst.id} value={String(inst.id)}>
                                                        {inst.name}
                                                    </SelectItem>
                                                ))}
                                            </SelectContent>
                                        </Select>
                                    </div>
                                </CardContent>
                            </Card>

                            <Card>
                                <CardHeader>
                                    <CardTitle>Permissions</CardTitle>
                                    <CardDescription>Pilih hak akses untuk role ini</CardDescription>
                                </CardHeader>
                                <CardContent>
                                    {Object.keys(groupedPermissions).length === 0 ? (
                                        <p className="text-muted-foreground">Belum ada permission tersedia</p>
                                    ) : (
                                        <div className="space-y-6">
                                            {Object.entries(groupedPermissions).map(([group, perms]) => (
                                                <div key={group}>
                                                    <h4 className="font-medium mb-2 capitalize">{group}</h4>
                                                    <div className="grid gap-2 md:grid-cols-2">
                                                        {perms.map((perm) => (
                                                            <label
                                                                key={perm.id}
                                                                className="flex items-center gap-2 rounded-lg border p-3 cursor-pointer hover:bg-muted/50"
                                                            >
                                                                <Checkbox
                                                                    checked={data.permissions.includes(perm.id)}
                                                                    onCheckedChange={() => togglePermission(perm.id)}
                                                                />
                                                                <div>
                                                                    <p className="text-sm font-medium">{perm.display_name}</p>
                                                                    <p className="text-xs text-muted-foreground font-mono">{perm.name}</p>
                                                                </div>
                                                            </label>
                                                        ))}
                                                    </div>
                                                </div>
                                            ))}
                                        </div>
                                    )}
                                </CardContent>
                            </Card>
                        </div>

                        <div>
                            <Card>
                                <CardContent className="pt-6">
                                    <div className="flex flex-col gap-2">
                                        <Button type="submit" disabled={processing}>
                                            {processing ? 'Menyimpan...' : 'Simpan Perubahan'}
                                        </Button>
                                        <Button variant="outline" asChild>
                                            <Link href={rolesIndex.url(code)}>Batal</Link>
                                        </Button>
                                    </div>
                                </CardContent>
                            </Card>
                        </div>
                    </div>
                </form>
            </div>
        </AppLayout>
    );
}
