import Vue from 'vue'
import App from './App.vue'
import store from './store/store'
import VeeValidate from 'vee-validate'

Vue.use(VeeValidate, {
  errorBagName: 'validator',
  events: 'input|blur'
})

new Vue({
  el: '#app',
  store,
  render: h => h(App)
})
