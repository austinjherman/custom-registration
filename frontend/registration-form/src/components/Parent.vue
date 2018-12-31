<template>
  <div>

    <fieldset class="fieldset">
      Parent for which students?
      <div v-for="student in $store.state.students.students">
        <label>
          <input type="checkbox" :value="student.name">
          <span>{{ student.name }}</span>
        </label>
      </div>
    </fieldset>
    
    <div class="input-wrap">
      <label>
        <span class="d-block">Name <span class="asterisk--required">*</span></span>
        <input :name="'parent_' + id + '_name'" v-validate="'required'" v-model="name">
      </label>
    </div>

    <div class="input-wrap">
      <label>
        <span class="d-block">Email <span class="asterisk--required">*</span></span>
        <input type="email" :name="'parent_' + id + '_email'" v-model="email">
      </label>    
    </div>

    <div class="input-wrap">
      <label>
        <span class="d-block">Phone <span class="asterisk--required">*</span></span>
        <input type="tel" :name="'parent_' + id + '_phone'" v-model="phone">
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
        phone: ""
      }
    }, 

    mounted () {
      this.id = this._uid
    },

  }

</script>