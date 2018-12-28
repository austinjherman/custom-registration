import Vue from 'vue'
import App from './App.vue'
import VeeValidate from 'vee-validate';

Vue.use(VeeValidate, {
  errorBagName: 'validator'
});

new Vue({
  el: '#app',
  render: h => h(App)
})
