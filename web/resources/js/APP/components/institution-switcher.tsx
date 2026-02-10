import { Link, usePage } from '@inertiajs/react';
import { ChevronsUpDown, Check } from 'lucide-react';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuLabel,
    DropdownMenuTrigger,
} from '@/shared/ui/dropdown-menu';
import { SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/shared/ui/sidebar';
import type { SharedData } from '@/types';

export function InstitutionSwitcher() {
    const { currentPortal, auth } = usePage<SharedData>().props;
    const institutions = auth.available_portals?.institutions ?? [];

    // If there's only one institution or none, show a static display
    if (institutions.length <= 1) {
        return (
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" className="data-[state=open]:bg-sidebar-accent data-[state=open]:text-sidebar-accent-foreground">
                        <div className="flex aspect-square size-8 items-center justify-center rounded-lg bg-sidebar-primary text-sidebar-primary-foreground">
                            <div className="font-bold">{currentPortal?.code?.substring(0, 2) || 'SA'}</div>
                        </div>
                        <div className="grid flex-1 text-left text-sm leading-tight">
                            <span className="truncate font-semibold">{currentPortal?.name || 'Super App'}</span>
                            <span className="truncate text-xs">{currentPortal?.type || 'Platform'}</span>
                        </div>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        );
    }

    return (
        <SidebarMenu>
            <SidebarMenuItem>
                <DropdownMenu>
                    <DropdownMenuTrigger asChild>
                        <SidebarMenuButton
                            size="lg"
                            className="data-[state=open]:bg-sidebar-accent data-[state=open]:text-sidebar-accent-foreground"
                        >
                            <div className="flex aspect-square size-8 items-center justify-center rounded-lg bg-sidebar-primary text-sidebar-primary-foreground">
                                <div className="font-bold">{currentPortal?.code?.substring(0, 2) || 'SA'}</div>
                            </div>
                            <div className="grid flex-1 text-left text-sm leading-tight">
                                <span className="truncate font-semibold">{currentPortal?.name || 'Pilih Lembaga'}</span>
                                <span className="truncate text-xs">{currentPortal?.type || 'Platform'}</span>
                            </div>
                            <ChevronsUpDown className="ml-auto" />
                        </SidebarMenuButton>
                    </DropdownMenuTrigger>
                    <DropdownMenuContent
                        className="w-[--radix-dropdown-menu-trigger-width] min-w-56 rounded-lg"
                        align="start"
                        side="bottom"
                        sideOffset={4}
                    >
                        <DropdownMenuLabel className="text-xs text-muted-foreground">Lembaga</DropdownMenuLabel>
                        {institutions.map((institution) => (
                            <DropdownMenuItem key={institution.id} asChild className="gap-2 p-2">
                                <Link href={institution.url}>
                                    <div className="flex size-6 items-center justify-center rounded-sm border">
                                        <span className="text-xs font-medium">{institution.code.substring(0, 2)}</span>
                                    </div>
                                    {institution.name}
                                    {currentPortal?.code === institution.code && <Check className="ml-auto size-4" />}
                                </Link>
                            </DropdownMenuItem>
                        ))}
                    </DropdownMenuContent>
                </DropdownMenu>
            </SidebarMenuItem>
        </SidebarMenu>
    );
}
