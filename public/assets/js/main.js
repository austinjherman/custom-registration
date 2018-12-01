
var fscrForm = new Vue({
  el: '#fscrForm',
  data: {
    formFields: {}
  },
  methods: {
    foo: function() {
      console.log('email input change');
    },
    handleFirstPage: function() {
      console.log(this.formFields.poolAccess);
    }
  }
});