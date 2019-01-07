<template>
  <div>

    <fieldset class="fieldset">
      Parent for which students?
      <div v-for="student in $store.getters['students/getStudents']" :key="student.id">
        <label>
          <input type="checkbox" :value="Number(student.id)" @change="handleParentStudentRelationship">
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
  import Parent from '../store/models/ParentModel'

  export default {

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
          parent = new Parent(),
          parents = this.$store.getters['parents/getParents'];

      parents.forEach(p => {
        temp.push(p);
      });

      parent.id = this.id;
      temp.push(parent);

      this.$store.commit('parents/updateParents', temp);

    },

    beforeDestroy() {

      var temp = [],
      parents = this.$store.getters['parents/getParents'];

      parents.forEach(p => {
        if(p.id !== this.id) {
          temp.push(p);
        }
      });

      this.$store.commit('parents/updateParents', temp);

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
  
        var action = 'remove',
            parent = this;

        if(e.target.checked) {
          action = 'add';
        }

        this.$store.commit('parents/updateParent', {
          id: parent.id,
          studentId: e.target.value,
          action: action
        });

      }

    }

  }

</script>