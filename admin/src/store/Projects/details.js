export default {
    namespaced: true,
    state: {
        details: [],
        searchTerm: "",
    },
    mutations: {
        setDetails(state, value){
            state.details = value;
        },
        setSearchTerm(state, value){
            state.searchTerm = value;
        },
        sortByStatus(state, value){
            const arr = state.details.slice(0);
            if(value == "up"){
                state.details = arr.sort(function(a, b){
                    return b.status - a.status;
                });
            }
            if(value == "down"){
                state.details = arr.sort(function(a, b){
                    return a.status - b.status;
                });
            }
        },
        sortByCreated(state, value){
            const arr = state.details.slice(0);
            if(value == "up"){
                state.details = arr.sort(function(a, b){
                    let date1 = new Date(b.created_at);
                    let date2 = new Date(a.created_at);
                    return date1 - date2;
                });
            }
            if(value == "down"){
                state.details = arr.sort(function(a, b){
                    let date1 = new Date(a.created_at);
                    let date2 = new Date(b.created_at);
                    return date1 - date2;
                });
            }
        },
        sortByTimeSpent(state, value){
            const arr = state.details.slice(0);
            if(value == "up"){
                state.details = arr.sort(function(a, b){
                    return b.minutesSpent - a.minutesSpent;
                });
            }
            if(value == "down"){
                state.details = arr.sort(function(a, b){
                    return a.minutesSpent - b.minutesSpent;
                });
            }
        }
    },
    actions: {
        setDetails({ commit }, value){
            commit('setDetails', value);
        },
        setSearchTerm({ commit }, value){
            commit("setSearchTerm", value);
        },
        setSortByStatus({ commit }, value){
            commit('sortByStatus', value);
        },
        sortByCreated({ commit }, value){
            commit('sortByCreated', value);
        },
        sortByTimeSpent({ commit }, value){
            commit('sortByTimeSpent', value);
        }
    },
    getters: {
        filteredDetails: (state) => {
            const search = state.searchTerm.toString().toLowerCase();
            return state.details.filter(element => {
                const task = element.task.toString().toLowerCase();
                const name = element.username.toString().toLowerCase();

                return task.includes(search) || name.includes(search);
            }); 
        },
        // sortByStatus: (state) => {
        //     const arr = state.details.slice(0);
        //     return arr.sort(function(a, b) {
        //         return b.status - a.status;
        //     })
        // }
    }
}