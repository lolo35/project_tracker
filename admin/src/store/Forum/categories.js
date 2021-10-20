export default {
    namespaced: true,
    state: {
        categories: [],
    },
    mutations: {
        setCategories(state, value){
            state.categories = value;
        }
    },
    actions: {
        setCategories({ commit }, value){
            commit('setCategories', value);
        }
    },
    getters: {

    }
}