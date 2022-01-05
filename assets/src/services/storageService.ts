import {User} from "../../typings/User";


interface StorageData {
    /**
     * The current logged in user
     */
    activeUser: User|null;
}

export class StorageService {

    private readonly data: StorageData;

    constructor() {
        if (localStorage.getItem('storageData')) {
            this.data = JSON.parse(localStorage.getItem('storageData') as string) as StorageData;
        } else {
            this.data = {
                activeUser: null
            };
        }
    }

    private writeData() {
        localStorage.setItem('storageData', JSON.stringify(this.data));
    }

    public getActiveUser(): User|null {
        return this.data.activeUser;
    }

    public setActiveUser(user: User|null) {
        this.data.activeUser = user;
        this.writeData();
    }


}