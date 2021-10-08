export default {
    namespaced: true,
    state: {
        tasks: [],
    },
    mutations: {
        setTasks(state, value){
            state.tasks = value;
        },
        updateTasks(state, value){
            state.tasks.push(value);
        },
        deleteTask(state, index){
            state.tasks.splice(index, 1);
        }
    },
    actions: {
        setTasks({ commit }, value){
            commit('setTasks', value);
        },
        updateTasks({ commit }, value){
            commit('updateTasks', value);
        },
        deleteTask({ commit }, index){
            commit('deleteTask', index);
        }
    },
    getters: {

    }
}