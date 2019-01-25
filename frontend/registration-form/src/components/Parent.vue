<template>
  <div class="fscr-parent-component">

    <fieldset class="fscr-fieldset">
      <span class="fscr-d-block fscr-input-label">Parent for which students?</span>
      <div v-for="student in this.$parent.$refs.students" :key="student.id">
        <label>
          <input type="checkbox" @change="handleParentStudentRelationship" :data-student-id="student.id" :data-parent-id="id" ref="studentCheckboxes">
          <span>{{ student.name }}</span>
        </label>
      </div>
      <span class="fscr-d-block fscr-input-error">{{ studentsEmptyError }}</span>
    </fieldset>

    <div class="fscr-d-flex wrap">
    
      <div class="fscr-input-wrap fscr-input-wrap--half">
        <label class="fscr-input-label">
          <span class="fscr-d-block">Name <span class="fscr-asterisk--required">*</span></span>
          <input name="name" type="text" v-validate="'required'" v-model="name" :data-vv-scope="vvScope" class="fscr-input">
          <span class="fscr-d-block fscr-input-error">{{ validator.first('name', vvScope) }}</span>
        </label>
      </div>

      <div class="fscr-input-wrap fscr-input-wrap--half">
        <label class="fscr-input-label">
          <span class="fscr-d-block">Email <span class="fscr-asterisk--required">*</span></span>
          <input name="email" type="email" v-validate="'required|email'" v-model="email" :data-vv-scope="vvScope" class="fscr-input">
          <span class="fscr-d-block fscr-input-error">{{ validator.first('email', vvScope) }}</span>
        </label>    
      </div>

      <div class="fscr-input-wrap fscr-input-wrap--half">
        <label class="fscr-input-label">
          <span class="fscr-d-block">Phone <span class="fscr-asterisk--required">*</span></span>
          <input name="phone" type="tel" v-validate="'required|length:17'" v-model="phone" v-mask="['+1 (###) ###-####']" :data-vv-scope="vvScope" class="fscr-input">
          <span class="fscr-d-block fscr-input-error">{{ validator.first('phone', vvScope) }}</span>
        </label>    
      </div>

    </div>

  </div>
</template>

<script>

  import GlobalState from '../GlobalState';

  export default {

    props: ['vvScope', 'getStudent'],

    data() {
      return {
        globalState: GlobalState,
        id: null, 
        name: null,
        email: null,
        phone: null,
        students: [],
        serverResponse: {},
        studentsEmptyError: null
      }
    }, 

    mounted() {
      this.id = Number(this._uid);
    },

    beforeDestroy() {
      this.delete();
    },

    methods: {

      /**
       * Validate the parent input fields.
       *
       * @param none
       * @return Array of Promises
       */
      validate() {

        var promises  = [],
            validate  = null,
            validated = null;

        var promise = new Promise((resolve, reject) => { 
          if(this.students.length > 0) {
            this.studentsEmptyError = null;
            return resolve(true);
          }
          else {
            this.studentsEmptyError = "Please choose at least one student.";
            return false;
          }
        })
        promises.push(promise);
        promises.push(this.$validator.validate(this.vvScope + '.name'));
        promises.push(this.$validator.validate(this.vvScope + '.email'));
        promises.push(this.$validator.validate(this.vvScope + '.phone'));
        return promises;
      },

      /**
       * Store this parent in the database.
       *
       * @param none
       * @return Promise
       */
      store() {

        var request = {};
        request.name = this.name;
        request.email = this.email;
        request.phone_number = this.phone;
        request.form_entry_id = this.globalState.serverResponse.form.id;

        return new Promise((resolve, reject) => {
          this.$http.post(this.API_BASE_URL + '/guardians/create', request)
            .then((response) => {
              this.serverResponse = response.data.guardian;
              resolve(this.serverResponse);
            })
            .catch((error) => {
              reject(error);
            })
        });

      },

      /**
       * Update this parent in the database.
       *
       * @param none
       * @return Promise
       */
      update() {

        var request = {};
        request.name = this.name;
        request.email = this.email;
        request.phone_number = this.phone;
        request.form_entry_id = this.globalState.serverResponse.form.id;

        return new Promise((resolve, reject) => {
          this.$http.put(this.API_BASE_URL + '/guardians/update/' + this.serverResponse.id, request)
            .then((response) => {
              this.serverResponse = response.data.guardian;
              resolve(this.serverResponse);
            })
            .catch((error) => {
              reject(error);
            })
        });

      },

      /**
       * Delete this parent from the database.
       *
       * @param none
       * @return Promise
       */
      delete() {
        var student;
        this.students.forEach(sId => {
          student = this.getStudent(sId);
          if(student) {
            student.update();
          }
        });
        if(this.serverResponse.hasOwnProperty('id')) {
          return new Promise((resolve, reject) => {
            this.$http.delete(this.API_BASE_URL + '/guardians/delete/' + this.serverResponse.id)
              .then((response) => {
                this.serverResponse = {};
                resolve(response);
              })
              .catch((error) => {
                reject(error);
              })
          });
        }
      },

      /**
       * Handle parent/student relationships on the frontend. 
       * 
       * The following function keeps track of which students belong to 
       * which parents based on the student checkboxes within the parent component. 
       * 
       * No student can be selected more than once. 
       *
       * @param Event e
       * @return void
       */
      handleParentStudentRelationship(e) {

        var allParents        = this.$parent.$refs.parents,
            clickedParent     = this,
            clickedParentId   = this.id,
            clickedStudentId  = Number(e.target.getAttribute('data-student-id')),
            index             = null,
            scParentId        = null,
            scStudentId       = null,
            checkboxParentId  = null,
            checkboxStudentId = null;

        // if a box was checked, add student to parent component
        // loop through all parent components and remove student if
        // it's checked on another parent component
        if(e.target.checked) {

          this.studentsEmptyError = null;

          // add student to this parent
          this.students.push({id: clickedStudentId});

          // add parent to this student
          var student = this.getStudent(clickedStudentId);
          student.parent = this.id;

          // loop through parent components
          // and remove student from other parent components
          allParents.forEach((p, i) => {
            checkboxParentId  = p.id,

            // loop through checkboxes on parent component
            p.$refs.studentCheckboxes.forEach(sc => {
              checkboxStudentId = Number(sc.getAttribute('data-student-id'));

              // if the parent is not the parent that was clicked
              // and the student has the same ID of the student that was clicked
              // uncheck student and update this parent's array of students
              if(checkboxParentId != clickedParentId && checkboxStudentId == clickedStudentId) {
                sc.checked = false;
                index = null;
                p.students.forEach((s, j) => {
                  if(s.id == clickedStudentId) {
                    index = j;
                  }
                });
                if(index !== null) {
                  p.students.splice(index, 1);
                }
              }
            });
          });
        }

        else {

          index = null;
          var student = this.getStudent(clickedStudentId);
          student.parent = null;
          this.students.forEach((s, i) => {
            if(s.id == clickedStudentId) {
              index = i;
            }
          });
          if(index !== null) {
            this.students.splice(index, 1);
          }

          if(this.students.length < 1) {
            this.studentsEmptyError = "Please at least one student.";
          }

        }
        
      },

      /**
       * Determines if parent fields are different than what they are on the server.
       *
       * @param none
       * @return Boolean
       */
      isDirty() {

        if(Object.keys(this.serverResponse).length !== 0 && this.serverResponse.hasOwnProperty('id')) {

          var isDirty = 
            this.name  != this.serverResponse.name || 
            this.email != this.serverResponse.email_address || 
            this.phone != this.serverResponse.phone_number;
          console.log('parent ' + this.id + " is dirty: ", isDirty);
          return isDirty;
        }

        return true;

      }

    }

  }

</script>