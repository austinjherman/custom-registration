<template>
  <div class="fscr-student-component">

    <div class="fscr-d-flex wrap">
    
      <div class="fscr-input-wrap fscr-input-wrap--half">
        <label class="fscr-input-label">
          <span class="fscr-d-block fscr-input-label-text">Name <span class="fscr-asterisk--required">*</span></span>
          <input :name="'name'" v-validate="'required'" v-model="name" data-vv-as="Student Name" :data-vv-scope="vvScope" class="fscr-input" />
          <span class="fscr-d-block fscr-input-error">{{ validator.first('name', vvScope) }}</span>
        </label>
      </div>

      <div class="fscr-input-wrap fscr-input-wrap--half">
        <label :for="'student_' + vvScope + '_dob'" class="fscr-input-label"><span class="fscr-d-block fscr-input-label-text">Date of Birth <span class="fscr-asterisk--required">*</span></span></label>
        <Datepicker :name="'dob'" :id="vvScope + '_dob'" v-model="dob" v-validate="'required'" data-vv-as="Student Date of Birth" :data-vv-scope="vvScope" input-class="fscr-input" />
        <span class="fscr-d-block fscr-input-error">{{ validator.first('dob', vvScope) }}</span>
      </div>

    </div>

  </div>
</template>

<script>

  import GlobalState from '../GlobalState'
  import Datepicker from 'vuejs-datepicker'

  export default {

    props: ['vvScope', 'getParent'],

    components: {
      Datepicker
    },

    data() {
      return {
        globalState: GlobalState,
        id: null,
        name: null,
        dob: null,
        parent: null,
        serverResponse: {}
      }
    }, 

    mounted() {
      this.id = this._uid;
    },

    beforeDestroy() {
      this.delete();
    },

    methods: {

      /**
       * Validate the student input fields.
       *
       * @param none
       * @return Array of Promises
       */
      validate() {
        var promises  = [],
            validate  = null,
            validated = null;
        promises.push(this.$validator.validate(this.vvScope + '.name'));
        promises.push(this.$validator.validate(this.vvScope + '.dob'));
        return promises;
      },

      /**
       * Store this student in the database.
       *
       * @param none
       * @return Promise
       */
      store() {

        var request = {};
        request.student_name = this.name;
        request.student_date_of_birth = this.dob.toISOString();
        request.student_date_of_birth = request.student_date_of_birth.split('T')[0];
        request.form_entry_id = this.globalState.serverResponse.form.id;
        request.guardian_id = null;

        var guardian = this.getParent(this.parent);
        if(guardian && guardian.serverResponse.hasOwnProperty('parent')) {
          request.guardian_id = guardian.serverResponse.parent.id;
        }
        else if (guardian && guardian.serverResponse.hasOwnProperty('id')) {
          request.guardian_id = guardian.serverResponse.id;
        }

        return new Promise((resolve, reject) => {
          this.$http.post(this.API_BASE_URL + '/students/create', request)
            .then(response => {
              this.serverResponse = response.data.student;
              resolve(this.serverResponse);
            })
            .catch(error => {
              this.serverResponse = JSON.stringify(error.data);
              reject(error);
            });
          });
      },

      /**
       * Update this student in the database.
       *
       * @param none
       * @return Promise
       */
      update() {

        var request = {};
        request.name = this.name;
        request.date_of_birth = this.dob;

        var guardian = this.getParent(this.parent);
        if(guardian && guardian.serverResponse.hasOwnProperty('parent')) {
          request.guardian_id = guardian.serverResponse.parent.id;
        }
        else if (guardian && guardian.serverResponse.hasOwnProperty('parent')) {
          request.guardian_id = guardian.serverResponse.id;
        }

        return new Promise((resolve, reject) => {
          this.$http.put(this.API_BASE_URL + '/students/update/' + this.serverResponse.id, request)
            .then(response => {
              this.serverResponse = response.data.student;
              resolve(this.serverResponse);
            })
            .catch(error => {
              this.serverResponse = JSON.stringify(error);
              reject(error);
            });
          });
      },

      /**
       * Delete this student from the database.
       *
       * @param none
       * @return Promise
       */
      delete() {
        return new Promise((resolve, reject) => {
          this.$http.delete(this.API_BASE_URL + '/students/delete/' + this.serverResponse.id)
            .then((response) => {
              this.serverResponse = {};
              resolve(response);
            })
            .catch((error) => {
              reject(error);
            })
        });
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
            this.name != this.serverResponse.name || 
            this.parent != this.serverResponse.guardian_id || 
            this.dob.toISOString().split('T')[0] !== new Date(this.serverResponse.date_of_birth.date).toISOString().split('T')[0];
          console.log('student ' + this.id + " is dirty: ", isDirty);
          return isDirty;
        }

        return true;

      },

      formatDate(date) {
        return new Date(date).toISOString().split('T')[0];
      }

    }

  }

</script>