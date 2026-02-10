import { Head, Link } from '@inertiajs/react';
import { Users, Plus, MoreHorizontal, Pencil, Trash2, Search, Mail, Shield } from 'lucide-react';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Badge } from '@/components/ui/badge';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
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
    { title: 'Users', href: '/admin/users' },
];

type User = {
    id: number;
    name: string;
    email: string;
    avatar_url?: string;
    roles: { name: string; display_name: string }[];
    created_at: string;
};

type Props = {
    users: User[];
};

export default function UserIndex({ users = [] }: Props) {
    const getInitials = (name: string) => {
        return name.split(' ').map(n => n[0]).join('').substring(0, 2).toUpperCase();
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Manajemen User" />

            <div className="flex flex-col gap-6 p-6">
                {/* Header */}
                <div className="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                    <div className="flex items-center gap-3">
                        <div className="flex size-12 items-center justify-center rounded-xl bg-cyan-100 dark:bg-cyan-900/30">
                            <Users className="size-6 text-cyan-600" />
                        </div>
                        <div>
                            <h1 className="text-2xl font-bold">Manajemen User</h1>
                            <p className="text-muted-foreground">Kelola pengguna dan assign role</p>
                        </div>
                    </div>
                    <Button asChild>
                        <Link href="/admin/users/create">
                            <Plus className="mr-2 size-4" />
                            Tambah User
                        </Link>
                    </Button>
                </div>

                {/* Search */}
                <Card>
                    <CardContent className="pt-6">
                        <div className="flex gap-4">
                            <div className="relative flex-1">
                                <Search className="absolute left-3 top-1/2 size-4 -translate-y-1/2 text-muted-foreground" />
                                <Input placeholder="Cari user..." className="pl-9" />
                            </div>
                        </div>
                    </CardContent>
                </Card>

                {/* Table */}
                <Card>
                    <CardHeader>
                        <CardTitle>Daftar User ({users.length})</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <Table>
                            <TableHeader>
                                <TableRow>
                                    <TableHead>User</TableHead>
                                    <TableHead>Email</TableHead>
                                    <TableHead>Roles</TableHead>
                                    <TableHead>Terdaftar</TableHead>
                                    <TableHead className="w-[80px]"></TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                {users.length === 0 ? (
                                    <TableRow>
                                        <TableCell colSpan={5} className="h-32 text-center text-muted-foreground">
                                            Belum ada data user
                                        </TableCell>
                                    </TableRow>
                                ) : (
                                    users.map((user) => (
                                        <TableRow key={user.id}>
                                            <TableCell>
                                                <div className="flex items-center gap-3">
                                                    <Avatar>
                                                        <AvatarImage src={user.avatar_url} />
                                                        <AvatarFallback>{getInitials(user.name)}</AvatarFallback>
                                                    </Avatar>
                                                    <span className="font-medium">{user.name}</span>
                                                </div>
                                            </TableCell>
                                            <TableCell>
                                                <div className="flex items-center gap-2 text-muted-foreground">
                                                    <Mail className="size-4" />
                                                    {user.email}
                                                </div>
                                            </TableCell>
                                            <TableCell>
                                                <div className="flex flex-wrap gap-1">
                                                    {user.roles.length === 0 ? (
                                                        <span className="text-muted-foreground">-</span>
                                                    ) : (
                                                        user.roles.map((role) => (
                                                            <Badge key={role.name} variant="outline" className="text-xs">
                                                                <Shield className="mr-1 size-3" />
                                                                {role.display_name}
                                                            </Badge>
                                                        ))
                                                    )}
                                                </div>
                                            </TableCell>
                                            <TableCell className="text-muted-foreground">
                                                {new Date(user.created_at).toLocaleDateString('id-ID')}
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
                                                            <Link href={`/admin/users/${user.id}/edit`}>
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
