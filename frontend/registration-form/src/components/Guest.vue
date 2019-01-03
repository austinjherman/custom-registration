<template>
  <div>

    <fieldset class="fieldset">

      <legend>Your Contact Information</legend>

      <div class="input-wrap">
      <label>
        <span class="d-block">First Name <span class="asterisk--required">*</span></span>
        <input name="firstName" type="text" v-model="firstName" v-validate="'required'">
        <span class="d-block">{{ validator.first('firstName') }}</span>
      </label>
      </div>

      <div class="input-wrap">
      <label>
        <span class="d-block">Last Name <span class="asterisk--required">*</span></span>
        <input name="lastName" type="text" v-model="lastName" v-validate="'required'">
        <span class="d-block">{{ validator.first('lastName') }}</span>
      </label>
      </div>

      <div class="input-wrap">
      <label>
        <span class="d-block">Email <span class="asterisk--required">*</span></span>
        <input name="email" type="email" v-model="email" v-validate="'required|email'">
        <span class="d-block">{{ validator.first('email') }}</span>
      </label>
      </div>

      <div class="input-wrap">
      <label>
        <span class="d-block">Phone <span class="asterisk--required">*</span></span>
        <input name="phone" type="tel" v-model="phone" v-validate="'required'">
        <span class="d-block">{{ validator.first('phone') }}</span>
      </label>
      </div>

      <div class="input-wrap">
      <label>
        <span class="d-block">ZIP Code <span class="asterisk--required">*</span></span>
        <input name="zip" type="tel" v-model="zip" v-validate="'required'">
        <span class="d-block">{{ validator.first('zip') }}</span>
      </label>
      </div>

      <fieldset class="fieldset">

        <legend>Do you have access to a pool? <span class="asterisk--required">*</span></legend>

        <div class="input-wrap">
        <label>
          <span class="d-block">Yes</span>
          <input name="poolAccess" type="radio" v-model="poolAccess" value="true" v-validate="'required'">
        </label>
        </div>

        <div class="input-wrap">
        <label>
          <span class="d-block">No</span>
          <input name="poolAccess" type="radio" v-model="poolAccess" value="false" v-validate="'required'">
        </label>
        </div>

        <span class="d-block">{{ validator.first('poolAccess') }}</span>

      </fieldset>

    </fieldset>

  </div>
</template>

<script>

  import Vue from 'vue';
  import Parent from './Parent';

  export default {

    mounted () {
      this.id = this._uid
    },

    computed: {
      firstName: {
        get() {
          return this.$store.state.guest.firstName;
        },
        set(value) {
          this.$store.commit('guest/updateGuest', {
            firstName: value
          });
        }
      },
      lastName: {
        get() {
          return this.$store.state.guest.lastName;
        },
        set(value) {
          this.$store.commit('guest/updateGuest', {
            lastName: value
          });
        }
      },
      email: {
        get() {
          return this.$store.state.guest.email;
        },
        set(value) {
          this.$store.commit('guest/updateGuest', {
            email: value
          });
        }
      },
      phone: {
        get() {
          return this.$store.state.guest.phone;
        },
        set(value) {
          this.$store.commit('guest/updateGuest', {
            phone: value
          });
        }
      },
      zip: {
        get() {
          return this.$store.state.guest.zip;
        },
        set(value) {
          this.$store.commit('guest/updateGuest', {
            zip: value
          });
        }
      },
      poolAccess: {
        get() {
          return this.$store.state.guest.poolAccess;
        },
        set(value) {
          this.$store.commit('guest/updateGuest', {
            poolAccess: value
          });
        }
      },
      created: {
        get() {
          return this.$store.state.guest.created;
        },
        set(value) {
          this.$store.commit('guest/updateGuest', {
            created: value
          });
        }
      }
    },

    methods: {

      validate: async function() {

        // validate necessary form fields
        var validate = Promise.all([
          this.$validator.validate('firstName'),
          this.$validator.validate('lastName'),
          this.$validator.validate('email'),
          this.$validator.validate('phone'),
          this.$validator.validate('zip'),
          this.$validator.validate('poolAccess'),
        ]);

        // await results of promise and ensure they are all true
        var validated = await validate.then(result => validated = result.every(isValid => isValid));

        if(validated) {
          return true;
        }

        return false;

      }, 

      makeParent() {
        var guest = this,
            ParentComponent = Vue.extend(Parent),
            parent = new ParentComponent();
        parent.name = this.$store.state.guest.firstName + " " + this.$store.state.guest.lastName;
        parent.email = this.$store.state.guest.email;
        parent.phone = this.$store.state.guest.phone;
        //this.$store.commit('parents/createParent', parent);
      }

    }

  }
</script>