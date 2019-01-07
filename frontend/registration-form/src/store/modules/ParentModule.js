import Vue from 'vue'

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
      parent = parentModule.helpers.getParent(obj.id);

      if(parent) {

        // loop through the provided obj
        for(var prop in obj) {

          // if we have a studentId, then we need to add to students array
          if(prop == 'studentId' && obj.action == 'add') {
            this.commit('parents/addStudent', obj);
          }

          else if(prop == 'studentId' && obj.action == 'remove') {
            this.commit('parents/removeStudent', obj);
          }

          else if(parent.isEditable(prop)) {
            Vue.set(parent, prop, obj[prop]);
          }

        }

      }

    },

    addStudent(state, obj) {

      var temp    = [],
          parent  = parentModule.helpers.getParent(obj.id),
          student = getStudent(id);

      if(parent && student) {
        parent.students.forEach(s => {
          temp.push(s);
          s.parent = parent;
        });
        temp.push(student);
        Vue.set(parent, 'students', temp);
      }

    },

    removeStudent(state, obj) {
      
      var temp    = [],
          parent  = parentModule.helpers.getParent(obj.id),
          student = parentModule.helpers.getStudent(obj.studentId);

      if(parent && student) {
        parent.students.forEach(s => {
          if(s.id != obj.studentId) {
            temp.push(s);
          }
          else {
            s.parent = null;
          }
        });
        temp.push(student);
        Vue.set(parent, 'students', temp);
      }

    },

  },

  getters: {
    getNumberOfParents: state => state.numberOfParents,
    getParents: state => state.parents,
    getStudents: (state, getters, rootState, rootGetters) => {
      return rootGetters['students/getStudents'];
    }
  },

  helpers: {

    /**
     * This function returns an existing parent object.
     * If a parent object with the exiting ID does not
     * already exist, it will create one.
     *
     */
    getParent(id) {

      var parent = null,
          parents = parentModule.state.parents;
      
      // search for existing parent
      parents.forEach((p, i) => {
        if(p.id == id) {
          parent = p;
          parent.key = i;
        }
      });

      return parent;

    },

    getStudent(id) {
      
      var student = null,
          students = parentModule.getters.getStudents;

      console.log('getStudent students: ', students);

      students.forEach(s => {
        if(s.id == id) {
          student = s;
        }
      });

      return student;

    }

  }

}

export default parentModule;