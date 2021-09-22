export default {
    namespaced: true,
    state: {
        user: "",
        pass: "",
    },
    mutations: {
        setUser(state, value){
            state.user = value;
        },
        setPass(state, value){
            state.pass = value;
        }
    },
    actions: {
        setUser({ commit }, value){
            commit('setUser', value);
        },
        setPass({ commit }, value){
            commit('setPass', value);
        }
    },
    getters: {

    }
}