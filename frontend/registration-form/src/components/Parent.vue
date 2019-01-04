<template>
  <div>

    <fieldset class="fieldset">
      Parent for which students?
      <div v-for="student in $store.getters['students/getStudents']" :key="student.id">
        <label>
          <input type="checkbox" :value="student" @change="handleParentStudentRelationship">
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

  import { mapState } from 'vuex'

  export default {

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
          var parent,
              parents = this.$store.getters['parents/getParents'];
          parents.forEach(p => {
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
          var parent,
              parents = this.$store.getters['parents/getParents'];
          parents.forEach(p => {
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
          var parent,
              parents = this.$store.getters['parents/getParents'];
          parents.forEach(p => {
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
      ...mapState({
        students: state => state.students
      })
    },

    methods: {
      handleParentStudentRelationship(e) {
        var parent = this,
            parents = this.$store.getters['parents/getParents'];
        parents.forEach(p => {
          if(p.id == parent.id) {
            this.$store.commit('parents/addStudent', {
              parentId: this.id,
              studentId: e.target.value
            });
          }
        });
      }
    }

  }

</script>