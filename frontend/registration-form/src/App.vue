<template>

  <div id="app" class="fscr">
    
    <!-- Page 1 -->
    <Guest ref="guest"/>
    <hr>
    <div class="fscrForm__btn-container">
      <button type="button" @click="handleFirstPageSubmission()" class="fscr__button fscr__button--primary">Next</button>
    </div>

    <!-- Page 2 -->
    <Students ref="students"/>
    <Parents ref="parents"/>
    <hr>
    <div class="fscrForm__btn-container">
      <button type="button" @click="goToPage(1)" class="fscr__button fscr__button--primary">Back</button>
      <button type="button" @click="handleSecondPageSubmission()" class="fscr__button fscr__button--primary">Next</button>
    </div>

  </div>

</template>

<script>
import  Guest from './components/Guest.vue'
import  Parent from './components/Parent.vue'
import  Parents from './components/Parents.vue'
import  Students from './components/Students.vue'

export default {
  
  name: 'app',
  
  data () {
    return {
      msg: 'Welcome to Your Vue.js App'
    }
  },

  components: {
    Guest,
    Students,
    Parents,
    Parent
  },

  methods: {

    goToPage(pageNumber) {
      //
    },

    async handleFirstPageSubmission() {
      var validated = await this.$refs.guest.validate();
      if(validated) {
        this.$refs.guest.sendToApi();
        return true;
      }
      return false;
    },

    handleSecondPageSubmission() {
      this.$refs.parents.sendToApi();
      //console.log('students: ', this.$store.state.students.students);
      //console.log('parents: ', this.$store.state.parents.parents);
    }

  }
}
</script>

<style lang="scss">

  .fscr {

    .d-block {
      display: block;
    }

    .input-wrap {
      margin: 15px 0;
    }

    .fieldset {
      border: none;
      margin: 0;
      padding: 0;
    }

  }
  
</style>
