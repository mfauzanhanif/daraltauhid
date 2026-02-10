import { Link, usePage } from '@inertiajs/react';
import { ArrowLeftRight } from 'lucide-react';
import { Breadcrumbs } from '@/APP/components/breadcrumbs';
import { SidebarTrigger } from '@/shared/ui/sidebar';
import { Button } from '@/shared/ui/button';
import type { BreadcrumbItem as BreadcrumbItemType, SharedData } from '@/types';
import portal from '@/routes/portal';

declare function route(name: string, params?: any, absolute?: boolean): string;

export function AppSidebarHeader({
    breadcrumbs = [],
}: {
    breadcrumbs?: BreadcrumbItemType[];
}) {
    const { currentPortal, auth } = usePage<SharedData>().props;
    const portals = auth.available_portals;
    const hasMultiplePortals = portals &&
        ((portals.institutions?.length ?? 0) > 1 ||
            (portals.students?.length ?? 0) > 0 ||
            (portals.admin && (portals.institutions?.length ?? 0) > 0));

    return (
        <header className="flex h-16 shrink-0 items-center gap-2 border-b border-sidebar-border/50 px-6 transition-[width,height] ease-linear group-has-data-[collapsible=icon]/sidebar-wrapper:h-12 md:px-4">
            <div className="flex items-center gap-2 flex-1">
                <SidebarTrigger className="-ml-1" />
                <Breadcrumbs breadcrumbs={breadcrumbs} />
            </div>

            {/* Switch Portal Button */}
            {currentPortal && (
                <div className="flex items-center gap-2">
                    <span className="text-sm text-muted-foreground hidden sm:block">
                        {currentPortal.name}
                    </span>
                    {hasMultiplePortals && (
                        <Button variant="outline" size="sm" asChild>
                            <Link href={portal.switch({ institution: currentPortal.code })}>
                                <ArrowLeftRight className="size-4 mr-1" />
                                <span className="hidden sm:inline">Ganti Lembaga</span>
                            </Link>
                        </Button>
                    )}
                </div>
            )}
        </header>
    );
}
