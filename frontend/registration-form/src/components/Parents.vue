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
          <select name="number_parents" v-validate="'required|min_value:1'" v-model="numberOfParents">
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

    <div v-if="guestIsOnlyParent == 'false'">
      <div v-for="n in numberOfParents" v-bind:key="n.id">
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
          return this.$store.getters['parents/getCount'];
        },
        set(value) {
          this.$store.commit('parents/changeAmount', Number(value));
        },
      }

    },

    methods: {

      sendToApi() {

        var request,
            requests = [];

        if(this.$store.getters.guestIsOnlyParent == 'false') {
          if(Array.isArray(this.$store.getters['parents/getParents'])) {
            this.$store.getters['parents/getParents'].forEach(parent => {
              request = {};
              request.name = parent.name;
              request.email = parent.email;
              request.phone_number = parent.phone;
              requests.push(request);
            });
          }
        }
        else {
          request = {};
          request.name = this.$store.state.guest.firstName + " " + this.$store.state.guest.lastName;
          request.email = this.$store.state.guest.email;
          request.phone_number = this.$store.state.guest.phone;
          requests.push(request);
        }

        console.log('parents: ', requests)

      }

    },

  }

</script>