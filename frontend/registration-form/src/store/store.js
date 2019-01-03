import Vue from 'vue'
import Vuex from 'vuex'
import guestModule   from './modules/GuestModule.js'
import studentModule from './modules/StudentModule.js'
import parentModule  from './modules/ParentModule.js'

Vue.use(Vuex)

export default new Vuex.Store({

  modules: {
    guest: guestModule,
    parents: parentModule,
    students: studentModule
  },

  state: {
    guestIsOnlyParent: true
  },

  mutations: {
    setGuestIsOnlyParent(state, b) {
      state.guestIsOnlyParent = b;
    }
  },

  getters: {
    guestIsOnlyParent: state => state.guestIsOnlyParent
  }

})