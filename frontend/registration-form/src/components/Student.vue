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

  import Datepicker from 'vuejs-datepicker';

  export default {

    props: ['vvScope'],

    components: {
      Datepicker
    },

    data() {
      return {
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

    methods: {

      validate() {
        var promises  = [],
            validate  = null,
            validated = null;
        promises.push(this.$validator.validate(this.vvScope + '.name'));
        promises.push(this.$validator.validate(this.vvScope + '.dob'));
        return promises;
      },

      store(obj) {
        var request = {};
        request.student_name = this.name;
        request.student_date_of_birth = this.dob;
        request.guardian_id = obj.guardian_id;
        request.form_entry_id = obj.form_entry_id;
        this.$http.post(this.API_BASE_URL + '/students/create', request)
          .then(response => {
            // update formEntry id so we know we have one
            this.serverResponse = response.data.student;
            this.$emit('student:create');
          })
          .catch(error => {
            this.serverResponse = JSON.stringify(error.data);
            this.$emit('student:create:error');
          });
      },

      isDirty() {

        if(Object.keys(this.serverResponse).length !== 0 && this.serverResponse.hasOwnProperty('id')) {

          var isDirty = 
            this.name != this.serverResponse.name || 
            new Date(this.dob).getTime() !== new Date(this.serverResponse.student_date_of_birth).getTime()
          console.log('student ' + this.id + " is dirty: ", isDirty);
          return isDirty;
        }

        return true;

      }

    }

  }

</script>