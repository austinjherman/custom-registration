import Vue from 'vue'

const parentModule = {

  namespaced: true,

  state: {
    count: 0,
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
    },

    /** 
     * Update a parent object in store.
     *
     */
    updateParent(state, obj) {
      
      var foundExistingParent = false;

      // try to find a current parent object to update
      state.parents.forEach((parent, i) => {
        if(parent.id === obj.id) {
          for(var prop in obj) {
            if(prop !== 'id') {
              Vue.set(state.parents[i], prop, obj[prop]);
            }
          }
          foundExistingParent = true;
        }
      });

      // if none is found, push a new parent to the array
      if(!foundExistingParent || state.parents.length == 0) {
        this.commit('parents/createParent', obj);
      }

    },

    addStudent(state, obj) {

      var student,
          students = this.getters['students/getStudents'];

      students.forEach(s => {
        if(s.id == obj.studentId) {
          student = s;
        }
      });
    
      if(student) {
        // look for the parent in question
        state.parents.forEach((parent, i) => {
          // if found add, the student in question to parent's students
          // have to rebuild the array to maintain reactivity
          if(parent.id === obj.parentId) {
            var temp = [];
            parent.students.forEach(student => {
              temp.push(student);
            });
            temp.push(student);
            Vue.set(state.parents[i], 'students', temp);
          }
        });
      }

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

    changeAmount(state, n) {
      Vue.set(state, 'count', n);
      var temp = [];
      state.parents.forEach((parent, i) => {
        if(i < n) {
          temp.push(parent);
        }
      });
      Vue.set(state, 'parents', temp);
    }

  },

  getters: {
    getCount: state => state.count,
    getParents: state => state.parents,
    getStudents (state, getters, rootState, rootGetters) {
      return rootGetters['students/getStudents'];
    }
  },

}

export default parentModule;