import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex);

export default new Vuex.Store({
  state: {
    currentEmail: null
  },
  mutations: {
    changeCurrentEmail(state, email) {
      state.currentEmail = email;
    }
  },
  actions: {

  }
})
