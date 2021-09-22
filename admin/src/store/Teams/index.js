export default {
    namespaced: true,
    state: {
        showModal: false,
        teamName: "",
        users: [],
        teamMembers: [],
        teamLeader: "",
        teams: {},
        teamRows: 2,
    },
    mutations: {
        resetTeamRows(state){
            state.teamRows = 2;
        },
        addTeamRow(state){
            state.teamRows += 2;
        },
        setShowModal(state){
            state.showModal = !state.showModal;
            state.teamRows = 2;
        },
        setTeamName(state, value){
            state.teamName = value;
        },
        setUsers(state, value){
            state.users = value;
        },
        setTeamMembers(state, value){
            if(value !== undefined){
                state.teamMembers.push(value);
            }
        },
        setTeamLeader(state, value){            
            state.teamLeader = state.teamMembers[value];
        },
        removeTeamLeader(state){
            state.teamLeader = "";
        },
        setTeams(state, value){
            //state.teams = value;
            let groupedTeams = {};
            for(let i = 0; i < value.length; i++){
                if(!(value[i].team in groupedTeams)){
                    groupedTeams[`${value[i].team}`] = {};
                }
                if(!('members' in groupedTeams[`${value[i].team}`])){
                    groupedTeams[`${value[i].team}`]['members'] = [];
                }
                if(!('leader' in groupedTeams[`${value[i].team}`])){
                    groupedTeams[`${value[i].team}`]['leader'] = value[i].leader;
                }
                groupedTeams[`${value[i].team}`]['members'].push(value[i].members);
                groupedTeams[`${value[i].team}`]['teamId'] = parseInt(value[i].teamId);
            }

            Object.assign(state.teams, groupedTeams);
        },
        removeTeam(state, value){
            delete state.teams[`${value}`];
        }
    },
    actions: {
        resetTeamRows({ commit }){
            commit('resetTeamRows');
        },
        addTeamRow({ commit }){
            commit('addTeamRow');
        },
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
        },
        setTeams({ commit }, value){
            commit('setTeams', value);
        },
        removeTeam({ commit }, value){
            commit('removeTeam', value);
        }
    },
    getters: {

    }
}