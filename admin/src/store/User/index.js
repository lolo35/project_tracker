export default {
    namespaced: true,
    state: {
        name: "",
        email: "",
        userId: "",
    },
    mutations: {
        setName(state, value){
            state.name = value;
        },
        setEmail(state, value){
            state.email = value;
        },
        setUserId(state, value){
            state.userId = value;
        }
    },
    actions: {
        setName({ commit }, value){
            commit('setName', value);
        },
        setEmail({ commit }, value){
            commit('setEmail', value);
        },
        setUserId({ commit }, value){
            commit('setUserId', value);
        }
    },
    getters: {

    }
}