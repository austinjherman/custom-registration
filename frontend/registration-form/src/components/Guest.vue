<template>
  <div>

    <div class="input-wrap">
      <label>
        <span class="d-block">First Name <span class="asterisk--required">*</span></span>
        <input name="firstName" type="text" v-model="frontend.firstName" v-validate="'required'" data-vv-as="First Name">
        <span class="d-block">{{ validator.first('firstName') }}</span>
      </label>
    </div>

    <div class="input-wrap">
      <label>
        <span class="d-block">Last Name <span class="asterisk--required">*</span></span>
        <input name="lastName" type="text" v-model="frontend.lastName" v-validate="'required'" data-vv-as="Last Name">
        <span class="d-block">{{ validator.first('lastName') }}</span>
      </label>
    </div>

    <div class="input-wrap">
      <label>
        <span class="d-block">Email <span class="asterisk--required">*</span></span>
        <input name="email" type="email" v-model="frontend.email" v-validate="'required|email'" data-vv-as="Email">
        <span class="d-block">{{ validator.first('email') }}</span>
      </label>
    </div>

    <div class="input-wrap">
      <label>
        <span class="d-block">Phone <span class="asterisk--required">*</span></span>
        <input name="phone" type="tel" v-model="frontend.phone" v-validate="'required|length:17'" v-mask="['+1 (###) ###-####']" data-vv-as="Phone">
        <span class="d-block">{{ validator.first('phone') }}</span>
      </label>
    </div>

    <div class="input-wrap">
      <label>
        <span class="d-block">ZIP Code <span class="asterisk--required">*</span></span>
        <input name="zip" type="tel" v-model="frontend.zip" v-validate="'required|length:5'" v-mask="['#####']" data-vv-as="ZIP Code">
        <span class="d-block">{{ validator.first('zip') }}</span>
      </label>
    </div>

    <fieldset class="fieldset">

      <legend>Do you have access to a pool? <span class="asterisk--required">*</span></legend>

      <div class="input-wrap">
        <label>
          <span class="d-block">Yes</span>
          <input name="poolAccess" type="radio" v-model="frontend.poolAccess" value="true" v-validate="'required'" data-vv-as="Pool Access">
        </label>
      </div>

      <div class="input-wrap">
        <label>
          <span class="d-block">No</span>
          <input name="poolAccess" type="radio" v-model="frontend.poolAccess" value="false" v-validate="'required'" data-vv-as="Pool Access">
        </label>
      </div>

      <span class="d-block">{{ validator.first('poolAccess') }}</span>

    </fieldset>

  </div>
</template>

<script>

  export default {

    data() {
      return {
        serverResponse: {},
        frontend: {
          firstName: null,
          lastName: null,
          email: null,
          phone: null,
          zip: null,
          poolAccess: null
        }
      }
    },

    mounted () {
      this.frontend.id = this._uid
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

      sendToApi(formEntryId) {
        var request = {};
        request.first_name = this.frontend.firstName;
        request.last_name = this.frontend.lastName;
        request.email_address = this.frontend.email;
        request.phone_number = this.frontend.phone;
        request.zip_code = this.frontend.zip;
        request.pool_access = this.frontend.poolAccess;
        request.form_entry_id = formEntryId;
        this.$http.post(this.API_BASE_URL + '/guests/create', request)
          .then(response => {
            // update formEntry id so we know we have one
            this.$set(this.apiResponse.guest, 'success', response.data.guest);
            this.$emit('guest:create');
          })
          .catch(error => {
            this.$set(this.apiResponse.guest, 'error', JSON.stringify(error.data));
            this.$emit('guest:create:error');
          });
      }

    }

  }
</script>