export default {
    namespaced: true,
    state: {
        team: false,
        teamComp: [],
        showModal: false,
    },
    mutations: {
        setTeam(state, value){
            state.team = value;
        },
        setTeamComp(state, value){
            state.teamComp = value;
        },
        setShowModal(state, value){
            state.showModal = value;
        },
        addTeamMember(state, value){
            state.teamComp.push(value);
        },
        removeTeamMember(state, value){
            for(let i = 0; i < state.teamComp.length; i++){
                if(value === state.teamComp[i].id){
                    state.teamComp.splice(i, 1);
                    break;
                }
            }
        }
    },
    actions: {
        setTeam({ commit }, value){
            commit("setTeam", value);
        },
        setTeamComp({ commit }, value){
            commit("setTeamComp", value);
        },
        setShowModal({ commit }, value){
            commit("setShowModal", value);
        },
        addTeamMember({ commit }, value){
            commit("addTeamMember", value);
        },
        removeTeamMember({ commit }, value){
            commit("removeTeamMember", value);
        }
    },
    getters: {

    }
}