<template>
  <div>
    
    <div class="input-wrap">
      <label>
        <span class="d-block">Name <span class="asterisk--required">*</span></span>
        <input :name="'student_name'" v-validate="'required'" v-model="name" @input="updateStudent($event)">
        <span class="d-block">{{ this.validator.first('student_name') }}</span>
        <span class="d-block">student id: {{ this.id }}</span>
      </label>
    </div>

    <div class="input-wrap">
      <label :for="'student_' + id + '_dob'"><span>Date of Birth <span class="asterisk--required">*</span></span></label>
      <Datepicker :name="'student_name'" :id="'student_' + id + '_dob'" v-model="dob"  @input="updateStudent($event)" v-validate="'required'" data-vv-validate-on="'selected|opened|closed'"/>
    </div>

  </div>
</template>

<script>

  import Datepicker from 'vuejs-datepicker';

  export default {

    components: {
      Datepicker
    },

    data() {
      return {
        id: "",
        name: "",
        dob: "",
        save: true
      }
    }, 

    mounted() {
      this.id = this._uid
    },

    methods: {
      updateStudent($event) {
        this.$store.commit('students/updateStudent', this);
      },
      test() {
        console.log('datepicker blur');
      }
    }

  }

</script>