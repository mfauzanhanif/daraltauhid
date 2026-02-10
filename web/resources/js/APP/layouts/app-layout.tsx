import { AppContent } from '@/APP/components/app-content';
import { AppShell } from '@/APP/components/app-shell';
import { AppSidebar } from '@/APP/components/app-sidebar';
import { AppSidebarHeader } from '@/APP/components/app-sidebar-header';
import type { AppLayoutProps } from '@/types';

export default ({ children, breadcrumbs, ...props }: AppLayoutProps) => {
    return (
        <AppShell variant="sidebar" {...props}>
            <AppSidebar />
            <AppContent variant="sidebar" className="overflow-x-hidden">
                <AppSidebarHeader breadcrumbs={breadcrumbs} />
                {children}
            </AppContent>
        </AppShell>
    );
}