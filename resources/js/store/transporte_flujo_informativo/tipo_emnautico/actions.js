import Vue from 'vue'
import {errorMessage, successMessage} from '../../../utils/notificaciones'
import * as message from '../../../utils/resources'

export function getTipo_emnautico(context, data) {
  return Vue.prototype.$axios.post('/api/list_tipo_emnauticos', data)
    .then((result) => {
      const data = result.data
      context.commit('setTipo_emnauticoList', data)
      return data
    }).catch(err => {
      errorMessage(message.eDataError, err)
    })
}

export async function getTipo_emnauticos(context, data) {
  return Vue.prototype.$axios.post('/api/get_tipo_emnauticos', data)
    .then((result) => {
      return result.data.data
    }).catch(err => {
      errorMessage(message.eDataError, err)
    })
}

export function saveTipo_emnautico(context, data) {
  return Vue.prototype.$axios.post('/api/create_tipo_emnauticos', data)
    .then((result) => {
      const data = result.data.data
      context.commit('addTipo_emnautico', data)
      successMessage(message.sCreateRow)
      return data
    }).catch(err => {
      return err.response.data
    })
}

export function editTipo_emnautico(context, data) {
  return Vue.prototype.$axios.put('/api/update_tipo_emnauticos/' + data.id, data)
    .then((result) => {
      context.commit('updateTipo_emnautico', result.data.data)
      successMessage(message.sUpdateRow)
      return result.data.data
    }).catch(err => {
      return err.response.data
    })
}

export function deleteTipo_emnautico(context, id) {
  return Vue.prototype.$axios.post('/api/delete_tipo_emnauticos/' + id)
    .then(() => {
      context.commit('deleteTipo_emnautico', id)
      successMessage(message.sRemoveOk)
      return id
    }).catch(err => {
      errorMessage(message.eRemoveError, err)
    })
}
