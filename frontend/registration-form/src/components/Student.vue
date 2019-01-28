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
        schedule: {
          local: {},
          server: {
            error: null,
            success: {}
          }
        },
        serverResponse: {
          error: null,
          success: {}
        },
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
        request.form_entry_id = this.globalState.serverResponse.form.success.id;
        request.guardian_id = null;

        // getParent will always return the guardian if the guestIsOnlyParent boolean
        // is true
        var guardian = this.getParent(this.parent);

        // if guest is only parent, then the guest component instance is returned
        if(guardian && guardian.serverResponse.hasOwnProperty('parent')) {
          request.guardian_id = guardian.serverResponse.parent.success.id;
        }

        // otherwise a parent component instance is returned.
        else if (guardian && guardian.serverResponse.success.hasOwnProperty('id')) {
          request.guardian_id = guardian.serverResponse.success.id;
        }

        return new Promise((resolve, reject) => {
          this.$http.post(this.API_BASE_URL + '/students/create', request)
            .then(response => {
              this.$set(this.serverResponse, 'success', response.data.student);
              resolve(this.serverResponse);
            })
            .catch(error => {
              this.$set(this.serverResponse, 'error', error.data);
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
        request.guardian_id = null;
        request.duration_id = this.globalState.selectedLessonDurationServerId;
        request.lesson_qty = this.globalState.selectedLessonQty;

        var guardian = this.getParent(this.parent),
            parentId = null;
        if(guardian && guardian.serverResponse.hasOwnProperty('parent')) {
          parentId = guardian.serverResponse.parent.success.id;
          this.parent = parentId;
          request.guardian_id = parentId;
        }
        else if (guardian && guardian.serverResponse.success.hasOwnProperty('id')) {
          parentId = guardian.serverResponse.success.id;
          this.parent = parentId;
          request.guardian_id = parentId;
        }

        return new Promise((resolve, reject) => {
          this.$http.put(this.API_BASE_URL + '/students/update/' + this.serverResponse.success.id, request)
            .then(response => {
              this.$set(this.serverResponse, 'success', response.data.student);
              resolve(this.serverResponse);
            })
            .catch(error => {
              this.$set(this.serverResponse, 'data', error.data);
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
          this.$http.delete(this.API_BASE_URL + '/students/delete/' + this.serverResponse.success.id)
            .then((response) => {
              this.$set(this.serverResponse, 'success', {});
              resolve(response);
            })
            .catch((error) => {
              this.$set(this.serverResponse, 'error', error.data);
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
      isDirty(formPage) {

        var isDirty = true;

        if (formPage == 3) {
          if(Object.keys(this.serverResponse.success).length !== 0 && this.serverResponse.success.hasOwnProperty('id')) {
            if (
              this.globalState.selectedLessonDurationServerId != this.serverResponse.duration_id || 
              this.globalState.selectedLessonQty != this.serverResponse.lesson_qty
            ) {
              isDirty = true;
            }
            else {
              isDirty = false;
            }
          }
        }

        return isDirty;

      },

      formatDate(date) {
        return new Date(date).toISOString().split('T')[0];
      }

    }

  }

</script>