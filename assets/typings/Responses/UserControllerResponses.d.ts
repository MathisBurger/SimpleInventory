import {User} from "../User";

export interface GetAllUsersResponse {
    users: User[];
}

export interface CreateUserResponse {
    message: string;
    user?: User;
}

export interface DeleteUserRespose {
    message: string;
    success?: boolean;
}