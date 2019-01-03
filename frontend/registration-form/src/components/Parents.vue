<template>
  <div>

    <div class="input-wrap">
      <fieldset class="fieldset">
        <legend>Are you the parent/guardian of all the students? <span class="asterisk--required">*</span></legend>
        <label>
          <span class="d-block">Yes</span>
          <input type="radio" value="true" v-model="this.$store.state.guestIsOnlyParent" @input="handleParentGuardianRadio($event)">
        </label>
        <label>
          <span class="d-block">No</span>
          <input type="radio" value="false" v-model="this.$store.state.guestIsOnlyParent" @input="handleParentGuardianRadio($event)">
        </label>
      </fieldset>
    </div>

    <div v-if="this.$store.state.guestIsOnlyParent == false">
      <div class="input-wrap">
        <label>
          <span class="d-block">How many parents are you signing up? <span class="asterisk--required">*</span></span>                
          <select name="number_parents" v-validate="'required|min_value:1'" @change="changeNumberOfParents($event)">
            <option selected="selected" value="0">Please Select</option>
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            <option value="5">5</option>
            <option value="6">6</option>
            <option value="7">7</option>
            <option value="8">8</option>
          </select>
        </label>
      </div>
    </div>

    <div v-if="this.$store.state.guestIsOnlyParent == false">
      <div v-for="n in this.$store.state.parents.parents" v-bind:key="n.id">
        <Parent/>
      </div>
    </div>

  </div>
</template>

<script>

  import Parent from './Parent.vue';

  export default {

    components: {
      Parent
    },

    mounted() {
      this.id = this._uid
    },

    methods: {

      handleParentGuardianRadio($event) {
        if($event.target.value == 'true') {
          this.$store.commit('setGuestIsOnlyParent', true);
          this.changeNumberOfParents(this.$store.state.parents.length);
        }
        else {
          this.$store.commit('setGuestIsOnlyParent', false);
          this.changeNumberOfParents(this.$store.state.parents.length);
        }
      },

      changeNumberOfParents($event) {
        this.$store.commit('parents/changeAmount', Number($event.target.value));
      }

    },

  }

</script>