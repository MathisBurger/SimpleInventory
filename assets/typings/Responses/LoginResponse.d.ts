import {PermissionLevels} from "@/permissions";

export interface LoginResponse {
    userIdentifier: string;
    token: string;
    roles: PermissionLevels[];
}