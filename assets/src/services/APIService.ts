import RestService from "./RestService";
import {LoginResponse} from "../../typings/Responses/LoginResponse";
import {User} from "../../typings/User";
import {CreateUserResponse, DeleteUserRespose, GetAllUsersResponse} from "../../typings/Responses/UserControllerResponses";
import {CreatePermissionGroupResponse, DeletePermissionGroupResponse, GetAllPermissionGroupsResponse} from "../../typings/Responses/PermissionGroupsControllerResponses";
import { CreateElementResponse, CreateTableResponse, DeleteTableResponse, GetAllTablesResponse, GetTableResponse, RemoveElementResponse } from "assets/typings/Responses/TableControllerResponses";

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
    public async getAllTables(): Promise<GetAllTablesResponse> {
        return await this.get<GetAllTablesResponse>('/api/table/getAllTables');
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

    /**
     * Creates a new permission group in the system.
     * 
     * @param name The new name of the poermission group
     * @param groupColor The color of the new permission group
     * @param tables All IDs of the tables that are given
     * @returns  The response of the request
     */
    public async createPermissionGroup(name: string, groupColor: string, tables: number[]): Promise<CreatePermissionGroupResponse> {
        return await this.post<CreatePermissionGroupResponse>('/api/permission-group/createGroup', JSON.stringify({
            name,
            groupColor,
            tables
        }));
    }

    /**
     * Deletes an existing permission group from the system.
     * 
     * @param id The ID of the table that should be deleted
     * @returns The response of the request
     */
    public async deletePermissionGroup(id: number): Promise<DeletePermissionGroupResponse> {
        return await this.post<DeletePermissionGroupResponse>('/api/permission-group/deleteGroup', JSON.stringify({
            groupID: id
        }));
    }

    /**
     * Creates a new table in the system.
     * 
     * @param name The name of the new table
     * @returns The response of the request
     */
    public async createTable(name: string): Promise<CreateTableResponse> {
        return await this.post<CreateTableResponse>('/api/table/createTable', JSON.stringify({
            tableName: name
        }));
    }

    /**
     * Deletes an table from the inventory system.
     * 
     * @param id The ID of the table that should be deleted
     * @returns The response of the request
     */
    public async deleteTable(id: number): Promise<DeleteTableResponse> {
        return await this.post<DeleteTableResponse>('/api/table/deleteTable', JSON.stringify({
            tableID: id,
        }));
    }

    /**
     * Fetches an specific table from the server.
     * 
     * @param id The id of the table that should be fetched
     * @returns The response of the request
     */
    public async getTable(id: number): Promise<GetTableResponse> {
        return await this.post<GetTableResponse>('/api/table/getTable', JSON.stringify({
            tableID: id
        }));
    }

    /**
     * Creates a new table element on the table with the given ID.
     * 
     * @param tableID The ID of the table the element should be added to
     * @param content The content of the element as json
     * @returns The response of the request
     */
    public async createTableElement(tableID: number, content: any): Promise<CreateElementResponse> {
        return await this.post<CreateElementResponse>('/api/table/addElement',  JSON.stringify({
            tableID,
            content,
        }));
    }

    /**
     * Deletes an element from a table. 
     * 
     * @param elementID The ID of the element that should be deleted
     * @returns The response of the request
     */
    public async removeTableElement(elementID: number): Promise<RemoveElementResponse> {
        return await this.post<RemoveElementResponse>('/api/table/removeElement', JSON.stringify({
            elementID
        }));
    }
}