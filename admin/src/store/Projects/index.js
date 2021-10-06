export default {
    namespaced: true,
    state: {
        projects: [],
        showAddProjectModal: false,
        searchTerm: "",
    },
    mutations: {
        setProjects(state,value){
            state.projects = value;
        },
        toggleShowProjectModal(state){
            state.showAddProjectModal = !state.showAddProjectModal;
        },
        addProject(state, value){
            state.projects.push(value);
        },
        setSearchTerm(state,value){
            state.searchTerm = value;
        }
    },
    actions: {
        setProjects({ commit }, value) {
            commit("setProjects", value);
        },
        toggleShowProjectModal({ commit }){
            commit("toggleShowProjectModal");
        },
        addProject({ commit }, value){
            commit("addProject", value);
        },
        setSearchTerm({ commit }, value){
            commit("setSearchTerm", value);
        }
    },
    getters: {
        filteredProjects: (state) => {
            if(state.searchTerm.length > 0){
                const search = state.searchTerm.toString().toLowerCase();
                return state.projects.filter(element => {
                    const name = element.name.toString().toLowerCase();
                    //console.log(name.includes(search));
                    return name.includes(search);
                });
            }
            return state.projects;        
        }
    }
}