import Vue from 'vue'
import App from './App.vue'
import store from './store/store'
import VueTheMask from 'vue-the-mask'
import VeeValidate from 'vee-validate'
import { Validator } from 'vee-validate'

Vue.use(VueTheMask)

Vue.use(VeeValidate, {
  errorBagName: 'validator',
  events: 'input|blur|closed'
})

new Vue({
  el: '#app',
  store,
  render: h => h(App)
})

const dict = {
  custom: {
    phone: {
      required: 'Please enter a valid phone number.',
      length: 'Please enter a valid phone number.'
    },
    zip: {
      required: 'Please enter a valid zip code.',
      length: 'Please enter a valid zip code.'
    }
  }
};

Validator.localize('en', dict);