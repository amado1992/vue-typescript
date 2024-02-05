import Vue from 'vue'
import {errorMessage, successMessage} from '../../../utils/notificaciones'
import * as message from '../../../utils/resources'

export function getTipo_vturistico(context, data) {
  return Vue.prototype.$axios.post('/api/list_tipo_vturisticos', data)
    .then((result) => {
      const data = result.data
      context.commit('setTipo_vturisticoList', data)
      return data
    }).catch(err => {
      errorMessage(message.eDataError, err)
    })
}

export async function getTipo_vturisticos(context, data) {
  return Vue.prototype.$axios.post('/api/get_tipo_vturisticos', data)
    .then((result) => {
      return result.data.data
    }).catch(err => {
      errorMessage(message.eDataError, err)
    })
}

export function saveTipo_vturistico(context, data) {
  return Vue.prototype.$axios.post('/api/create_tipo_vturisticos', data)
    .then((result) => {
      const data = result.data.data
      context.commit('addTipo_vturistico', data)
      successMessage(message.sCreateRow)
      return data
    }).catch(err => {
      return err.response.data
    })
}

export function editTipo_vturistico(context, data) {
  return Vue.prototype.$axios.put('/api/update_tipo_vturisticos/' + data.id, data)
    .then((result) => {
      context.commit('updateTipo_vturistico', result.data.data)
      successMessage(message.sUpdateRow)
      return result.data.data
    }).catch(err => {
      return err.response.data
    })
}

export function deleteTipo_vturistico(context, id) {
  return Vue.prototype.$axios.post('/api/delete_tipo_vturisticos/' + id)
    .then(() => {
      context.commit('deleteTipo_vturistico', id)
      successMessage(message.sRemoveOk)
      return id
    }).catch(err => {
      errorMessage(message.eRemoveError, err)
    })
}
