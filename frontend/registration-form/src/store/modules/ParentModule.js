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
      console.log('temp: ', temp);
      Vue.set(state, 'parents', temp);
      Vue.set(state, 'changed', true);
    },

    /** 
     * Update a parent object in store.
     *
     */
    updateParent(state, updatedParent) {
      
      var foundExistingParent = false;

      // try to find a current parent object to update
      state.parents.forEach((parent, i) => {
        if(parent.id === updatedParent.id) {

          // save the parent
          Vue.set(state.parents, i, updatedParent);
          Vue.set(state, 'changed', true);
          foundExistingParent = true;

        }
      });

      // if none is found, push a new parent to the array
      if(!foundExistingParent || state.parents.length == 0) {
        this.commit('parents/createParent', updatedParent);
      }

    },

    addStudent(state, obj) {

      console.log('state.parents: ', state.parents);
    
      // look for the parent in question
      state.parents.forEach((parent, i) => {

        // if found add, the student in question to parent's students
        // have to rebuild the array to maintain reactivity
        if(parent.id === obj.parent.id) {
          var temp = [];
          parent.students.forEach(student => {
            temp.push(student);
          });
          temp.push(obj.student);
          Vue.set(state.parents[i], 'students', temp);
        }

      });

    },

    removeStudent(state, obj) {
      
      // look for the parent in question
      state.parents.forEach((parent, i) => {

        // if found, remove student in question
        // have to rebuild array to maintain reactivity
        if(parent.id == obj.parent.id) {
          if(parent.students.length) {
            var temp = [];
            parent.students.forEach(student => {
              if(student.id != obj.student.id) {
                temp.push(student);
              }
            });
            Vue.set(state.parents[i], 'students', ['what', 'the', 'heck']);
          }
        }
      });

    },

  },

  getters: {
    parents: state => state.parents,
    changed: state => state.changed
  }

}

export default parentModule;