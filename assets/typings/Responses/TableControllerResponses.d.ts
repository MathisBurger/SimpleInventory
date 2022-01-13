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

export interface GetTableResponse {
    message?: string;
    table?: Table;
}

export interface CreateElementResponse {
    message: string;
    table?: Table;
}

export interface RemoveElementResponse {
    message: string;
    table?: Table;
}