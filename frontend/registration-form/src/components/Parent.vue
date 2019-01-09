<template>
  <div>

    <fieldset class="fieldset">
      Parent for which students?
      <div v-for="student in this.$parent.$refs.students" :key="student.id">
        <label>
          <input type="checkbox" @change="handleParentStudentRelationship" :data-student-id="student.id" :data-parent-id="id" ref="studentCheckboxes">
          <span>{{ student.name }}</span>
        </label>
      </div>
    </fieldset>
    
    <div class="input-wrap">
      <label>
        <span class="d-block">Name <span class="asterisk--required">*</span></span>
        <input name="name" type="text" v-validate="'required'" v-model="name" :data-vv-scope="vvScope">
        <span class="d-block">{{ validator.first('name', vvScope) }}</span>
      </label>
    </div>

    <div class="input-wrap">
      <label>
        <span class="d-block">Email <span class="asterisk--required">*</span></span>
        <input name="email" type="email" v-validate="'required|email'" v-model="email" :data-vv-scope="vvScope">
        <span class="d-block">{{ validator.first('email', vvScope) }}</span>
      </label>    
    </div>

    <div class="input-wrap">
      <label>
        <span class="d-block">Phone <span class="asterisk--required">*</span></span>
        <input name="phone" type="tel" v-validate="'required|length:17'" v-model="phone" v-mask="['+1 (###) ###-####']" :data-vv-scope="vvScope">
        <span class="d-block">{{ validator.first('phone', vvScope) }}</span>
      </label>    
    </div>

  </div>
</template>

<script>

  export default {

    props: ['vvScope'],

    data() {
      return {
        id: null, 
        name: null,
        email: null,
        phone: null,
        students: []
      }
    }, 

    /**
     * Push an bare parent object to the store on the
     * mounted event. 
     */
    mounted() {
      this.id = Number(this._uid);
    },

    /**
     * Remove this parent instance from store on the
     * beforeDestroy event.
     */
    beforeDestroy() {
      //
    },

    methods: {
  
      handleParentStudentRelationship(e) {

        var allParents    = this.$parent.$refs.parents,
            clickedParent = this,
            studentId     = Number(e.target.getAttribute('data-student-id')),
            allCheckboxes = [];

        // collect all of the student checkboxes for each parent
        allParents.forEach(p => {
          allCheckboxes = allCheckboxes.concat(p.$refs.studentCheckboxes);
        });

        if(clickedParent) {
          
          // get this ready to rebuild students array
          var temp = [];

          // if the checkbox is checked
          // add student to checkbox parent
          // remove student from all other checkbox parents
          if(e.target.checked) {

            // rebuild current students array for the clicked parent
            clickedParent.students.forEach(s => {
              temp.push(s);
            });
            // add checked student
            temp.push({id: studentId});

            // uncheck this checkbox in other parents
            allCheckboxes.forEach(sc => {
              var checkboxParentId  = Number(sc.getAttribute('data-parent-id')),
                  checkboxStudentId = Number(sc.getAttribute('data-student-id'));

              // uncheck if the parent is not the current parent && student is the same
              if(checkboxParentId != clickedParent.id && checkboxStudentId == studentId) {
                sc.checked = false;

                // now update the students associated with the checkbox parent
                var checkboxParent = null;
                this.$parent.$refs.parents.forEach(p => {
                  if(p.id == checkboxParentId) {
                    checkboxParent = p;
                  }
                });
                if(checkboxParent) {
                  var temp2 = [];
                  checkboxParent.students.forEach(s2 => {
                    if(studentId != checkboxStudentId) {
                      temp2.push(s2);
                    }
                  });
                  console.log('found parent to update: ', checkboxParent);
                  checkboxParent.students = temp2;
                }

              }
            });

          }

          // if unchecked, simply rebuild students array without
          // unchecked student
          else {
            clickedParent.students.forEach(s => {
              if(s.id != studentId) {
                temp.push(s);
              }
            });
          }

          console.log('parent you just edited students: ', temp);
          clickedParent.$set(clickedParent, 'students', temp);

        }


      }

    }

  }

</script>