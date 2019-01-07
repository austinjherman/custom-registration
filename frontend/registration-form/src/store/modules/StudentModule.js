import Vue from 'vue'

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
      var student = studentModule.helpers.getStudent(obj.id);

      if(student) {
        // loop through the provided obj
        for(var prop in obj) {
          if(student.isEditable(prop)) {
            Vue.set(student, prop, obj[prop]);
          }
        }
      }
    }

  },

  getters: {
    getNumberOfStudents: state => state.numberOfStudents,
    getStudents: state => state.students
  },

  helpers: {
    getStudent(id) {
      var student = null;
      studentModule.state.students.forEach((s, i) => {
        if(s.id == id) {
          student = s;
          student.key = i;
        }
      });
      return student;
    }
  }

}

export default studentModule;