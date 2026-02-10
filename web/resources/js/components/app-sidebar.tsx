import { Link, usePage } from '@inertiajs/react';
import {
    LayoutGrid,
    Building2,
    Calendar,
    BookOpen,
    DollarSign,
    Shield,
    Key,
    Users,
    Settings,
    GraduationCap,
    CreditCard,
} from 'lucide-react';
import { NavFooter } from '@/components/nav-footer';
import { NavMain } from '@/components/nav-main';
import { NavUser } from '@/components/nav-user';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import type { NavItem, SharedData } from '@/types';
import AppLogo from './app-logo';

const footerNavItems: NavItem[] = [];

// Menu untuk Admin Yayasan
// Menu untuk Admin Yayasan (YDTP)
const getYayasanMenuItems = (code: string): NavItem[] => [
    {
        title: 'Dashboard',
        href: `/${code}/dashboard`,
        icon: LayoutGrid,
    },
    {
        title: 'Manajemen Lembaga',
        href: `/${code}/institutions`,
        icon: Building2,
    },
    {
        title: 'Tahun Ajaran',
        href: `/${code}/academic-years`,
        icon: Calendar,
    },
    {
        title: 'Semester',
        href: `/${code}/semesters`,
        icon: BookOpen,
    },
    {
        title: 'Periode Fiskal',
        href: `/${code}/fiscal-periods`,
        icon: DollarSign,
    },
    {
        title: 'Manajemen Role',
        href: `/${code}/roles`,
        icon: Shield,
    },
    {
        title: 'Permissions',
        href: `/${code}/permissions`,
        icon: Key,
    },
    {
        title: 'Manajemen User',
        href: `/${code}/users`,
        icon: Users,
    },
];

// Menu untuk Portal Lembaga
const getLembagaMenuItems = (code: string): NavItem[] => [
    {
        title: 'Dashboard',
        href: `/${code}/dashboard`,
        icon: LayoutGrid,
    },
    {
        title: 'Pengaturan',
        href: `/${code}/settings`,
        icon: Settings,
    },
    {
        title: 'Pengguna',
        href: `/${code}/users`,
        icon: Users,
    },
    {
        title: 'Role',
        href: `/${code}/roles`,
        icon: Shield,
    },
];

// Menu untuk Portal Wali
const getWaliMenuItems = (studentId: string): NavItem[] => [
    {
        title: 'Dashboard',
        href: `/wali/${studentId}/dashboard`,
        icon: LayoutGrid,
    },
    {
        title: 'Data Akademik',
        href: `/wali/${studentId}/academic`,
        icon: GraduationCap,
    },
    {
        title: 'Data Keuangan',
        href: `/wali/${studentId}/finance`,
        icon: CreditCard,
    },
];

export function AppSidebar() {
    const { currentPortal } = usePage<SharedData>().props;

    // Use currentPortal dashboardUrl or fallback to /select-portal
    const dashboardUrl = currentPortal?.dashboardUrl ?? '/select-portal';

    // Determine menu items based on portal type
    const getMenuItems = (): NavItem[] => {
        if (!currentPortal) {
            return [{ title: 'Dashboard', href: '/select-portal', icon: LayoutGrid }];
        }

        switch (currentPortal.type) {
            case 'institution':
                // Check if it is the Yayasan (YDTP)
                if (currentPortal.code === 'YDTP') {
                    return getYayasanMenuItems(currentPortal.code);
                }
                return getLembagaMenuItems(currentPortal.code);
            case 'wali':
                return getWaliMenuItems(currentPortal.code);
            default:
                return [{ title: 'Dashboard', href: dashboardUrl, icon: LayoutGrid }];
        }
    };

    const mainNavItems = getMenuItems();

    return (
        <Sidebar collapsible="icon" variant="inset">
            <SidebarHeader>
                <SidebarMenu>
                    <SidebarMenuItem>
                        <SidebarMenuButton size="lg" asChild>
                            <Link href={dashboardUrl} prefetch>
                                <AppLogo />
                            </Link>
                        </SidebarMenuButton>
                    </SidebarMenuItem>
                </SidebarMenu>
            </SidebarHeader>

            <SidebarContent>
                <NavMain items={mainNavItems} />
            </SidebarContent>

            <SidebarFooter>
                <NavFooter items={footerNavItems} className="mt-auto" />
                <NavUser />
            </SidebarFooter>
        </Sidebar>
    );
}
