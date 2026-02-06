import { Head, router } from '@inertiajs/react';
import { Building2 } from 'lucide-react';
import AuthLayout from '@/layouts/auth-layout';

declare function route(name: string, params?: Record<string, unknown>): string;

type Institution = {
    id: number;
    name: string;
    nickname: string;
    code: string;
    category: string;
    logo_path?: string;
};

type Props = {
    institutions: Institution[];
};

export default function SelectInstitution({ institutions }: Props) {
    const handleSelect = (institutionId: number) => {
        router.post(route('institution.switch'), {
            institution_id: institutionId,
        });
    };

    return (
        <AuthLayout
            title="Pilih Lembaga"
            description="Pilih lembaga yang ingin Anda akses"
        >
            <Head title="Pilih Lembaga" />

            <div className="grid gap-3">
                {institutions.map((institution) => (
                    <button
                        key={institution.id}
                        onClick={() => handleSelect(institution.id)}
                        className="flex items-center gap-4 rounded-lg border p-4 text-left transition-colors hover:bg-accent hover:text-accent-foreground focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2"
                    >
                        <div className="flex size-12 shrink-0 items-center justify-center rounded-lg bg-primary text-primary-foreground">
                            {institution.logo_path ? (
                                <img
                                    src={institution.logo_path}
                                    alt={institution.name}
                                    className="size-8"
                                />
                            ) : (
                                <Building2 className="size-6" />
                            )}
                        </div>
                        <div className="min-w-0 flex-1">
                            <div className="truncate font-medium">
                                {institution.nickname || institution.name}
                            </div>
                            <div className="truncate text-sm text-muted-foreground">
                                {institution.category}
                            </div>
                        </div>
                    </button>
                ))}
            </div>

            {institutions.length === 0 && (
                <div className="text-center text-muted-foreground">
                    Anda tidak memiliki akses ke lembaga manapun.
                </div>
            )}
        </AuthLayout>
    );
}
