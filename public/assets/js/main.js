
var fscrForm = new Vue({
  el: '#fscrForm',
  data: {
    api: "http://localhost/floridaswim/wp-json/fscr/v1", 
    formFields: {}
  },
  methods: {
    foo: function() {
      console.log('email input change');
    },
    handleFirstPage: function() {
      var request = {};
      request.first_name = this.formFields.firstName;
      request.last_name = this.formFields.lastName;
      request.email_address = this.formFields.email;
      request.phone_number = this.formFields.phone;
      request.zip_code = this.formFields.zipCode;
      request.pool_access = this.formFields.poolAccess;
      console.log(request);
      axios.post(this.api + '/registrants/create', request)
        .then(function(response) {
          console.log(response);
        })
        .catch(function(error) {
          console.log(error.response);
        });
    }
  }
});