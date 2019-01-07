<template>
  <div>
    
    <div class="input-wrap">
      <label>
        <span class="d-block">Name <span class="asterisk--required">*</span></span>
        <input :name="'student_name'" v-validate="'required'" v-model="name">
        <span class="d-block">{{ this.validator.first('student_name') }}</span>
      </label>
    </div>

    <div class="input-wrap">
      <label :for="'student_' + id + '_dob'"><span>Date of Birth <span class="asterisk--required">*</span></span></label>
      <Datepicker :name="'student_name'" :id="'student_' + id + '_dob'" v-model="dob" v-validate="'required'"/>
    </div>

  </div>
</template>

<script>

  import Datepicker from 'vuejs-datepicker';
  import Student from '../store/models/StudentModel';

  export default {

    components: {
      Datepicker
    },

    data() {
      return {
        id: null,
      }
    }, 

    mounted() {
      
      // make ID easier to access
      this.id = this._uid;
      
      // add this parent to the store
      var temp = [],
          student = new Student(),
          students = this.$store.getters['students/getStudents'];

      students.forEach(p => {
        temp.push(p);
      });

      student.id = this.id;
      temp.push(student);

      this.$store.commit('students/updateStudents', temp);

    },

    beforeDestroy() {

      var temp = [],
      students = this.$store.getters['students/getStudents'];

      students.forEach(p => {
        if(p.id !== this.id) {
          temp.push(p);
        }
      });

      this.$store.commit('students/updateStudents', temp);

    },

    computed: {
      name: {
        get() {
          var student,
              students = this.$store.getters['students/getStudents'];
          students.forEach(s => {
            if(s.id == this.id) {
              student = s;
            }
          });
          if(student) {
            return student.name;
          }
          return null;
        },
        set(value) {
          this.$store.commit('students/updateStudent', {
            id: this.id,
            name: value
          })
        }
      },
      dob: {
        get() {
          var student,
              students = this.$store.getters['students/getStudents'];
          students.forEach(s => {
            if(s.id == this.id) {
              student = s;
            }
          });
          if(student) {
            return student.dob;
          }
          return null;
        },
        set(value) {
          this.$store.commit('students/updateStudent', {
            id: this.id,
            dob: value
          })
        }
      }
    }

  }

</script>