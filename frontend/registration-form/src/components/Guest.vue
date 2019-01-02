<template>
  <div>

    <fieldset class="fieldset">

      <legend>Your Contact Information</legend>

      <div class="input-wrap">
      <label>
        <span class="d-block">First Name <span class="asterisk--required">*</span></span>
        <input name="guest_first_name" type="text" v-model="first_name" v-validate="'required'">
        <span class="d-block">{{ validator.first('guest_first_name') }}</span>
      </label>
      </div>

      <div class="input-wrap">
      <label>
        <span class="d-block">Last Name <span class="asterisk--required">*</span></span>
        <input name="guest_last_name" type="text" v-model="last_name" v-validate="'required'">
        <span class="d-block">{{ validator.first('guest_last_name') }}</span>
      </label>
      </div>

      <div class="input-wrap">
      <label>
        <span class="d-block">Email <span class="asterisk--required">*</span></span>
        <input name="guest_email_address" type="email" v-model="email_address" v-validate="'required|email'">
        <span class="d-block">{{ validator.first('guest_email_address') }}</span>
      </label>
      </div>

      <div class="input-wrap">
      <label>
        <span class="d-block">Phone <span class="asterisk--required">*</span></span>
        <input name="guest_phone_number" type="tel" v-model="phone_number" v-validate="'required'">
        <span class="d-block">{{ validator.first('guest_phone_number') }}</span>
      </label>
      </div>

      <div class="input-wrap">
      <label>
        <span class="d-block">ZIP Code <span class="asterisk--required">*</span></span>
        <input name="guest_zip_code" type="tel" v-model="zip_code" v-validate="'required'">
        <span class="d-block">{{ validator.first('guest_zip_code') }}</span>
      </label>
      </div>

      <fieldset class="fieldset">

        <legend>Do you have access to a pool? <span class="asterisk--required">*</span></legend>

        <div class="input-wrap">
        <label>
          <span class="d-block">Yes</span>
          <input name="guest_pool_access" type="radio" v-model="pool_access" value="true" v-validate="'required'">
        </label>
        </div>

        <div class="input-wrap">
        <label>
          <span class="d-block">No</span>
          <input name="guest_pool_access" type="radio" v-model="pool_access" value="false" v-validate="'required'">
        </label>
        </div>

        <span class="d-block">{{ validator.first('guest_pool_access') }}</span>

      </fieldset>

    </fieldset>

  </div>
</template>

<script>

  export default {

    data: function() {
      return {
        first_name: "",
        last_name: "",
        email_address: "",
        phone_number: "",
        zip_code: "",
        pool_access: "",
        created: null
      }
    }, 

    mounted () {
      this.id = this._uid
    },

    methods: {

      validate: async function() {

        // validate necessary form fields
        var validate = Promise.all([
          this.$validator.validate('guest_first_name'),
          this.$validator.validate('guest_last_name'),
          this.$validator.validate('guest_email_address'),
          this.$validator.validate('guest_phone_number'),
          this.$validator.validate('guest_zip_code'),
          this.$validator.validate('guest_pool_access'),
        ]);

        // await results of promise and ensure they are all true
        var validated = await validate.then(result => validated = result.every(isValid => isValid));

        if(validated) {
          return true;
        }

        return false;

      }, 

      makeParent() {
        var guest = this;
        this.$store.commit('parents/createParent', {
          name: guest.first_name + " " + guest.last_name,
          email: guest.email_address, 
          phone: guest.phone_number,
          students: []
        });
      }

    }

  }
</script>