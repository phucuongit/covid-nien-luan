import { createStore } from "vuex"
import axios from "axios"
import router from "@/router"
export default createStore({
  state: {
    user: {
      username: '',
      password: ''
    },
    token: '',
    stateLogin: false
  },
  mutations: {
    SET_TOKEN (state, tokenPayload) {
      state.token = tokenPayload
    },
    SET_USER (state, userPayload) {
      state.user = userPayload
    },
    SET_STATE_LOGIN (State, stateLoginPayload) {
      State.stateLogin = stateLoginPayload
    }
  },
  actions: {
    setToken ({commit}, tokenPayload) {
      commit ('SET_TOKEN', tokenPayload)
    },
    setUser ({commit}, userPayload) {
      commit ('SET_USER', userPayload)
    },
    login({commit}, loginPayload) {
      axios.post("https://api-nienluan.sharenows.com/api/v1/auth/login", loginPayload)
      .then (response => {
        console.log(response);
        localStorage.setItem('token', response.data.data.token)
        router.push({name: 'Admin'});
        commit('SET_STATE_LOGIN', false)
      })
      .catch (error => {
        commit('SET_STATE_LOGIN', true)
        console.log(error)
      })
    }
  },
  modules: {}
})
