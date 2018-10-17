import Vue from 'vue'
import Vuex from 'vuex'
import axios from 'axios'

Vue.use(Vuex);

export default new Vuex.Store({
  state: {
    currentEmail: null,
    user: JSON.parse(localStorage.getItem('user')),
    notifications: []
  },
  getters: {
    isLoggedIn(state) {
      return !!state.user;
    }
  },
  mutations: {
    changeCurrentEmail(state, email) {
      state.currentEmail = email;
    },
    login(state, user) {
      state.user = user;
    },
    logout(state) {
      state.user = null;
    },
    addNotification(state, notification) {
      state.notifications.push(notification);
    },
    removeNotification(state, index) {
      delete state.notifications[index];
    }
  },
  actions: {
    login(context, data) {
      return new Promise((resolve, reject) => {
        context.commit('logout');
        axios.post('/oauth/auth', {
          grant_type: 'password',
          username: data.username,
          password: data.password,
          client_id: 'app',
          client_secret: '',
          access_type: 'offline',
        })
          .then(response => {
            const user = response.data;
            localStorage.setItem('user', JSON.stringify(user));
            axios.defaults.headers.common['Authorization'] = 'Bearer ' + user.access_token;
            context.commit('login', user);
            resolve(user)
          })
          .catch(error => {
            context.commit('logout');
            localStorage.removeItem('user');
            reject(error)
          })
      })
    },
    logout(context) {
      return new Promise((resolve) => {
        context.commit('logout');
        localStorage.removeItem('user');
        delete axios.defaults.headers.common['Authorization'];
        resolve()
      });
    },
    refresh(context) {
      return new Promise((resolve, reject) => {
        if (context.state.user) {
          delete axios.defaults.headers.common['Authorization'];
          return axios.post('/oauth/auth', {
            grant_type: 'refresh_token',
            refresh_token: context.state.user.refresh_token,
            client_id: 'app',
            client_secret: '',
          })
            .then(response => {
              const user = response.data;
              localStorage.setItem('user', JSON.stringify(user));
              axios.defaults.headers.common['Authorization'] = 'Bearer ' + user.access_token;
              context.commit('login', user);
              resolve(response)
            })
            .catch(error => {
              context.dispatch('logout');
              reject(error)
            })
        }
        resolve()
      });
    }
  }
})
