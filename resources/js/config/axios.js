import Vue from 'vue'
import axios from 'axios'
const axiosContext = axios.create({
  baseURL: Vue.prototype.$urlApi,
  headers: {
    'X-Requested-With': 'XMLHttpRequest',
    'X-CSRF-TOKEN': document.head.querySelector('meta[name="csrf-token"]').content
  }
})

axiosContext.interceptors.request.use(config => {
  let token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9sb2NhbGhvc3Q6ODAwMFwvYXBpXC9sb2dpbiIsImlhdCI6MTU3ODkwODU4MywiZXhwIjoxNTc4OTE1NzgzLCJuYmYiOjE1Nzg5MDg1ODMsImp0aSI6IkRPcTE5aDdidEF2SkpvQlkiLCJzdWIiOjEsInBydiI6Ijg3ZTBhZjFlZjlmZDE1ODEyZmRlYzk3MTUzYTE0ZTBiMDQ3NTQ2YWEifQ.tdilC8EWZ-UnF0tkTgSO6WQFG0dFNh0BvxyHPeNyhiw'
  if (sessionStorage.getItem('token')) {
    token = sessionStorage.getItem('token')
  }
  config.headers.Authorization = 'Bearer ' + token
  return config
})
axiosContext.interceptors.response.use(
  response => {
    if (response.data.code === 'format' || response.data.code === 'session' || response.data.code === 'token') {
      sessionStorage.clear()
      window.location = '/'
      return
    }
    return response
  }, error => {
    if (error.response && error.response.status === 419) {
      console.log('419: ' + error)
      window.location = '/'
      return
    }
    return Promise.reject(error)
  })
Vue.prototype.$axios = axiosContext
