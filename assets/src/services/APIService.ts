import RestService from "./RestService";
import {LoginResponse} from "../../typings/Responses/LoginResponse";
import {User} from "../../typings/User";
import {CreateUserResponse, DeleteUserRespose, GetAllUsersResponse} from "../../typings/Responses/UserControllerResponses";
import {GetAllPermissionGroupsResponse} from "../../typings/Responses/PermissionGroupsControllerResponses";

export default class APIService extends RestService {

    /**
     * Trys to log in the user.
     *
     * @param username The username of the user
     * @param password The password of the user
     * @return LoginResponse The login response of the server
     * @throws Error The possible error from the RestService
     */
    public async login(username: string, password: string): Promise<LoginResponse> {
        return await this.post<LoginResponse>('/api/login', JSON.stringify({
            username,
            password,
        }));
    }

    /**
     * Checks if the user is authorized and updates the state out of this.
     *
     * @throws Error If the user is not authorized
     */
    public async checkLogin(): Promise<User>
    {
        return await this.get<User>('/api/check_login');
    }

    /**
     * Fetches all users from the database.
     */
    public async getAllUsers(): Promise<GetAllUsersResponse> {
        return await this.get<GetAllUsersResponse>('/api/user/allUsers');
    }

    /**
     * Fetches all tables from the database.
     */
    public async getAllTables(): Promise<any> {
        return await this.get<any>('/api/table/getAllTables');
    }

    /**
     * Fetches all permission groups from database.
     */
    public async getAllPermissionGroups(): Promise<GetAllPermissionGroupsResponse> {
        return await this.get<GetAllPermissionGroupsResponse>('/api/permission-group/allGroups');
    }

    /**
     * Creates a new user in the system.
     *
     * @param username The username of the user
     * @param password The password of the user
     * @param permissionGroups All permission group IDs of the user
     */
    public async createUser(username: string, password: string, permissionGroups: number[]): Promise<CreateUserResponse> {
        return await this.post<CreateUserResponse>('/api/user/createUser', JSON.stringify({
            username,
            password,
            permissionGroups
        }));
    }

    /**
     * Deletes an user from the system.
     * 
     * @param userID The ID of the user that should be deleted
     * @returns The response of the request
     */
    public async deleteUser(userID: number): Promise<DeleteUserRespose> {
        return await this.post<DeleteUserRespose>('/api/user/deleteUser', JSON.stringify({userID}));
    }
}