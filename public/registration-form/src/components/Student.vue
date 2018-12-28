<template>
  <div>
    
    <div class="input-wrap">
      <label>
        <span class="d-block">Name <span class="asterisk--required">*</span></span>
        <input :name="'student_' + id + '_name'" v-validate="'required'">
      </label>
    </div>

    <div class="input-wrap">
      <label :for="'student_' + id + '_dob'"><span>Date of Birth <span class="asterisk--required">*</span></span></label>
      <Datepicker :id="'student_' + id + '_dob'" />
    </div>

  </div>
</template>

<script>

  import Datepicker from 'vuejs-datepicker';

  export default {

    components: {
      Datepicker
    },

    data () {
      return {
        id: "",
        name: "",
        dob: ""
      }
    }, 

    mounted () {
      this.id = this._uid
    },

  }

</script>