import Vue from 'vue'
import { getStudent } from '../helpers'

const studentModule = {

  namespaced: true,

  state: {
    numberOfStudents: 0,
    students: []
  },

  mutations: {

    setNumberOfStudents(state, n) {
      Vue.set(state, 'numberOfStudents', n);
    },

    updateStudents(state, students) {
      Vue.set(state, 'students', students);
    },

    /** 
     * Update a student object in store.
     *
     */
    updateStudent(state, obj) {

      // try to find a current parent object to update
      var student = getStudent(obj.id);

      if(student) {
        // loop through the provided obj
        for(var prop in obj) {
          Vue.set(student, prop, obj[prop]);
        }
      }
    }

  },

  getters: {
    getNumberOfStudents: state => state.numberOfStudents,
    getStudents: state => state.students
  },

}

export default studentModule;