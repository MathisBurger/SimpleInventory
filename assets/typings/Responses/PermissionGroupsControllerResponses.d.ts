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

export interface RemoveUserFromPermissionGroupResponse {
    message: string;
}

export interface AddUserToPermissionGroupResponse {
    message: string;
}

export interface AddTableToPermissionGroupResponse {
    message: string;
}

export interface RemoveTableFromPermissionGroupResponse {
    message: string;
}