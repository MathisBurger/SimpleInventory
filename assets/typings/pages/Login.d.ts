import APIService from "@/services/APIService";

export interface LoginData {
    rules: any[];
    username: string;
    password: string;
    apiService: APIService;
}

export interface LoginMethods {
    login: () => void;
}