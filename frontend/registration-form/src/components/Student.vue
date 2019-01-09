<template>
  <div>
    
    <div class="input-wrap">
      <label>
        <span class="d-block">Name <span class="asterisk--required">*</span></span>
        <input :name="'name'" v-validate="'required'" v-model="name" data-vv-as="Student Name" :data-vv-scope="vvScope" />
        <span class="d-block">{{ validator.first('name', vvScope) }}</span>
      </label>
    </div>

    <div class="input-wrap">
      <label :for="'student_' + vvScope + '_dob'"><span>Date of Birth <span class="asterisk--required">*</span></span></label>
      <Datepicker :name="'dob'" :id="vvScope + '_dob'" v-model="dob" v-validate="'required'" data-vv-as="Student Date of Birth" :data-vv-scope="vvScope" />
      <span class="d-block">{{ validator.first('dob', vvScope) }}</span>
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
        students: []
      }
    }, 

    mounted() {
      this.id = this._uid;
    }

  }

</script>