import {PermissionGroup} from "../PermissionGroup";

export interface GetAllPermissionGroupsResponse {
    groups: PermissionGroup[];
}

export interface CreatePermissionGroupResponse {
    message: string;
    group?: PermissionGroup[];
}

export interface DeletePermissionGroupResponse {
    message: string;
}