import Guest from 

<template>

  <div id="app" class="fscr">
    
    <Guest/>

    <Students/>


  </div>

</template>

<script>
import  Guest from './components/Guest.vue';
import  Students from './components/Students.vue';

export default {
  name: 'app',
  data () {
    return {
      msg: 'Welcome to Your Vue.js App'
    }
  },
  components: {
    Guest,
    Students
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
