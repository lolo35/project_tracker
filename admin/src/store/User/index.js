export default {
    namespaced: true,
    state: {
        name: "",
        email: "",
        userId: "",
        autoliv_id: "",
        userLoaded: false,
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
        },
        setUserLoaded(state, value){
            state.userLoaded = value;
        },
        setAutolivId(state, value){
            state.autoliv_id = value;
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
        },
        setUserLoaded({ commit }, value){
            commit("setUserLoaded", value);
        },
        setAutolivId({ commit }, value){
            commit('setAutolivId', value);
        }
    },
    getters: {

    }
}