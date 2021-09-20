import { createStore } from 'vuex';
import navigation from './Navigation/index';
import teams from './Teams/index';

export default createStore({
  state: {
    url: "http://localhost/lumen/project_mgmt/public/"
  },
  mutations: {
  },
  actions: {
  },
  modules: {
    navigation,
    teams,
  },
  getters: {

  }
})
