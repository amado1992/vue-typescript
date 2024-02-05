import Vue from 'vue'
import {errorMessage, successMessage} from '../../../utils/notificaciones'
import * as message from '../../../utils/resources'

export function getTipo_vaservicio(context, data) {
  return Vue.prototype.$axios.post('/api/list_tipo_vaservicios', data)
    .then((result) => {
      const data = result.data
      context.commit('setTipo_vaservicioList', data)
      return data
    }).catch(err => {
      errorMessage(message.eDataError, err)
    })
}

export async function getTipo_vaservicios(context, data) {
  return Vue.prototype.$axios.post('/api/get_tipo_vaservicios', data)
    .then((result) => {
      return result.data.data
    }).catch(err => {
      errorMessage(message.eDataError, err)
    })
}

export function saveTipo_vaservicio(context, data) {
  return Vue.prototype.$axios.post('/api/create_tipo_vaservicios', data)
    .then((result) => {
      const data = result.data.data
      context.commit('addTipo_vaservicio', data)
      successMessage(message.sCreateRow)
      return data
    }).catch(err => {
      return err.response.data
    })
}

export function editTipo_vaservicio(context, data) {
  return Vue.prototype.$axios.put('/api/update_tipo_vaservicios/' + data.id, data)
    .then((result) => {
      context.commit('updateTipo_vaservicio', result.data.data)
      successMessage(message.sUpdateRow)
      return result.data.data
    }).catch(err => {
      return err.response.data
    })
}

export function deleteTipo_vaservicio(context, id) {
  return Vue.prototype.$axios.post('/api/delete_tipo_vaservicios/' + id)
    .then(() => {
      context.commit('deleteTipo_vaservicio', id)
      successMessage(message.sRemoveOk)
      return id
    }).catch(err => {
      errorMessage(message.eRemoveError, err)
    })
}
