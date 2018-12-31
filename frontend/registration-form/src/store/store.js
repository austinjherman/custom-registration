import Vue from 'vue'
import Vuex from 'vuex'
import studentModule from './modules/StudentModule.js'
import parentModule from './modules/ParentModule.js'

Vue.use(Vuex)

export default new Vuex.Store({

  modules: {
    parents: parentModule,
    students: studentModule
  },

})