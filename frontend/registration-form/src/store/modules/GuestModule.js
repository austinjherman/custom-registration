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
    getFirstName(state) {
      return state.firstName;
    },
    getLastName(state) {
      return state.lastName;
    },
    getEmail(state) {
      return state.email;
    },
    getPhone(state) {
      return state.phone;
    },
    getZip(state) {
      return state.zip;
    },
    getPoolAccess(state) {
      return state.poolAccess;
    },
    getCreated(state) {
      return state.created;
    }
  }

}

export default guestModule;