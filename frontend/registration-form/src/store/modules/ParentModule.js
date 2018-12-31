import Vue from 'vue'

const parentModule = {

  namespaced: true,

  state: {
    changed: false,
    parents: []
  },

  mutations: {

    /** 
     * Create a parent object in store.
     *
     */
    createParent(state, newParent) {
      var temp = [];
      state.parents.forEach(parent => {
        temp.push(parent);
      });
      temp.push(newParent);
      Vue.set(state, 'parents', temp);
      Vue.set(state, 'changed', true);
    },

    /** 
     * Update a parent object in store.
     *
     */
    updateStudent(state, updatedParent) {
      
      var foundExistingParent = false;

      // try to find a current student object to update
      state.parents.forEach((parent, i) => {
        if(parent.id === updatedParent.id) {
          Vue.set(state.students, i, updatedParent);
          Vue.set(state, 'changed', true);
          foundExistingParent = true;
        }
      });

      // if none is found, push a new student to the array
      if(!foundExistingParent || state.parent.length == 0) {
        this.commit('parents/createParent', updatedParent);
      }

    },

  },

  getters: {
    parents: state => state.parents,
    changed: state => state.changed
  }

}

export default parentModule;