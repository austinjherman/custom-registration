<template>
  <div class="fscr-parent-component">

    <fieldset class="fscr-fieldset">
      <span class="fscr-d-block fscr-input-label">Parent for which students?</span>
      <div v-for="student in this.$parent.$refs.students" :key="student.id">
        <label>
          <input type="checkbox" @change="handleParentStudentRelationship" :data-student-id="student.id" :data-parent-id="id" ref="studentCheckboxes">
          <span>{{ student.name }}</span>
        </label>
      </div>
    </fieldset>

    <div class="fscr-d-flex wrap">
    
      <div class="fscr-input-wrap fscr-input-wrap--half">
        <label class="fscr-input-label">
          <span class="fscr-d-block">Name <span class="fscr-asterisk--required">*</span></span>
          <input name="name" type="text" v-validate="'required'" v-model="name" :data-vv-scope="vvScope" class="fscr-input">
          <span class="d-block">{{ validator.first('name', vvScope) }}</span>
        </label>
      </div>

      <div class="fscr-input-wrap fscr-input-wrap--half">
        <label class="fscr-input-label">
          <span class="fscr-d-block">Email <span class="fscr-asterisk--required">*</span></span>
          <input name="email" type="email" v-validate="'required|email'" v-model="email" :data-vv-scope="vvScope" class="fscr-input">
          <span class="d-block">{{ validator.first('email', vvScope) }}</span>
        </label>    
      </div>

      <div class="fscr-input-wrap fscr-input-wrap--half">
        <label class="fscr-input-label">
          <span class="fscr-d-block">Phone <span class="fscr-asterisk--required">*</span></span>
          <input name="phone" type="tel" v-validate="'required|length:17'" v-model="phone" v-mask="['+1 (###) ###-####']" :data-vv-scope="vvScope" class="fscr-input">
          <span class="d-block">{{ validator.first('phone', vvScope) }}</span>
        </label>    
      </div>

    </div>

  </div>
</template>

<script>

  export default {

    props: ['vvScope', 'getStudent'],

    data() {
      return {
        id: null, 
        name: null,
        email: null,
        phone: null,
        students: [],
        serverResponse: {}
      }
    }, 

    mounted() {
      this.id = Number(this._uid);
    },

    methods: {

      save() {
        
      },

      handleParentStudentRelationship(e) {

        var allParents        = this.$parent.$refs.parents,
            clickedParent     = this,
            clickedParentId   = this.id,
            clickedStudentId  = Number(e.target.getAttribute('data-student-id')),
            index             = null,
            scParentId        = null,
            scStudentId       = null,
            checkboxParentId  = null,
            checkboxStudentId = null;

        // if a box was checked, add student to parent component
        // loop through all parent components and remove student if
        // it's checked on another parent component
        if(e.target.checked) {

          // add student to this parent
          this.students.push({id: clickedStudentId});

          // add parent to this student
          var student = this.getStudent(clickedStudentId);
          student.parent = this.id;

          // loop through parent components
          // and remove student from other parent components
          allParents.forEach((p, i) => {
            checkboxParentId  = p.id,

            // loop through checkboxes on parent component
            p.$refs.studentCheckboxes.forEach(sc => {
              checkboxStudentId = Number(sc.getAttribute('data-student-id'));

              // if the parent is not the parent that was clicked
              // and the student has the same ID of the student that was clicked
              // uncheck student and update this parent's array of students
              if(checkboxParentId != clickedParentId && checkboxStudentId == clickedStudentId) {
                sc.checked = false;
                index = null;
                p.students.forEach((s, j) => {
                  if(s.id == clickedStudentId) {
                    index = j;
                  }
                });
                if(index !== null) {
                  p.students.splice(index, 1);
                }
              }
            });
          });
        }

        else {
          index = null;
          var student = this.getStudent(clickedStudentId);
          student.parent = null;
          this.students.forEach((s, i) => {
            if(s.id == clickedStudentId) {
              index = i;
            }
          });
          if(index !== null) {
            this.students.splice(index, 1);
          }
        }
        
      }

    }

  }

</script>