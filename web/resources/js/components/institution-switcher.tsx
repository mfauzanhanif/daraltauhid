import { router, usePage } from '@inertiajs/react';
import { ChevronsUpDown, Check } from 'lucide-react';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuLabel,
    DropdownMenuSeparator,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { useMemo } from 'react';

declare function route(name: string, params?: any): string;

export function InstitutionSwitcher() {
    const { auth } = usePage<any>().props;
    const currentInstitution = auth.institution;
    const institutions = auth.institutions || [];

    const handleSwitch = (institutionId: number) => {
        router.post(route('institution.switch'), {
            institution_id: institutionId,
        });
    };

    if (institutions.length <= 1) {
        return (
             <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" className="data-[state=open]:bg-sidebar-accent data-[state=open]:text-sidebar-accent-foreground">
                        <div className="flex aspect-square size-8 items-center justify-center rounded-lg bg-sidebar-primary text-sidebar-primary-foreground">
                            {currentInstitution?.logo_path ? (
                                <img src={currentInstitution.logo_path} alt="Logo" className="size-8" />
                            ) : (
                                <div className="font-bold">{currentInstitution?.code?.substring(0, 2) || 'SA'}</div>
                            )}
                        </div>
                        <div className="grid flex-1 text-left text-sm leading-tight">
                            <span className="truncate font-semibold">{currentInstitution?.name || 'Super App'}</span>
                            <span className="truncate text-xs">{currentInstitution?.category || 'Platform'}</span>
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
                                 {currentInstitution?.logo_path ? (
                                    <img src={currentInstitution.logo_path} alt="Logo" className="size-8" />
                                ) : (
                                     <div className="font-bold">{currentInstitution?.code?.substring(0, 2) || 'SA'}</div>
                                )}
                            </div>
                            <div className="grid flex-1 text-left text-sm leading-tight">
                                <span className="truncate font-semibold">{currentInstitution?.name || 'Select Institution'}</span>
                                <span className="truncate text-xs">{currentInstitution?.category || 'Platform'}</span>
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
                        <DropdownMenuLabel className="text-xs text-muted-foreground">Institutions</DropdownMenuLabel>
                        {institutions.map((institution: any) => (
                            <DropdownMenuItem
                                key={institution.id}
                                onClick={() => handleSwitch(institution.id)}
                                className="gap-2 p-2"
                            >
                                <div className="flex size-6 items-center justify-center rounded-sm border">
                                    {institution.logo_path ? (
                                        <img src={institution.logo_path} alt="Logo" className="size-4 shrink-0" />
                                    ) : (
                                        <span className="text-xs font-medium">{institution.code.substring(0, 2)}</span>
                                    )}
                                </div>
                                {institution.name}
                                {currentInstitution?.id === institution.id && <Check className="ml-auto size-4" />}
                            </DropdownMenuItem>
                        ))}
                    </DropdownMenuContent>
                </DropdownMenu>
            </SidebarMenuItem>
        </SidebarMenu>
    );
}
