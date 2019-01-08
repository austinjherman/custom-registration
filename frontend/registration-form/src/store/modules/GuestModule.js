import Vue from 'vue'

const guestModule = {

  namespaced: true,

  state: {
    firstName: null,
    lastName: null,
    email: null,
    phone: null,
    zip: null,
    poolAccess: null,
    created: null
  },

  mutations: {
    updateGuest(state, obj) {
      for(var prop in obj) {
        if(state.hasOwnProperty(prop)) {
          Vue.set(state, prop, obj[prop]);
        }
      }
    }
  },

}

export default guestModule;