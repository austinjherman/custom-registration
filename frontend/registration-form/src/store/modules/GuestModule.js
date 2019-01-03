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
          state[prop] = obj[prop];
        }
      }
    }
  },

  getters: {
    firstName(state) {
      return state.firstName;
    },
    lastName(state) {
      return state.lastName;
    },
    email(state) {
      return state.email;
    },
    phone(state) {
      return state.phone;
    },
    zip(state) {
      return state.zip;
    },
    poolAccess(state) {
      return state.poolAccess;
    },
    created(state) {
      return state.created;
    }
  }

}

export default guestModule;