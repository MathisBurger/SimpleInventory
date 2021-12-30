
interface GlobalStoreType {
    /**
     * Global state
     */
    state: {
        /**
         * The active user that is currently logged in
         */
        activeUser: null
    },
    /**
     * All setter to set the states
     */
    setter: {
        /**
         * Setts the currently logged in user
         */
        setActiveUser: () => void;
    }
}


var stores: GlobalStoreType = {
    setter: {
        setActiveUser: function () {
        }
    },
    state: {
        activeUser: null
    }
}

export default stores;