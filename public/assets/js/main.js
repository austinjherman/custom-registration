
var fscrForm = new Vue({
  el: '#fscrForm',
  data: {
    api: "http://localhost/floridaswim/wp-json/fscr/v1", 
    formFields: {
      students: {},
    },
    errors: {
      first_name: "",
      last_name: "",
      email_address: "",
      phone_number: "",
      zip_code: "",
      pool_access: ""
    }
  },
  methods: {
    foo: function() {
      //console.log('email input change');
    },
    handleFirstPage: function() {
      var request = {};
      request.first_name = this.formFields.firstName;
      request.last_name = this.formFields.lastName;
      request.email_address = this.formFields.email;
      request.phone_number = this.formFields.phone;
      request.zip_code = this.formFields.zipCode;
      request.pool_access = this.formFields.poolAccess;
      axios.post(this.api + '/registrants/create', request)
        .then(response => {
          //console.log(response);
        })
        .catch(error => {
          var errorResponse = error.response.data.errors;
          if(typeof errorResponse != 'undefined') {
            for (var e in this.errors) {
              if(errorResponse.hasOwnProperty(e)) {
                this.errors[e] = errorResponse[e][0];
              }
              else {
                this.errors[e] = "";
              }
            }
          }
        });
    },
    handleSecondPage: function() {
      console.log(this.formFields.students["1"].name);
    },
    updateStudentObjectName: function(e, n) {
      if(typeof this.formFields.students[n] == 'undefined') {
        this.$set(this.formFields.students, n, {});
      }
      this.formFields.students[n].name = e.target.value;
    },
    updateStudentObjectDob: function(e, n) {
      if(typeof this.formFields.students[n] == 'undefined') {
        this.$set(this.formFields.students, n, {});
      }
      this.formFields.students[n].dob = e.target.value;
    }
  }
});

