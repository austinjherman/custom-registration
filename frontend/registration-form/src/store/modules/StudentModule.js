import Vue from 'vue'

const studentModule = {

  namespaced: true,

  state: {
    students: []
  },

  mutations: {

    /** 
     * Create a student object in store.
     *
     */
    createStudent(state, newStudent) {
      var temp = [];
      state.students.forEach(student => {
        temp.push(student);
      });
      temp.push(newStudent);
      Vue.set(state, 'students', temp);
      Vue.set(state, 'changed', true);
    },

    /** 
     * Update a student object in store.
     *
     */
    updateStudent(state, updatedStudent) {
      
      var foundExistingStudent = false;

      // try to find a current student object to update
      state.students.forEach((student, i) => {
        if(student.id === updatedStudent.id) {
          Vue.set(state.students, i, updatedStudent);
          Vue.set(state, 'changed', true);
          foundExistingStudent = true;
        }
      });

      // if none is found, push a new student to the array
      if(!foundExistingStudent || state.students.length == 0) {
        this.commit('students/createStudent', updatedStudent);
      }

    },

    addParent(state, obj) {
      state.students.forEach((student, i) => {
        if(student.id === obj.student.id) {
          Vue.set(state.students[i], 'parent', obj.parent);
        }
      });
    },

    removeParent(state, obj) {
      state.students.forEach((student, i) => {
        if(student.id === obj.student.id) {
          Vue.set(state.students[i], 'parent', null);
        }
      });
    },

    /**
     * Change the amount of students in store. 
     * This simply un-saves the student so we don't have 
     * to delete the user's input.
     *
     */
    updateNumberOfStudents(state, targetNumberOfStudents) {
      var temp = [];
      state.students.forEach((student, i) => {
      if(i < targetNumberOfStudents) {
        temp.push(student);
      }
      Vue.set(state, 'students', temp);
      Vue.set(state, 'changed', true);
     });
    }

  },

}

export default studentModule;