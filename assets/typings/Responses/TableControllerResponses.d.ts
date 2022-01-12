import { Table } from "../Table";

export interface GetAllTablesResponse {
    tables: Table[];
}

export interface CreateTableResponse {
    message: string;
    table?: Table;
}

export interface DeleteTableResponse {
    message: string;
}