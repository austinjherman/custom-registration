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

  import GlobalState from '../GlobalState'

  export default {

    data() {
      return {
        globalState: GlobalState,
        firstName: null,
        lastName: null,
        email: null,
        phone: null,
        zip: null,
        poolAccess: null,
        serverResponse: {
          guest: {
            error: null,
            success: {}
          },
          parent: {
            error: null,
            success: {}
          }
        }
      }
    },

    mounted () {
      this.id = this._uid
    },

    methods: {

      /**
       * Validate the guest input fields.
       *
       * @param none
       * @return boolean
       */
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

      /**
       * Store this guest in the database.
       *
       * @param none
       * @return Promise
       */
      store() {

        var request = {};
        request.first_name = this.firstName;
        request.last_name = this.lastName;
        request.email_address = this.email;
        request.phone_number = this.phone;
        request.zip_code = this.zip;
        request.pool_access = this.poolAccess;
        request.form_entry_id = this.globalState.serverResponse.form.success.id;

        return new Promise((resolve, reject) => {
          this.$http.post(this.API_BASE_URL + '/guests/create', request)
            .then(response => {
              this.$set(this.serverResponse.guest, 'success', response.data.guest);
              resolve(response.data.guest);
            })
            .catch(error => {
              this.$set(this.serverResponse.guest, 'error', error.data);
              reject(error);
            });
        });

      },

      /**
       * Update this guest in the database.
       *
       * @param none
       * @return Promise
       */
      update() {

        var request = {};
        request.first_name = this.firstName;
        request.last_name = this.lastName;
        request.email_address = this.email;
        request.phone_number = this.phone;
        request.zip_code = this.zip;
        request.pool_access = this.poolAccess;
        request.form_entry_id = this.globalState.serverResponse.form.success.id;

        return new Promise((resolve, reject) => {
          this.$http.put(this.API_BASE_URL + '/guests/update/' + this.serverResponse.guest.success.id, request)
            .then(response => {
              this.$set(this.serverResponse.guest, 'success', response.data.guest);
              resolve(response.data.guest);
            })
            .catch(error => {
              this.$set(this.serverResponse.guest, 'error', error.data);
              reject(error);
            });
        });

      },

      /**
       * Create a new parent with this guest's information.
       *
       * @param none
       * @return Promise
       */
      saveAsParent() {

        var parent = {};
        parent.name = this.firstName + " " + this.lastName;
        parent.email = this.email;
        parent.phone_number = this.phone;
        parent.form_entry_id = this.globalState.serverResponse.form.success.id;

        return new Promise((resolve, reject) => {
          this.$http.post(this.API_BASE_URL + '/guardians/create', parent)
            .then((response) => {
              this.$set(this.serverResponse.parent, 'success', response.data.guardian);
              resolve(response.data.guardian);
            })
            .catch((error) => {
              this.$set(this.serverResponse.parent, 'error', error.data);
              reject(error);
            })
        });

      },

      /**
       * Update the parent that was created with this guest's information.
       *
       * @param none
       * @return Promise
       */
      updateAsParent() {

        var parent = {};
        parent.name = this.firstName + " " + this.lastName;
        parent.email = this.email;
        parent.phone_number = this.phone;
        parent.form_entry_id = this.globalState.serverResponse.form.success.id;

        return new Promise((resolve, reject) => {
          this.$http.post(this.API_BASE_URL + '/guardians/update/' + this.serverResponse.parent.success.id, parent)
            .then((response) => {
              this.$set(this.serverResponse.parent, 'success', response.data.guardian);
              resolve(response.data.guardian);
            })
            .catch((error) => {
              this.$set(this.serverResponse.parent, 'error', error.data);
              reject(error);
            })
        });

      },

      /**
       * Delete the parent that was created with this guest's information.
       *
       * @param none
       * @return Promise
       */
      deleteAsParent() {
        return new Promise((resolve, reject) => {
          this.$http.delete(this.API_BASE_URL + '/guardians/delete/' + this.serverResponse.parent.id)
          .then((response) => {
            this.$set(this.serverResponse.parent, 'success', {});
            resolve(this.serverResponse.parent.success);
          })
          .catch((error) => {
            this.$set(this.serverResponse.parent, 'error', error);
            reject(error);
          })
        });
      },

      /**
       * Determines if guest fields are different than what they are on the server.
       *
       * @param none
       * @return Boolean
       */
      isDirty() {
        if(Object.keys(this.serverResponse.guest).length !== 0 && this.serverResponse.guest.hasOwnProperty('id')) {
          var isDirty = 
            this.firstName  != this.serverResponse.guest.first_name ||
            this.lastName   != this.serverResponse.guest.last_name ||
            this.email      != this.serverResponse.guest.email_address ||
            this.phone      != this.serverResponse.guest.phone_number ||
            this.zip        != this.serverResponse.guest.zip_code ||
            this.poolAccess != this.serverResponse.guest.pool_access;
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