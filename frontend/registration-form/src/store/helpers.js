import store from './store'

/**
 | ---------------------------------------
 |  Guest Helpers
 | ---------------------------------------
 */
export function getGuest() {
  return store.state.guest;
}


/**
 | ---------------------------------------
 |  Parent Helpers
 | ---------------------------------------
 */
export function getParent(id) {
  var parent = null;
  store.state.parents.parents.forEach(p => {
    if (p.id == id) {
      parent = p;
    }
  });
  return parent;
}

export function getParents() {
  return store.state.parents.parents;
}


/**
 | ---------------------------------------
 |  Student Helpers
 | ---------------------------------------
 */
export function getStudent(id) {
  var student = null;
  store.state.students.students.forEach(s => {
    if (s.id == id) {
      student = s;
    }
  });
  return student;
}

export function getStudents() {
  return store.state.students.students;
}

/**
 | ---------------------------------------
 |  API Helpers
 | ---------------------------------------
 */
export function sendParentsAndStudentsToApi() {
  var parentRequests  = [],
      studentRequests = [],
      request  = {};
}
