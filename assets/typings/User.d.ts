import {LoginResponse} from "./Responses/LoginResponse";

export type User = Pick<LoginResponse, 'user' | 'roles'>;