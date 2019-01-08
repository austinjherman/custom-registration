<template>
  <div>

    <div class="input-wrap">
      <fieldset class="fieldset">
        <legend>Are you the parent/guardian of all the students? <span class="asterisk--required">*</span></legend>
        <label>
          <span class="d-block">Yes</span>
          <input type="radio" value="true" v-model="guestIsOnlyParent">
        </label>
        <label>
          <span class="d-block">No</span>
          <input type="radio" value="false" v-model="guestIsOnlyParent">
        </label>
      </fieldset>
    </div>

    <div v-if="guestIsOnlyParent == 'false'">
      <div class="input-wrap">
        <label>
          <span class="d-block">How many parents are you signing up? <span class="asterisk--required">*</span></span>                
          <select name="numberOfParents" v-validate="'required|min_value:1'" v-model="numberOfParents">
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
          <span class="d-block">{{ validator.first('numberOfParents') }}</span>
        </label>
      </div>
    </div>

    <div v-if="guestIsOnlyParent == 'false'">
      <div v-for="n in numberOfParents" v-bind:key="n">
        <Parent :allParents="allParents" :vvScope="'parent_' + (n-1)" />
      </div>
    </div>

  </div>
</template>

<script>

  import Parent from './Parent.vue';
  import { getParents, getGuest, getStudents } from '../store/helpers';

  export default {

    components: {
      Parent
    },

    data() {
      return {
        id: null,
        allParents: []
      }
    },

    mounted() {
      this.id = this._uid
    },

    computed: {

      guestIsOnlyParent: {
        get() {
          return this.$store.getters.guestIsOnlyParent;
        },
        set(value) {
          this.$store.commit('setGuestIsOnlyParent', value);
        },
      },

      numberOfParents: {
        get() {
          return this.$store.getters['parents/getNumberOfParents'];
        },
        set(value) {
          this.$store.commit('parents/setNumberOfParents', Number(value));
        },
      },

    },

    methods: {

      async validate() {

        var promises = [],
            parents = this.allParents;

        promises.push(this.$validator.validate('numberOfParents'));

        parents.forEach(p => {
          promises.push(this.$validator.validate(p.vvScope + '.name'));
          promises.push(this.$validator.validate(p.vvScope + '.email'));
          promises.push(this.$validator.validate(p.vvScope + '.phone'));
        });

        var validate = Promise.all(promises);

        // await results of promise and ensure they are all true
        var validated = await validate.then(result => validated = result.every(isValid => isValid));

        if(validated) {
          return true;
        }

        return false;

      }

    },

  }

</script>