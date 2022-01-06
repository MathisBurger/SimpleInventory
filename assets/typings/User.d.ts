import {LoginResponse} from "./Responses/LoginResponse";
import {PermissionGroup} from "./PermissionGroup";

export type User = Pick<LoginResponse, 'userIdentifier' | 'roles'>
    & {permissionGroups?: PermissionGroup[], id?: number};