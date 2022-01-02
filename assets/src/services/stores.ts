import {User} from "../../typings/User";

interface GlobalStoreType {
    /**
     * Global state
     */
    state: {
        /**
         * The active user that is currently logged in
         */
        activeUser: User | null
    },
    /**
     * All setter to set the states
     */
    setter: {
        /**
         * Setts the currently logged in user
         */
        setActiveUser: (user: User) => void;
    }
}


var stores: GlobalStoreType = {
    setter: {
        setActiveUser: function (user: User) {
            stores.state.activeUser = user;
        }
    },
    state: {
        activeUser: null
    }
}

export default stores;