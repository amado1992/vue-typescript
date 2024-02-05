import Vue from 'vue'
import Vuelidate from 'vuelidate'
Vue.use(Vuelidate)
const urlApi = '/'
const urlDownloadFile = '/storage'

// Vue.use(VeeValidate)
Vue.prototype.$urlApi = urlApi
Vue.prototype.$urlDownloadFile = urlDownloadFile
