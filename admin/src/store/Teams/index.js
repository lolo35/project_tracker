export default {
    namespaced: true,
    state: {
        showModal: false,
        teamName: "",
        users: [],
        teamMembers: [],
        teamLeader: "",
    },
    mutations: {
        setShowModal(state){
            state.showModal = !state.showModal;
        },
        setTeamName(state, value){
            state.teamName = value;
        },
        setUsers(state, value){
            state.users = value;
        },
        setTeamMembers(state, value){
            state.teamMembers.push(value);
        },
        setTeamLeader(state, value){            
            state.teamLeader = state.teamMembers[value];
        },
        removeTeamLeader(state){
            state.teamLeader = "";
        }
    },
    actions: {
        setShowModal({ commit }){
            commit('setShowModal');
        },
        setTeamName({ commit }, value){
            commit("setTeamName", value);
        },
        setUsers({ commit }, value){
            commit('setUsers', value);
        },
        setTeamMembers({ commit }, value){
            commit('setTeamMembers', value);
        },
        setTeamLeader({ commit }, value){   
            commit('setTeamLeader', value);
        },
        removeTeamLeader({ commit }){
            commit('removeTeamLeader');
        }
    },
    getters: {

    }
}