<template>
  <div>

    <fieldset class="fieldset">
      Parent for which students?
      <div v-for="student in $store.state.students.students" :key="student.id">
        <label>
          <input type="checkbox" :value="student" v-model="students">
          <span>{{ student.name }}</span>
        </label>
      </div>
    </fieldset>
    
    <div class="input-wrap">
      <label>
        <span class="d-block">Name <span class="asterisk--required">*</span></span>
        <input name="name" type="text" v-validate="'required'" v-model="name">
        <span class="d-block">{{ validator.first('name') }}</span>
      </label>
    </div>

    <div class="input-wrap">
      <label>
        <span class="d-block">Email <span class="asterisk--required">*</span></span>
        <input name="email" type="email" v-validate="'required|email'" v-model="email">
        <span class="d-block">{{ validator.first('email') }}</span>
      </label>    
    </div>

    <div class="input-wrap">
      <label>
        <span class="d-block">Phone <span class="asterisk--required">*</span></span>
        <input name="phone" type="tel" v-validate="'required'" v-model="phone">
        <span class="d-block">{{ validator.first('phone') }}</span>
      </label>    
    </div>

  </div>
</template>

<script>

  export default {

    props: ['store'],

    data() {
      return {
        id: null
      }
    }, 

    mounted() {
      this.id = this._uid
    },

    computed: {
      name: {
        get() {
          var parent;
          this.$store.state.parents.parents.forEach(p => {
            if(p.id == this.id) {
              parent = p;
            }
          });
          if(parent) {
            return parent.name;
          }
          return null;
        },
        set(value) {
          this.$store.commit('parents/updateParent', {
            id: this.id,
            name: value
          });
        }
      },
      email: {
        get() {
          var parent;
          this.$store.state.parents.parents.forEach(p => {
            if(p.id == this.id) {
              parent = p;
            }
          });
          if(parent) {
            return parent.email;
          }
          return null;
        },
        set(value) {
          this.$store.commit('parents/updateParent', {
            id: this.id,
            email: value
          });
        }
      },
      phone: {
        get() {
          var parent;
          this.$store.state.parents.parents.forEach(p => {
            if(p.id == this.id) {
              parent = p;
            }
          });
          if(parent) {
            return parent.phone;
          }
          return null;
        },
        set(value) {
          this.$store.commit('parents/updateParent', {
            id: this.id,
            phone: value
          });
        }
      },
      students: {
        get() {
          var students;
          this.$store.state.parents.parents.forEach(parent => {
            if(parent.id = this.id) {
              students = parent.students;
            }
          });
          return students;
        },
        set(value) {
          this.$store.commit('parents/addStudent', value);
        }
      }
    },

    methods: {
      handleParentStudentRelationship($event) {
        var parent = this,
            selectedStudent;
        this.$store.state.students.students.forEach(student => {
          if(student.id === Number($event.target.value)) {
            selectedStudent = student;
          }
        });
        if(selectedStudent) {
          if($event.target.checked) {
            this.$store.commit('parents/addStudent', {
              student: selectedStudent,
              parent: parent
            });
            this.$store.commit('students/addParent', {
              student: selectedStudent,
              parent: parent
            });
          }
          else {
            this.$store.commit('parents/removeStudent', {
              student: selectedStudent,
              parent: parent
            });
            this.$store.commit('students/removeParent', {
              student: selectedStudent,
              parent: parent
            });
          }
        }
      }
    }

  }

</script>