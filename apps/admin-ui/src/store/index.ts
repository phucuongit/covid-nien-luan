import { createStore } from "vuex"

export default createStore({
  state: {
    user: {}
  },
  mutations: {
    SET_USER: (state, _user: any) => (state.user = _user)
  },
  actions: {
    setUser: ({ commit }, _user: any) => commit("SET_USER", _user)
  },
  modules: {}
})
