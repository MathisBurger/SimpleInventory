export interface PermissionGroupUserType {
    id: number;
    name: string;
}

export interface PermissionGroupTableType {
    id: number;
    name: string;
}

export interface PermissionGroup {
    id: number;
    name: string;
    groupColor: string;
    tables: PermissionGroupTableType[];
    users: PermissionGroupUserType[];
}