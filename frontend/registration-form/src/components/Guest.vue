<template>
  <div>

    <div class="fscr-d-flex wrap">

      <div class="fscr-input-wrap fscr-input-wrap--half">
        <label class="fscr-input-label">
          <span class="fscr-d-block fscr-input-label-text">First Name <span class="fscr-asterisk--required">*</span></span>
          <input name="firstName" type="text" v-model="firstName" v-validate="'required'" data-vv-as="First Name" class="fscr-input">
          <span class="fscr-d-block fscr-input-error">{{ validator.first('firstName') }}</span>
        </label>
      </div>

      <div class="fscr-input-wrap fscr-input-wrap--half">
        <label class="fscr-input-label">
          <span class="fscr-d-block fscr-input-label-text">Last Name <span class="fscr-asterisk--required">*</span></span>
          <input name="lastName" type="text" v-model="lastName" v-validate="'required'" data-vv-as="Last Name" class="fscr-input">
          <span class="fscr-d-block fscr-input-error">{{ validator.first('lastName') }}</span>
        </label>
      </div>

      <div class="fscr-input-wrap fscr-input-wrap--half">
        <label class="fscr-input-label">
          <span class="fscr-d-block fscr-input-label-text">Email <span class="fscr-asterisk--required">*</span></span>
          <input name="email" type="email" v-model="email" v-validate="'required|email'" data-vv-as="Email" class="fscr-input">
          <span class="fscr-d-block fscr-input-error">{{ validator.first('email') }}</span>
        </label>
      </div>

      <div class="fscr-input-wrap fscr-input-wrap--half">
        <label class="fscr-input-label">
          <span class="fscr-d-block fscr-input-label-text">Phone <span class="fscr-asterisk--required">*</span></span>
          <input name="phone" type="tel" v-model="phone" v-validate="'required|length:17'" v-mask="['+1 (###) ###-####']" data-vv-as="Phone" class="fscr-input">
          <span class="fscr-d-block fscr-input-error">{{ validator.first('phone') }}</span>
        </label>
      </div>

      <div class="fscr-input-wrap fscr-input-wrap--half">
        <label class="fscr-input-label">
          <span class="fscr-d-block fscr-input-label-text">ZIP Code <span class="fscr-asterisk--required">*</span></span>
          <input name="zip" type="tel" v-model="zip" v-validate="'required|length:5'" v-mask="['#####']" data-vv-as="ZIP Code" class="fscr-input">
          <span class="fscr-d-block fscr-input-error">{{ validator.first('zip') }}</span>
        </label>
      </div>

    </div>

    <fieldset class="fscr-fieldset fscr-fieldset--pool-access">

      <legend class="fscr-input-label">Do you have access to a pool? <span class="fscr-asterisk--required">*</span></legend>

      <div class="fscr-input-wrap fscr-d-inline-block">
        <label class="fscr-input-label">
          <span class="fscr-d-block">Yes</span>
          <input name="poolAccess" type="radio" v-model="poolAccess" value="true" v-validate="'required'" data-vv-as="Pool Access">
        </label>
      </div>

      <div class="fscr-input-wrap fscr-d-inline-block">
        <label class="fscr-input-label">
          <span class="fscr-d-block">No</span>
          <input name="poolAccess" type="radio" v-model="poolAccess" value="false" v-validate="'required'" data-vv-as="Pool Access">
        </label>
      </div>

      <span class="fscr-d-block fscr-input-error">{{ validator.first('poolAccess') }}</span>

    </fieldset>

  </div>
</template>

<script>

  export default {

    data() {
      return {
        firstName: null,
        lastName: null,
        email: null,
        phone: null,
        zip: null,
        poolAccess: null,
        serverResponse: {}
      }
    },

    mounted () {
      this.id = this._uid
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

      store(formEntryId) {
        var request = {};
        request.first_name = this.firstName;
        request.last_name = this.lastName;
        request.email_address = this.email;
        request.phone_number = this.phone;
        request.zip_code = this.zip;
        request.pool_access = this.poolAccess;
        request.form_entry_id = formEntryId;
        this.$http.post(this.API_BASE_URL + '/guests/create', request)
          .then(response => {
            // update formEntry id so we know we have one
            this.serverResponse = response.data.guest;
            this.$emit('guest:create');
          })
          .catch(error => {
            this.serverResponse = JSON.stringify(error.data);
            this.$emit('guest:create:error');
          });
      },

      update(formEntryId) {
        var request = {};
        request.first_name = this.firstName;
        request.last_name = this.lastName;
        request.email_address = this.email;
        request.phone_number = this.phone;
        request.zip_code = this.zip;
        request.pool_access = this.poolAccess;
        request.form_entry_id = formEntryId;
        this.$http.put(this.API_BASE_URL + '/guests/update/' + this.serverResponse.id, request)
          .then(response => {
            // update formEntry id so we know we have one
            this.serverResponse = response.data.guest;
            this.$emit('guest:create');
          })
          .catch(error => {
            this.serverResponse = JSON.stringify(error.data);
            this.$emit('guest:create:error');
          });
      },

      isDirty() {
        if(Object.keys(this.serverResponse).length !== 0 && this.serverResponse.hasOwnProperty('id')) {
          var isDirty = 
            this.firstName  != this.serverResponse.first_name ||
            this.lastName   != this.serverResponse.last_name ||
            this.email      != this.serverResponse.email_address ||
            this.phone      != this.serverResponse.phone_number ||
            this.zip        != this.serverResponse.zip_code ||
            this.poolAccess != this.serverResponse.pool_access;
          console.log('guest is dirty: ', isDirty);
          return isDirty;
        }
        return true;
      },

    }

  }
</script>

<style lang="scss" scoped>
  .fscr {
    .fscr-fieldset--pool-access {
      margin-top: 25px;
    }
  }
</style>