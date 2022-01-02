import {PermissionLevels} from "@/permissions";

export interface LoginResponse {
    user: string;
    token: string;
    roles: PermissionLevels[];
}