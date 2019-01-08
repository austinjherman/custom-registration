import Vue from 'vue'
import { getParents, getParent } from '../helpers'

const parentModule = {

  namespaced: true,

  state: {
    numberOfParents: 0,
    parents: [],
  },

  mutations: {

    setNumberOfParents(state, n) {
      Vue.set(state, 'numberOfParents', n);
    },

    updateParents(state, parents) {
      Vue.set(state, 'parents', parents);
    },

    /** 
     * Update a parent object in store.
     *
     */
    updateParent(state, obj) {
      // try to find a current parent object to update
      parent = getParent(obj.parentId)
      if(parent) {
        // loop through the provided obj
        for(var prop in obj) {
          Vue.set(parent, prop, obj[prop]);
        }
      }
    },

    updateStudents(state, obj) {
      parent = getParent(obj.parentId);
      if(parent) {
        Vue.set(parent, 'students', obj['studentIds']);
      }
    }

  },

  getters: {
    getNumberOfParents: state => state.numberOfParents,
  },

}

export default parentModule;