// importing policies
import policies from "./policies";

export default {
    install(Vue, options) {
        Vue.prototype.authorize = function(policy, model) {
            // check if the user is signed in
            if (!window.Auth.signedIn) return false;

            // check if teh policy is a string
            // and the model is an object
            if (typeof policy === "string" && typeof model === "object") {
                const user = window.Auth.user;

                return policies[policy](user, model);
            }
        };

        Vue.prototype.signedIn = window.Auth.signedIn;
    }
};
