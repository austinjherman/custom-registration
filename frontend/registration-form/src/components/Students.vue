<template>
  <div>

    <div class="input-wrap">
      <label>
        <span class="d-block">How many students are you enrolling? <span class="asterisk--required">*</span></span>                
        <select name="number_students_enrolling" v-model.number="numberOfStudents" v-validate="'required|min_value:1'">
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

    <div v-for="n in numberOfStudents" v-bind:key="n">
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

    data() {
      return {
        id: null
      }
    },

    mounted() {
      this.id = this._uid
    },

    computed: {
      numberOfStudents: {
        get() {
          return this.$store.getters['students/getNumberOfStudents'];
        },
        set(value) {
          this.$store.commit('students/setNumberOfStudents', Number(value));
        },
      }
    },

  }

</script>