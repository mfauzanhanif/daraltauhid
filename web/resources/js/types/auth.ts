export type User = {
    id: number;
    name: string;
    email: string;
    avatar?: string;
    email_verified_at: string | null;
    two_factor_enabled?: boolean;
    created_at: string;
    updated_at: string;
    [key: string]: unknown;
};

export type PortalInstitution = {
    id: number;
    code: string;
    name: string;
    type: string;
    url: string;
};

export type PortalStudent = {
    id: number;
    public_id: string;
    name: string;
    url: string;
};

export type AvailablePortals = {
    institutions?: PortalInstitution[];
    students?: PortalStudent[];
    admin?: {
        name: string;
        url: string;
    };
};

export type Auth = {
    user: User;
    roles?: string[];
    available_portals?: AvailablePortals;
};

export type TwoFactorSetupData = {
    svg: string;
    url: string;
};

export type TwoFactorSecretKey = {
    secretKey: string;
};
