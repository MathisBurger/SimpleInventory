import RestService from "./RestService";
import {LoginResponse} from "../../typings/Responses/LoginResponse";
import {User} from "../../typings/User";
import {CreateUserResponse, DeleteUserRespose, GetAllUsersResponse, UpdateUserResponse} from "../../typings/Responses/UserControllerResponses";
import {AddTableToPermissionGroupResponse, AddUserToPermissionGroupResponse, CreatePermissionGroupResponse, DeletePermissionGroupResponse, GetAllPermissionGroupsResponse, RemoveTableFromPermissionGroupResponse, RemoveUserFromPermissionGroupResponse} from "../../typings/Responses/PermissionGroupsControllerResponses";
import { CreateElementResponse, CreateTableResponse, DeleteTableResponse, GetAllTablesResponse, GetTableResponse, RemoveElementResponse, UpdateElementResponse } from "assets/typings/Responses/TableControllerResponses";
import { PermissionLevels } from "../permissions";

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
     * @param roles All roles the user should have
     */
    public async createUser(username: string, password: string, permissionGroups: number[], roles: PermissionLevels[]): Promise<CreateUserResponse> {
        return await this.post<CreateUserResponse>('/api/user/createUser', JSON.stringify({
            username,
            password,
            permissionGroups,
            roles
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

    /**
     * Updates an element in a table.
     * 
     * @param elementID The ID of the element that should be updated
     * @param content The new content of the element
     * @returns  The response of the request
     */
    public async updateTableElement(elementID: number, content: any): Promise<UpdateElementResponse> {
        return await this.post<UpdateElementResponse>('/api/table/updateElement', JSON.stringify({
            elementID,
            content
        }));
    }

    /**
     * Updates an user.
     * 
     * @param id The ID of the user that should be updated.
     * @param username The new username.
     * @param permissionGroups All permission group IDs the user should have
     * @param roles All the roles the user should have
     * @returns The response of the request
     */
    public async updateUser(
        id: number,
        username: string,
        permissionGroups: number[],
        roles: PermissionLevels[]
    ): Promise<UpdateUserResponse> {
        return await this.post<UpdateUserResponse>('/api/user/updateUser', JSON.stringify({
            id: id,
            username: username,
            permissionGroups: permissionGroups,
            roles: roles
        }));
    }

    /**
     * Removes an user from a permissionGroup.
     * 
     * @param groupID The ID of the group the user should be removed from
     * @param userID The ID of the user that should be removed
     * @returns The response of the request
     */
    public async removeUserFromPermissionGroups(
        groupID: number,
        userID: number
    ): Promise<RemoveUserFromPermissionGroupResponse> {
        return await this.post<RemoveUserFromPermissionGroupResponse>(
            '/api/permission-group/removeUser',
            JSON.stringify({groupID, userID})
        );
    }

    /**
     * Adds an user to the permission group.
     * 
     * @param groupID The ID of the group, the user should be added to
     * @param userID The ID of the user that should be added
     * @returns The response of the request
     */
    public async addUserToPermissionGroup(
        groupID: number,
        userID: number
    ): Promise<AddUserToPermissionGroupResponse> {
        return await this.post<AddUserToPermissionGroupResponse>(
            '/api/permission-group/addUser',
            JSON.stringify({groupID, userID})
        );
    }

    /**
     * Adds an table to a permission-group.
     * 
     * @param groupID The ID of the group that the table should be added to
     * @param tableID The ID of the table that should be added
     * @returns The response of the request
     */
    public async addTableToPermissionGroup(
        groupID: number,
        tableID: number
    ): Promise<AddTableToPermissionGroupResponse> {
        return await this.post<AddUserToPermissionGroupResponse>(
            '/api/permission-group/addTable',
            JSON.stringify({groupID, tableID})
        );
    }

    /**
     * Removes an table from a permission-group.
     * 
     * @param groupID The ID of the group the table should be added to
     * @param tableID The ID of the tabel that should be added.
     * @returns The response of the request
     */
    public async removeTableFromPermissionGroup(
        groupID: number,
        tableID: number
    ): Promise<RemoveTableFromPermissionGroupResponse> {
        return await this.post<RemoveTableFromPermissionGroupResponse>(
            '/api/permission-group/removeTable',
            JSON.stringify({groupID, tableID})
        );
    }
}