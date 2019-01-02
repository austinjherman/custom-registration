<template>
  <div>

    <fieldset class="fieldset">
      Parent for which students?
      <div v-for="student in $store.state.students.students">
        <label>
          <input type="checkbox" :value="student.id" @click="handleParentStudentRelationship($event)">
          <span>{{ student.name }}</span>
        </label>
      </div>
    </fieldset>
    
    <div class="input-wrap">
      <label>
        <span class="d-block">Name <span class="asterisk--required">*</span></span>
        <input :name="'parent_' + id + '_name'" v-validate="'required'" v-model="name" @input="updateParent($event)">
      </label>
    </div>

    <div class="input-wrap">
      <label>
        <span class="d-block">Email <span class="asterisk--required">*</span></span>
        <input type="email" :name="'parent_' + id + '_email'" v-model="email" @input="updateParent($event)">
      </label>    
    </div>

    <div class="input-wrap">
      <label>
        <span class="d-block">Phone <span class="asterisk--required">*</span></span>
        <input type="tel" :name="'parent_' + id + '_phone'" v-model="phone" @input="updateParent($event)">
      </label>    
    </div>

  </div>
</template>

<script>

  export default {

    data () {
      return {
        id: "",
        name: "",
        email: "",
        phone: "",
        students: []
      }
    }, 

    mounted () {
      this.id = this._uid
    },

    methods: {
      updateParent() {
        this.$store.commit('parents/updateParent', this);
      },
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