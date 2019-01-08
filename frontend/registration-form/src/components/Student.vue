<template>
  <div>
    
    <div class="input-wrap">
      <label>
        <span class="d-block">Name <span class="asterisk--required">*</span></span>
        <input :name="'name'" v-validate="'required'" v-model="name" data-vv-as="Student Name" :data-vv-scope="vvScope">
        <span class="d-block">{{ validator.first('name', vvScope) }}</span>
      </label>
    </div>

    <div class="input-wrap">
      <label :for="'student_' + vvScope + '_dob'"><span>Date of Birth <span class="asterisk--required">*</span></span></label>
      <Datepicker :name="'dob'" :id="vvScope + '_dob'" v-model="dob" v-validate="'required'" data-vv-as="Student Date of Birth" :data-vv-scope="vvScope"/>
      <span class="d-block">{{ validator.first('dob', vvScope) }}</span>
    </div>

  </div>
</template>

<script>

  import Datepicker from 'vuejs-datepicker';
  import Student from '../store/models/StudentModel';
  import { getStudent, getStudents } from '../store/helpers';

  export default {

    props: ['allStudents', 'vvScope'],

    components: {
      Datepicker
    },

    data() {
      return {
        id: null
      }
    }, 

    mounted() {
      
      // make ID easier to access
      this.id = this._uid;
      
      // add this parent to the store
      var temp = [],
          student = new Student(),
          students = getStudents();

      students.forEach(p => {
        temp.push(p);
      });

      student.id = this.id;
      temp.push(student);

      this.$store.commit('students/updateStudents', temp);
      this.allStudents.push(this);

    },

    beforeDestroy() {

      var temp = [],
          students = getStudents();

      students.forEach(p => {
        if(p.id !== this.id) {
          temp.push(p);
        }
      });

      this.$store.commit('students/updateStudents', temp);

      this.allStudents.forEach((s, i) => {
        if(s.id == this.id) {
          this.allStudents.splice(i, 1);
        }
      });

    },

    computed: {
      name: {
        get() {
          var student = getStudent(this.id);
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
          var student = getStudent(this.id);
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
    },
  }

</script>