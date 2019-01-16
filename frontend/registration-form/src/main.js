import Vue from 'vue'
import App from './App.vue'
import VueTheMask from 'vue-the-mask'
import VeeValidate from 'vee-validate'
import { Validator } from 'vee-validate'
import Axios from 'axios'

Vue.prototype.$http = Axios;
Vue.prototype.API_BASE_URL = 'http://localhost/floridaswim/wp-json/fscr/v1';

Vue.use(VueTheMask)

Vue.use(VeeValidate, {
  errorBagName: 'validator',
  events: 'input|blur|closed'
})

window['app'] = new Vue({
  el: '#app',
  render: h => h(App)
})

const dict = {
  custom: {
    phone: {
      required: 'Please enter a valid phone number.',
      length: 'Please enter a valid phone number.'
    },
    zip: {
      required: 'Please enter a valid ZIP Code.',
      length: 'Please enter a valid ZIP Code.'
    },
    email: {
      required: 'Please enter a valid email address.',
      email: 'Please enter a valid email address.'
    },
    numberOfStudents: {
      required: 'Please select the amount of students you\'d like to enroll.',
      min_value: 'Please select the amount of students you\'d like to enroll.'
    }
  }
};

Validator.localize('en', dict);