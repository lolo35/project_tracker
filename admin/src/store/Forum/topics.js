export default {
    namespaced: true,
    state: {
        topics: [],
    },
    mutations: {
        setTopics(state, value){
            console.log(value);
            state.topics[`${value[0].category_id}`] = value;
        }
    },
    actions: {
        setTopics({ commit }, value){
            commit('setTopics', value);
        }
    },
    getters: {

    }
}