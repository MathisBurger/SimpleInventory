import RestService from "./RestService";
import {LoginResponse} from "../../typings/Responses/LoginResponse";
import {User} from "../../typings/User";
import {GetAllUsersResponse} from "../../typings/Responses/UserControllerResponses";

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
    public async getAllPermissionGroups(): Promise<any> {
        return await this.get<any>('/api/permission-group/allGroups');
    }
}