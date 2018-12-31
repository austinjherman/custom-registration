<template>

  <div id="app" class="fscr">
    
    <Guest/>

    <Students/>
    <Parents/>

    <hr>

    <div class="fscrForm__btn-container">
      <button type="button" @click="goToPage(1)" class="fscr__button fscr__button--primary">Back</button>
      <button type="button" @click="handleSecondPageSubmission()" class="fscr__button fscr__button--primary">Next</button>
    </div>

  </div>

</template>

<script>
import  Guest from './components/Guest.vue'
import  Students from './components/Students.vue'
import  Parents from './components/Parents.vue'

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
    Parents
  },
  methods: {
    goToPage(pageNumber) {
      //
    },
    handleSecondPageSubmission() {
      console.log('students: ', this.$store.state.students.students);
      console.log('parents: ', this.$store.state.students.parents);
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
