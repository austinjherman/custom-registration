<template>
  <div>

    <fieldset class="fieldset">
      Parent for which students?
      <div v-for="student in $store.getters['students/getStudents']" :key="student.id">
        <label>
          <input type="checkbox" @change="handleParentStudentRelationship" :data-student-id="student.id" :data-parent-id="id" ref="studentCheckbox">
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

  import { mapState } from 'vuex'
  import Parent from '../store/models/ParentModel'
  import { getParents, getParent } from '../store/helpers'

  export default {

    props: ['allParents', 'vvScope'],

    data() {
      return {
        id: null
      }
    }, 

    /**
     * Push an bare parent object to the store on the
     * mounted event. 
     */
    mounted() {
      
      // make ID easier to access
      this.id = Number(this._uid);
      
      // add this parent to the store
      var temp    = [],
          parent  = new Parent(),
          parents = getParents()

      parents.forEach(p => {
        temp.push(p);
      });

      parent.id = this.id;
      parent.studentCheckboxes = this.$refs['studentCheckbox'];
      temp.push(parent);

      this.$store.commit('parents/updateParents', temp);
      this.allParents.push(this);

    },

    /**
     * Remove this parent instance from store on the
     * beforeDestroy event.
     */
    beforeDestroy() {

      var temp = [],
      parents = getParents()

      parents.forEach(p => {
        if(p.id !== this.id) {
          temp.push(p);
        }
      });

      this.$store.commit('parents/updateParents', temp);

      this.allParents.forEach((p, i) => {
        if(p.id == this.id) {
          this.allParents.splice(i, 1);
        }
      });

    },

    computed: {

      name: {
        get() {
          var parent = getParent(this.id);
          if(parent) {
            return parent.name;
          }
          return null;
        },
        set(value) {
          this.$store.commit('parents/updateParent', {
            parentId: this.id,
            name: value
          });
        }
      },

      email: {
        get() {
          var parent = getParent(this.id);
          if(parent) {
            return parent.email;
          }
          return null;
        },
        set(value) {
          this.$store.commit('parents/updateParent', {
            parentId: this.id,
            email: value
          });
        }
      },

      phone: {
        get() {
          var parent = getParent(this.id);
          if(parent) {
            return parent.phone;
          }
          return null;
        },
        set(value) {
          this.$store.commit('parents/updateParent', {
            parentId: this.id,
            phone: value
          });
        }
      },

      ...mapState({
        students: state => state.students
      })
      
    },

    methods: {
  
      handleParentStudentRelationship(e) {

        var allParents    = getParents(),
            clickedParent = getParent(this.id),
            studentId     = Number(e.target.getAttribute('data-student-id')),
            allCheckboxes = [];

        // collect all of the student checkboxes for each parent
        allParents.forEach(p => {
          allCheckboxes = allCheckboxes.concat(p.studentCheckboxes);
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
                this.$set(sc, 'checked', false);

                // now update the students associated with the checkbox parent in store
                var checkboxParent = getParent(checkboxParentId);
                if(checkboxParent) {
                  var temp2 = [];
                  checkboxParent.students.forEach(s2 => {
                    if(studentId != checkboxStudentId) {
                      temp2.push(s2);
                    }
                  });
                  this.$store.commit('parents/updateStudents', {
                    parentId: checkboxParentId,
                    studentIds: temp2
                  });
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

          this.$store.commit('parents/updateStudents', {
            parentId: this.id,
            studentIds: temp
          });

        }


      }

    }

  }

</script>