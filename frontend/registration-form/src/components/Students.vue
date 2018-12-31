<template>
  <div>

    <div class="input-wrap">
      <label>
        <span class="d-block">How many students are you enrolling? <span class="asterisk--required">*</span></span>                
        <select name="number_students_enrolling" v-model.number="number_students_enrolling" v-validate="'required|min_value:1'" @change="updateNumberOfStudents">
          <option selected="selected" value="0">Please Select</option>
          <option value="1">1</option>
          <option value="2">2</option>
          <option value="3">3</option>
          <option value="4">4</option>
          <option value="5">5</option>
          <option value="6">6</option>
          <option value="7">7</option>
          <option value="8">8</option>
        </select>
        <span class="d-block">{{ this.validator.first('number_students_enrolling') }}</span>
      </label>
    </div>

    <div v-for="n in number_students_enrolling" v-bind:key="n.id">
      <Student/>
    </div>

  </div>
</template>

<script>

  import Student from './Student.vue';

  export default {

    components: {
      Student
    },

    data () {
      return {
        number_students_enrolling: 0,
        error: ""
      }
    },

    mounted () {
      this.id = this._uid
    },

    methods: {
      updateNumberOfStudents() {
        this.$store.commit('students/updateNumberOfStudents', this.number_students_enrolling);
      }
    }

  }

</script>