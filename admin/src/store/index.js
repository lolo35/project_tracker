import { createStore } from 'vuex';
import navigation from './Navigation/index';
import teams from './Teams/index';
import login from './Login/index';
import user from './User/index';
import team from './Team/index';

export default createStore({
  state: {
    url: "http://localhost/lumen/project_mgmt/public/",
    isLogged: false,
  },
  mutations: {
    setIsLogged(state){
      state.isLogged = !state.isLogged;
    }
  },
  actions: {
    setIsLogged({ commit }){
      commit('setIsLogged');
    }
  },
  modules: {
    navigation,
    teams,
    login,
    user,
    team,
  },
  getters: {

  }
})
